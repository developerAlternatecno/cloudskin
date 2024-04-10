<div class="tab-pane fade show active" id="{{ $field['name'] }}" role="tabpanel">
    @if(isset($field['content']))
        {!! $field['content'] !!}
    @endif
</div>