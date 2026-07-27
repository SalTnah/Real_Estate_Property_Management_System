<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class CheckInactiveProperties extends Command
{
    protected $signature = 'properties:check-inactive';
    protected $description = 'Check for properties with no recent activity and dispatch inactivity notifications';

    public function handle()
    {
        $inactivityDays = 30;
        $thresholdDate = Carbon::now()->subDays($inactivityDays);

        $inactiveProperties = Property::where('last_activity_at', '<', $thresholdDate)
            ->orWhereNull('last_activity_at')
            ->get();

        $recentNotifications = Notification::where('type', 'property_inactive')
            ->where('created_at', '>', $thresholdDate)
            ->get();

        $count = 0;

        foreach ($inactiveProperties as $property) {
            $alreadyNotified = $recentNotifications->contains(function ($notification) use ($property) {
                $data = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;
                return isset($data['property_id']) && $data['property_id'] == $property->id;
            });

            if (!$alreadyNotified) {
                $propertyTitle = $property->title ?? 'Property #' . $property->id;
                $messageText = "Property has been inactive for over {$inactivityDays} days.";

                // Include direct table columns required by the database schema
                $notificationData = [
                    'type' => 'property_inactive',
                    'title' => 'Property Inactive: ' . $propertyTitle, // Direct column required by table schema
                    'agent_id' => $property->agent_id, 
                    'data' => [
                        'property_id' => $property->id,
                        'title' => $propertyTitle,
                        'message' => $messageText,
                    ],
                ];

                $recipients = [];

                if ($property->agent && isset($property->agent->user_id)) {
                    $recipients[] = $property->agent->user_id;
                }

                $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
                $recipients = array_unique(array_merge($recipients, $adminIds));

                if (!empty($recipients)) {
                    foreach ($recipients as $userId) {
                        $notificationData['user_id'] = $userId;
                        Notification::create($notificationData);
                    }
                } else {
                    Notification::create($notificationData);
                }

                $count++;
            }
        }

        $this->info("Checked properties: Dispatched {$count} inactivity notifications.");
    }
}