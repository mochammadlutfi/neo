<?php

namespace App\DataTables;

use App\Models\Ekskul;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

use Carbon\Carbon;
class EkskulDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function($row){
                $btn = '<div class="dropdown">
                    <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" id="dropdown-default-outline-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                    </button>
                    <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-outline-primary" style="">';
                $btn .= '<a class="dropdown-item" href="'. route('ekskul.show', $row->id).'"><i class="si si-eye me-1"></i>Detail</a>';
                    $btn .= '<a class="dropdown-item" href="'. route('ekskul.edit', $row->id).'"><i class="si si-note me-1"></i>Ubah</a>';
                    $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="hapus('.$row->id.')"><i class="si si-trash me-1"></i>Hapus</a>';
                $btn .= '</div></div>';
                return $btn;
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat('d l Y');
            })
            ->editColumn('status', function ($row) {
                if($row->status == 0){
                    return '<span class="badge bg-danger">Tidak Aktif</span>';
                }else{
                    return '<span class="badge bg-primary">Aktif</span>';
                }
            })
            ->rawColumns(['status','action'])
            ->addIndexColumn()
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Ekskul $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Ekskul $model): QueryBuilder
    {
        return $model->newQuery()->with('pembina')->select('ekskul.*');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('ekskul-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom("<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>")
                    ->orderBy(1)
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->orderable(false)->searchable(false),
            Column::make('nama')->title('Nama'),
            Column::make('pembina.nama')->title('Pembina'),
            Column::make('tempat')->title('Tempat'),
            Column::make('created_at')->title('Dibuat'),
            Column::make('status')->title('Status'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center')->title('Aksi'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Ekskul_' . date('YmdHis');
    }
}
