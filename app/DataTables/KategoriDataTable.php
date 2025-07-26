<?php

namespace App\DataTables;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

use Carbon\Carbon;

class KategoriDataTable extends DataTable
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
                    // $btn .= '<a class="dropdown-item" href="'. route('kategori.edit', $row->id).'"><i class="si si-note me-1"></i>Ubah</a>';
                    $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="hapus('.$row->id.')"><i class="si si-trash me-1"></i>Hapus</a>';
                $btn .= '</div></div>';
                return $btn;
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->tgl)->translatedFormat('d F Y');
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Kategori $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Kategori $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                ->setTableId('kategori-table')
                ->columns($this->getColumns())
                ->minifiedAjax()
                ->dom("<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>")
                ->orderBy(1)
                ->buttons([]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('nama')->title('Nama'),
            Column::make('created_at')->title('Dibuat'),
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
        return 'Kategori_' . date('YmdHis');
    }
}
