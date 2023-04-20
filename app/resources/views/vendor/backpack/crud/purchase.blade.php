@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => backpack_url('dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.edit') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
	<section class="container-fluid">
	  <h2>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small>Dataset purchase.</small>

        @if ($crud->hasAccess('list'))
          <small><a href="{{ url($crud->route) }}" class="d-print-none font-sm"><i class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
	  </h2>
	</section>
@endsection

@section('content')
<div class="row">
	<div class="{{ $crud->getEditContentClass() }}">
		<!-- Default box -->

		@include('crud::inc.grouped_errors')

		  <form method="post"
		  		action="{{ url('/test-route') }}"
				@if ($crud->hasUploadFields('update', $entry->getKey()))
				enctype="multipart/form-data"
				@endif
                id="purchase-form"
          >
		  {!! csrf_field() !!}
		  {!! method_field('POST') !!}

		  	@if ($crud->model->translationEnabled())
		    <div class="mb-2 text-right">
		    	<!-- Single button -->
				<div class="btn-group">
				  <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    {{trans('backpack::crud.language')}}: {{ $crud->model->getAvailableLocales()[request()->input('locale')?request()->input('locale'):App::getLocale()] }} &nbsp; <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu">
				  	@foreach ($crud->model->getAvailableLocales() as $key => $locale)
					  	<a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?locale={{ $key }}">{{ $locale }}</a>
				  	@endforeach
				  </ul>
				</div>
		    </div>
		    @endif
		      <!-- load the view from the application if it exists, otherwise load the one in the package -->
		      @if(view()->exists('vendor.backpack.crud.form_content'))
		      	@include('vendor.backpack.crud.form_content', ['fields' => $crud->fields(), 'action' => 'purchase'])
		      @else
		      	@include('crud::form_content', ['fields' => $crud->fields(), 'action' => 'purchase'])
		      @endif
            <div id="saveActions" class="form-group">
                <button type="submit" class="btn btn-success">
                    <span class="la la-save" role="presentation" aria-hidden="true"></span> &nbsp;
                    <span data-value="{{ 'purchase' }}">{{ 'Purchase' }}</span>
                </button>
                <a href="{{ $crud->hasAccess('list') ? url($crud->route) : url()->previous() }}" class="btn btn-default"><span class="la la-ban"></span> &nbsp;{{ trans('backpack::crud.cancel') }}</a>
            </div>
          </form>
            <input type="hidden" id="my-input" value="my-value">
	</div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('purchase-form');
        // Agrega un event listener para el evento "submit"
        form.addEventListener('submit', (event) => {
            // Previene el comportamiento por defecto del botón submit
            event.preventDefault();

            // Obtiene los datos del formulario
            const formData = new FormData(form);

            const data = {};
            // Recorrer todos los pares clave-valor del objeto FormData y agregarlos al objeto JSON
            for (const [key, value] of formData.entries()) {
                data[key] = value;
            }

            // Convertir el objeto JSON a una cadena
            const body = JSON.stringify(data);

          // Realiza una petición POST a la URL especificada
          fetch('/api/datasets/'+ {{$crud->getCurrentEntryId()}}+'/purchase', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            }
          })
          .then(response => {
            // Maneja la respuesta de la petición
            console.log(response);
            window.location.href = "http://localhost:8080/admin/dataset";

          })
          .catch(error => {
            // Maneja el error de la petición
            console.error(error);
          });
        });
    });

</script>

