@extends('layouts.app')
@section('title', __('theme.datatable_title'))

@section('content')
	<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-20">
		<div>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb breadcrumb-style1 mg-b-10">
					<li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ __('general.dashboard') }}</a></li>
					<li class="breadcrumb-item"><a href="{{ url('/dashboard/themes/table') }}">{{ __('general.appearance') }}</a></li>
					<li class="breadcrumb-item"><a href="{{ url('/dashboard/themes/table') }}">{{ __('general.themes') }}</a></li>
					<li class="breadcrumb-item active" aria-current="page">{{ __('theme.datatable_list') }}</li>
				</ol>
			</nav>
			<h4 class="mg-b-0 tx-spacing--1">{{ __('theme.datatable_list') }}</h4>
		</div>
		
		<div><a href="{{ url('dashboard/themes/create') }}" class="btn btn-sm pd-x-15 btn-white btn-uppercase mg-t-10"><i data-feather="plus" class="wd-10 mg-r-5"></i> {{ __('general.add') }}</a></div>
	</div>
	
	<div class="card">
		<div class="card-body">
			{!! Form::open(['url' => 'dashboard/themes/deleteall', 'method' => 'post', 'class' => 'form-horizontal']) !!}
				<input type="hidden" name="totaldata" id="totaldata" value="0" />
				<table class="table table-striped" id="themes-table">
					<thead>
						<tr>
							<th style="text-align:center;" width="15"></th>
							<th style="text-align:center;" width="25">{{ __('general.id') }}</th>
							<th>{{ __('theme.title') }}</th>
							<th>{{ __('theme.author') }}</th>
							<th>{{ __('theme.active') }}</th>
							<th style="text-align:center;" width="140">{{ __('general.actions') }}</th>
							<th></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td style="width:10px;" style="text-align:center;">
								<input type="checkbox" id="titleCheck" data-toggle="tooltip" title="{{ __('general.check_all') }}" />
							</td>
							<td colspan="5">
								<button class="btn btn-sm btn-danger" type="button" data-toggle="modal" data-target="#alertalldel"><i class="fa fa-trash"></i> {{ __('general.delete_selected') }}</button>
							</td>
							<td><button class="btn btn-xs btn-danger d-block d-sm-none" type="button" data-toggle="modal" data-target="#alertalldel">{{ __('general.delete') }}</button></td>
						</tr>
					</tfoot>
				</table>
			{!! Form::close() !!}
		</div>
	</div>
@endsection

@push('scripts')
<script type="text/javascript">
	$(function() {
		'use strict'
		
		var table = $('#themes-table').DataTable({
			processing: true,
			serverSide: true,
			stateSave: true,
			responsive: {
				details: {
					type: 'column',
					target: -1
				}
			},
			ajax: '{{ url("dashboard/themes/data") }}',
			autoWidth: false,
			order: [[1, 'desc']],
			columnDefs: [{
				targets: 'no-sort',
				orderable: false
			},{
				className: 'control',
				orderable: false,
				targets:   -1
			}],
			columns: [
				{ data: 'check', name: 'check', orderable: false, searchable: false },
				{ data: 'id', name: 'themes.id' },
				{ data: 'title', name: 'themes.title' },
				{ data: 'author', name: 'themes.author' },
				{ data: 'active', name: 'themes.active' },
				{ data: 'action', name: 'action', orderable: false, searchable: false },
				{ data: 'control', name: 'control', orderable: false, searchable: false },
			],
			drawCallback: function(settings) {
				$("#titleCheck").click(function() {
					var checkedStatus = this.checked;
					$("table tbody tr td div:first-child input[type=checkbox]").each(function() {
						this.checked = checkedStatus;
						if (checkedStatus == this.checked) {
							$(this).closest('table tbody tr').removeClass('selected');
							$(this).closest('table tbody tr').find('input:hidden').attr('disabled', !this.checked);
							$('#totaldata').val($('table tbody input[type=checkbox]:checked').length);
						}
						if (this.checked) {
							$(this).closest('table tbody tr').addClass('selected');
							$(this).closest('table tbody tr').find('input:hidden').attr('disabled', !this.checked);
							$('#totaldata').val($('table tbody input[type=checkbox]:checked').length);
						}
					});
				});	
				$('table tbody tr td div:first-child input[type=checkbox]').on('click', function () {
					var checkedStatus = this.checked;
					this.checked = checkedStatus;
					if (checkedStatus == this.checked) {
						$(this).closest('table tbody tr').removeClass('selected');
						$(this).closest('table tbody tr').find('input:hidden').attr('disabled', !this.checked);
						$('#totaldata').val($('table tbody input[type=checkbox]:checked').length);
					}
					if (this.checked) {
						$(this).closest('table tbody tr').addClass('selected');
						$(this).closest('table tbody tr').find('input:hidden').attr('disabled', !this.checked);
						$('#totaldata').val($('table tbody input[type=checkbox]:checked').length);
					}
				});
				$('table tbody tr td div:first-child input[type=checkbox]').change(function() {
					$(this).closest('tr').toggleClass("selected", this.checked);
				});
				deleter.init();
				$('[data-toggle="tooltip"]').tooltip();
			},
			language: {
				searchPlaceholder: 'Search...',
				sSearch: '',
				lengthMenu: '_MENU_ items/page',
			}
		});
		
		$('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });
	});
</script>
@endpush
