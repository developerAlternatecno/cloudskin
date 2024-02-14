@extends(backpack_view('blank'))

@php
$defaultBreadcrumbs = [
trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
$crud->entity_name_plural => url($crud->route),
trans('backpack::crud.add') => false,
];

// if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
$breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
<section class="container-fluid">
	<h2>
		<span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
		<small>{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</small>

		@if ($crud->hasAccess('list'))
		<small><a href="{{ url($crud->route) }}" class="d-print-none font-sm"><i class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
		@endif
	</h2>
</section>
@endsection

@section('content')

<div class="row">
	<div class="{{ $crud->getCreateContentClass() }}">
		<!-- Default box -->

		@include('crud::inc.grouped_errors')

		<form method="post" action="{{ url($crud->route) }}" @if ($crud->hasUploadFields('create'))
			enctype="multipart/form-data"
			@endif
			>
			{!! csrf_field() !!}
			<!-- load the view from the application if it exists, otherwise load the one in the package -->
			@if(view()->exists('vendor.backpack.crud.form_content'))
			@include('vendor.backpack.crud.form_content', [ 'fields' => $crud->fields(), 'action' => 'create' ])
			@else
			@include('crud::form_content', [ 'fields' => $crud->fields(), 'action' => 'create' ])
			@endif

			@include('crud::inc.form_save_buttons')
		</form>

		@if(isset($diccionarioData))
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script>
			var diccionarioData = @json($diccionarioData);


			$(document).ready(function() {
				var fieldName = 'data_type';
				var selector = '[data-repeatable-input-name="' + fieldName + '"]';

				console.table(diccionarioData);

				$(document).on('change', selector, function() {
					var selectedOption = $(this).val();
					var type = diccionarioData[selectedOption].input_type;
					var descripcion = diccionarioData[selectedOption].description;
					var defaultUnit = diccionarioData[selectedOption].default_unit;

					var rowNumber = $(this).data('row-number');
					var $row = $('#' + rowNumber);

					var selectedValue = $(this).val();

					var descriptionInput = $('[data-repeatable-input-name="description"][data-row-number="' + rowNumber + '"]');
					var typeInput = $('[data-repeatable-input-name="type"][data-row-number="' + rowNumber + '"]');
					var defaultUnitInput = $('[data-repeatable-input-name="unit"][data-row-number="' + rowNumber + '"]');

					descriptionInput.val(descripcion);
					typeInput.val(type);
					defaultUnitInput.val(defaultUnit);

				});
			});
		</script>
		@endif

	</div>
</div>

@endsection