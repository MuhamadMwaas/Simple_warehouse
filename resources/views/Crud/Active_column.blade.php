{{-- <span class="label {{ $entry['active'] ? 'label-success' : 'label-danger' }}">{{$entry['active']}}</span> --}}
@if ($entry->active)
    <span class="badge rounded-pill bg-success">active</span>
@else
    <span class="badge rounded-pill bg-danger">inactive</span>
@endif
