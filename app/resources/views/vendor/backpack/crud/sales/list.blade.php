@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        $crud->entity_name_plural => url($crud->route),
        trans('backpack::crud.list') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <div class="container-fluid">
        <h2>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            <small id="datatable_info_stack">{!! $crud->getSubheading() ?? '' !!}</small>
        </h2>
    </div>
@endsection

@section('content')
    <div class="{{ $crud->getListContentClass() }}">

        <div class="row mb-0">
            <div class="col-sm-6">
                @if ( $crud->buttons()->where('stack', 'top')->count() ||  $crud->exportButtons())
                    <div class="d-print-none {{ $crud->hasAccess('create')?'with-border':'' }}">
                        @include('crud::inc.button_stack', ['stack' => 'top'])
                    </div>
                @endif
            </div>
            <div class="col-sm-6">
                <div id="datatable_search_stack" class="mt-sm-0 mt-2 d-print-none"></div>
            </div>
        </div>
        {{-- Backpack List Filters --}}
        @if ($crud->filtersEnabled())
            @include('crud::inc.filters_navbar')
        @endif

        <table id="ventasPendientesTable" class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2" cellspacing="0">
            <thead>
                <tr>
                    {{-- Table columns --}}
                    @foreach ($crud->columns() as $column)
                        <th data-priority="{{ $column['priority'] }}">
                          {!! $column['label'] !!}
                        </th>
                    @endforeach
                    @if ( $crud->buttons()->where('stack', 'line')->count() )
                        <th data-orderable="false"
                            data-priority="{{ $crud->getActionsColumnPriority() }}"
                        >{{ trans('backpack::crud.actions') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
              @foreach ($pendingSales as $pendingSale)
                <tr>
                  @foreach ($crud->columns() as $column)
                    <td>{{ $pendingSale->{$column['name']} }}</td>
                    @endforeach

                  @if ($crud->buttons()->where('stack', 'line')->count())
                  <td>
                    @include('crud::inc.button_stack', ['stack' => 'line'])
                  </td>
                  @endif

                </tr>
              @endforeach
            </tbody>
            <tfoot>
              @foreach ($crud->columns() as $column)
                <th
                  data-priority="{{ $column['priority'] }}"
                >
                    {!! $column['label'] !!}
                </th>
              @endforeach
              @if ( $crud->buttons()->where('stack', 'line')->count() )
                <th data-orderable="false"
                    data-priority="{{ $crud->getActionsColumnPriority() }}"
                >{{ trans('backpack::crud.actions') }}</th>
              @endif 
            </tfoot>
        </table>
        <hr/>
        <table id="ventasHistoricoTable" class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2" cellspacing="0">
            <thead>
                <tr>
                    {{-- Table columns --}}
                    @foreach ($crud->columns() as $column)
                        <th
                          data-priority="{{ $column['priority'] }}">
                          {!! $column['label'] !!}
                        </th>
                    @endforeach
                    @if ( $crud->buttons()->where('stack', 'line')->count() )
                        <th data-orderable="false"
                            data-priority="{{ $crud->getActionsColumnPriority() }}"
                        >{{ trans('backpack::crud.actions') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
              @foreach ($salesHistory as $saleHistory)
                  <tr>
                    @foreach ($crud->columns() as $column)
                      <td>{{ $saleHistory->{$column['name']} }}</td>
                    @endforeach

                    @if ($crud->buttons()->where('stack', 'line')->count())
                    <td>
                      @include('crud::inc.button_stack', ['stack' => 'line'])
                    </td>
                    @endif

                  </tr>
                @endforeach
            </tbody>
            <tfoot>
              @foreach ($crud->columns() as $column)
                <th
                  data-priority="{{ $column['priority'] }}"
                >
                    {!! $column['label'] !!}
                </th>
              @endforeach
              @if ( $crud->buttons()->where('stack', 'line')->count() )
                <th data-orderable="false"
                    data-priority="{{ $crud->getActionsColumnPriority() }}"
                >{{ trans('backpack::crud.actions') }}</th>
              @endif 
            </tfoot>
        </table>

    </div>
@endsection

@section('after_styles')
    <!-- DATA TABLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/crud.css').'?v='.config('backpack.base.cachebusting_string') }}">
    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/form.css').'?v='.config('backpack.base.cachebusting_string') }}">
    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/list.css').'?v='.config('backpack.base.cachebusting_string') }}">

    <!-- CRUD LIST CONTENT - crud_list_styles stack -->
    @stack('crud_list_styles')
@endsection

@section('after_scripts')
    @include('crud::inc.datatables_logic')
    <script src="{{ asset('packages/backpack/crud/js/crud.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>
    <script src="{{ asset('packages/backpack/crud/js/form.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>
    <script src="{{ asset('packages/backpack/crud/js/list.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>

    <script>
        $(document).ready(function() {
            // Inicializar la primera tabla
            $('#ventasPendientesTable').DataTable({
              "paging": true,
              "ordering": false,
              "searching": false,
              "info": true,
              "responsive": true,
            });

          // Inicializar la segunda tabla
          $('#ventasHistoricoTable').DataTable({
              "paging": true,
              "ordering": false,
              "searching": false,
              "info": true,
              "responsive": true,
          });

        });
    </script>

    <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
    @stack('crud_list_scripts')
@endsection
