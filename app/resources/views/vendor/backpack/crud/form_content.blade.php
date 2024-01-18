<input type="hidden" name="http_referrer" value="{{ session('referrer_url_override') ?? old('http_referrer') ?? \URL::previous() ?? url($crud->route) }}">

{{-- Verificar si estamos utilizando pestañas --}}
@if ($crud->tabsEnabled() && count($crud->getTabs()))
    @include('crud::inc.show_tabbed_fields')
    <input type="hidden" name="current_tab" value="{{ Str::slug($crud->getTabs()[0]) }}" />
@else
    <div class="card">
        <div class="card-body row">
            @include('crud::inc.show_fields', ['fields' => $crud->fields()])
        </div>
    </div>
@endif

{{-- Definir blade stacks para que se puedan agregar estilos y scripts desde los campos a estas secciones --}}
@section('after_styles')
    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/crud.css').'?v='.config('backpack.base.cachebusting_string') }}">
    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/form.css').'?v='.config('backpack.base.cachebusting_string') }}">
    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/'.$action.'.css').'?v='.config('backpack.base.cachebusting_string') }}">

    <!-- CRUD FORM CONTENT - crud_fields_styles stack -->
    @stack('crud_fields_styles')

    {{-- Corrección temporal en la versión 4.1 --}}
    <style>
        .form-group.required label:not(:empty):not(.form-check-label)::after {
            content: '';
        }
        .form-group.required > label:not(:empty):not(.form-check-label)::after {
            content: ' *';
            color: #ff0000;
        }
    </style>
@endsection

@section('after_scripts')
    <script src="{{ asset('packages/backpack/crud/js/crud.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>
    <script src="{{ asset('packages/backpack/crud/js/form.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>
    <script src="{{ asset('packages/backpack/crud/js/'.$action.'.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>

    <!-- CRUD FORM CONTENT - crud_fields_scripts stack -->
    @stack('crud_fields_scripts')

    <script>
        function initializeFieldsWithJavascript(container) {
            var selector = container instanceof jQuery ? container : $(container);
            
            selector.find("[data-init-function]").not("[data-initialized=true]").each(function () {
                var element = $(this);
                var functionName = element.data('init-function');

                if (typeof window[functionName] === "function") {
                    window[functionName](element);
                    element.attr('data-initialized', 'true');
                }
            });
        }

        jQuery('document').ready(function($){
          $(document).on('change', ':checkbox', function() {
            var checkbox = $('[name="dataset_checkbox"]');
                if (checkbox.val() == '1') {
                    console.log('Checkbox is checked');
                    $('#latitude-field').show();
                    $('#longitude-field').show();
                } else {
                    console.log('Checkbox is not checked');
                    $('#latitude-field').hide();
                    $('#longitude-field').hide();
                }
          });

        $(document).on('change', $('[name="dataset_data_type"]'), function() {
        var valDataType = $('[name="dataset_data_type"]').val();
        if(valDataType == 'Static Data'){
            $('#static_data_upload').show();
            $('#realtime_data_upload').hide();
        }else{
            $('#static_data_upload').hide();
            $('#realtime_data_upload').show();
        }
        });

        })

        jQuery('document').ready(function($){
            initializeFieldsWithJavascript('form');

            var saveActions = $('#saveActions'),
                crudForm = saveActions.parents('form'),
                saveActionField = $('[name="save_action"]');

            saveActions.on('click', '.dropdown-menu a', function(){
                var saveAction = $(this).data('value');
                saveActionField.val(saveAction);
                crudForm.submit();
            });

            $(document).keydown(function(e) {
                if ((e.which == '115' || e.which == '83' ) && (e.ctrlKey || e.metaKey)) {
                    e.preventDefault();
                    $("button[type=submit]").trigger('click');
                    return false;
                }
                return true;
            });

            crudForm.submit(function (event) {
                $("button[type=submit]").prop('disabled', true);
            });

            @if ($crud->getAutoFocusOnFirstField())
                @php
                    $focusField = Arr::first($fields, function($field) {
                        return isset($field['auto_focus']) && $field['auto_focus'] == true;
                    });
                @endphp

                @if ($focusField)
                    @php
                        $focusFieldName = isset($focusField['value']) && is_iterable($focusField['value']) ? $focusField['name'] . '[]' : $focusField['name'];
                    @endphp
                    window.focusField = $('[name="{{ $focusFieldName }}"]').eq(0);
                @else
                    var focusField = $('form').find('input, textarea, select').not('[type="hidden"]').eq(0);
                @endif

                var fieldOffset = focusField.offset().top,
                    scrollTolerance = $(window).height() / 2;

                focusField.trigger('focus');

                if (fieldOffset > scrollTolerance) {
                    $('html, body').animate({scrollTop: (fieldOffset - 30)});
                }
            @endif

            @if ($crud->inlineErrorsEnabled() && $errors->any())
                window.errors = {!! json_encode($errors->messages()) !!};

                $.each(errors, function(property, messages){
                    var normalizedProperty = property.split('.').map(function(item, index){
                        return index === 0 ? item : '['+item+']';
                    }).join('');

                    var field = $('[name="' + normalizedProperty + '[]"]').length ?
                        $('[name="' + normalizedProperty + '[]"]') :
                        $('[name="' + normalizedProperty + '"]'),
                        container = field.parents('.form-group');

                    container.addClass('text-danger');
                    container.children('input, textarea, select').addClass('is-invalid');

                    $.each(messages, function(key, msg){
                        var row = $('<div class="invalid-feedback d-block">' + msg + '</div>');
                        row.appendTo(container);

                        @if ($crud->tabsEnabled())
                            var tab_id = $(container).closest('[role="tabpanel"]').attr('id');
                            $("#form_tabs [aria-controls="+tab_id+"]").addClass('text-danger');
                        @endif
                    });
                });
            @endif

            $("a[data-toggle='tab']").click(function(){
                currentTabName = $(this).attr('tab_name');
                $("input[name='current_tab']").val(currentTabName);
            });

            if (window.location.hash) {
                $("input[name='current_tab']").val(window.location.hash.substr(1));
            }
        });

    </script>
@endsection
