@extends('layouts.admin')
@section('content')
    @can('unit_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.units.create") }}">
                    إضافة موقع
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            مواقع الدورات
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-unit">
                    <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            الاسم
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($units as $key => $unit)
                        <tr data-entry-id="{{ $unit->id }}">
                            <td>

                            </td>
                            {{-- <td>
                                {{ $unit->id ?? '' }}
                            </td> --}}
                            <td>
                                {{ $unit->name ?? '' }}
                            </td>

                            <td>
                                @can('unit_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.units.show', $unit->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('unit_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.units.edit', $unit->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('unit_delete')
                                    <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>


        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('unit_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.units.massDestroy') }}",
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                    var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                        return $(entry).data('entry-id')
                    });

                    if (ids.length === 0) {
                        alert('{{ trans('global.datatables.zero_selected') }}')

                        return
                    }

                    if (confirm('{{ trans('global.areYouSure') }}')) {
                        $.ajax({
                            headers: {'x-csrf-token': _token},
                            method: 'POST',
                            url: config.url,
                            data: { ids: ids, _method: 'DELETE' }})
                            .done(function () { location.reload() })
                    }
                }
            }
            dtButtons.push(deleteButton)
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                order: [[ 1, 'desc' ]],
                pageLength: 100,
            });
            $('.datatable-unit:not(.ajaxTable)').DataTable({ buttons: dtButtons })
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        })

    </script>
@endsection
