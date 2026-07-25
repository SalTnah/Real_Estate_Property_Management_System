@if ($property->agent)
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Listing Agent</p>
        <div class="mt-3 flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-sm font-semibold text-white">
                {{ strtoupper(substr($property->agent->f_name, 0, 1).substr($property->agent->l_name, 0, 1)) }}
            </span>
            <div>
                <p class="font-medium text-gray-900">{{ $property->agent->f_name }} {{ $property->agent->l_name }}</p>
                <p class="text-sm text-gray-500">{{ $property->agent->agency_name }}{{ $property->agent->phone_num ? ' · '.$property->agent->phone_num : '' }}</p>
            </div>
        </div>
    </div>
@endif
