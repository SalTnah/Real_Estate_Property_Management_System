@php
    $appointment = $appointment ?? null;
    $agent = auth()->user()->agent;
    $properties = $agent->properties()->orderBy('title')->get();
    $clients = $agent->clients()->orderBy('f_name')->get();
@endphp

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-input-label for="title" value="Title" />
        <x-text-input id="title" name="title" type="text" required :value="old('title', $appointment?->title)" />
        <x-input-error :messages="$errors->get('title')" />
    </div>

    <div>
        <x-input-label for="appt_type" value="Type" />
        <select id="appt_type" name="appt_type" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @foreach (['Viewing', 'Meeting', 'Call', 'Listing', 'Personal'] as $option)
                <option value="{{ $option }}" @selected(old('appt_type', $appointment?->appt_type) === $option)>{{ $option }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('appt_type')" />
    </div>

    <div>
        <x-input-label for="status" value="Status" />
        <select id="status" name="status" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @foreach (['Scheduled', 'Completed', 'Cancelled', 'No-show'] as $option)
                <option value="{{ $option }}" @selected(old('status', $appointment?->status ?? 'Scheduled') === $option)>{{ $option }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" />
    </div>

    <div>
        <x-input-label for="start_time" value="Start" />
        <x-text-input id="start_time" name="start_time" type="datetime-local" required :value="old('start_time', optional($appointment?->start_time)->format('Y-m-d\TH:i'))" />
        <x-input-error :messages="$errors->get('start_time')" />
    </div>

    <div>
        <x-input-label for="end_time" value="End" />
        <x-text-input id="end_time" name="end_time" type="datetime-local" required :value="old('end_time', optional($appointment?->end_time)->format('Y-m-d\TH:i'))" />
        <x-input-error :messages="$errors->get('end_time')" />
    </div>

    <div>
        <x-input-label for="client_id" value="Client" />
        <select id="client_id" name="client_id" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">None</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @selected((string) old('client_id', $appointment?->client_id) === (string) $client->id)>{{ $client->f_name }} {{ $client->l_name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('client_id')" />
    </div>

    <div>
        <x-input-label for="property_id" value="Property" />
        <select id="property_id" name="property_id" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">None</option>
            @foreach ($properties as $property)
                <option value="{{ $property->id }}" @selected((string) old('property_id', $appointment?->property_id) === (string) $property->id)>{{ $property->title }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('property_id')" />
    </div>

    @if ($appointment)
        <div>
            <x-input-label for="outcome" value="Outcome" />
            <select id="outcome" name="outcome" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">None</option>
                @foreach (['Showed', 'No-show', 'Offer Made', 'Not Interested', 'Rescheduled'] as $option)
                    <option value="{{ $option }}" @selected(old('outcome', $appointment?->outcome) === $option)>{{ $option }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('outcome')" />
        </div>
    @endif
</div>

<div class="mt-4">
    <x-input-label for="notes" value="Notes" />
    <textarea id="notes" name="notes" rows="4" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $appointment?->notes) }}</textarea>
    <x-input-error :messages="$errors->get('notes')" />
</div>
