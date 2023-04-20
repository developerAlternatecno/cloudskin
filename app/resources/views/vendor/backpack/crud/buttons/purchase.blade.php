@if($entry->isPurchased == 0 and $entry->type == 'buyout')
    <a href="{{backpack_url('dataset/'.$entry->id."/purchase")}}" class="btn btn-sm btn-link"><i class="las la-shopping-bag"></i>Purchase</a>
@elseif($entry->isPurchased == 0 and $entry->type == 'rental')
    <a href="{{backpack_url('dataset/'.$entry->id."/purchase")}}" class="btn btn-sm btn-link"><i class="las la-shopping-bag"></i>Rent</a>
@elseif($entry->isPurchased == 1 and $entry->type == 'buyout')
    <span class="btn btn-sm btn-link disabled"><i class="las la-shopping-bag"></i>Purchased</span>
@elseif($entry->isPurchased == 1 and $entry->type == 'rental')
    <span class="btn btn-sm btn-link disabled"><i class="las la-shopping-bag"></i>Rented</span>
@endif

