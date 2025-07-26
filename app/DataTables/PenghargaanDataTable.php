<?php

namespace App\DataTables;

use App\Models\Penghargaan;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class PenghargaanDataTable extends DataTable
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
            ->addColumn('action', function(Penghargaan $row){
                $btn = '<div class="dropdown">
                    <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" id="dropdown-default-outline-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                    </button>
                    <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-outline-primary" style="">';
                $btn .= '<a class="dropdown-item" href="'. route('penghargaan.edit', $row->id).'"><i class="si si-note me-1"></i>Ubah</a>';
                $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="hapus('.$row->id.')"><i class="si si-trash me-1"></i>Hapus</a>';
                $btn .= '</div></div>';
                return $btn;
            })
            ->editColumn('tgl', function ($row) {
                return Carbon::parse($row->tgl)->translatedFormat('d F Y');
            })
            ->editColumn('status', function ($row) {
                if($row->status == 'pending'){
                    return '<span class="badge bg-warning">Pending</span>';
                }else if($row->status == 'proses'){
                    return '<span class="badge bg-primary">Proses</span>';
                }else if($row->status == 'selesai'){
                    return '<span class="badge bg-success">Selesai</span>';
                }
            })
            ->rawColumns(['status','action'])
            ->addIndexColumn()
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Penghargaan $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Penghargaan $model): QueryBuilder
    {
        return $model->newQuery()->with('ekskul')->select('penghargaan.*');

        // return $data;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('penghargaan-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom("<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>")
                    ->orderBy(1);
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
            Column::make('ekskul.nama')->title('Ekskul'),
            Column::make('tgl')->title('Tanggal'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'penghargaan' . date('YmdHis');
    }
}
