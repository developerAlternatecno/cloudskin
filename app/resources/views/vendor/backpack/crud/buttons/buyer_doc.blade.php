@if($entry->buyer_doc)
    <a href="{{route('buyer-doc', $entry->id)}}" class="btn btn-sm btn-link"><i class="las la-download"></i>Buyer doc</a>
@else
    <a href="{{route('buyer-doc', $entry->id)}}" class="btn btn-sm btn-link disabled"><i class="las la-download"></i>Buyer doc</a>
@endif
