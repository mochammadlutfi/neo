<?php

namespace App\DataTables;

use App\Models\Anggota;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;
class AnggotaDataTable extends DataTable
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
            ->addColumn('action', function(Anggota $d){
                $btn = '<div class="dropdown">
                    <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" id="dropdown-default-outline-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                    </button>
                    <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-outline-primary" style="">';
                    $btn .= '<a class="dropdown-item" href="'. route('anggota.show', $d->id).'"><i class="si si-eye me-1"></i>Detail</a>';
                    $btn .= '<a class="dropdown-item" href="'. route('anggota.edit', $d->id).'"><i class="si si-note me-1"></i>Ubah</a>';
                    $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="hapus('. $d->id.')"><i class="si si-trash me-1"></i>Hapus</a>';
                $btn .= '</div></div>';
                return $btn; 
            })
            ->setRowId('id')
            ->addIndexColumn()
            ->editColumn('created_at', function (Anggota $user) {
                return Carbon::parse($user->created_at)->translatedFormat('d F Y');
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Anggota $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Anggota $model): QueryBuilder
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
                    ->parameters([
                        'buttons' => false,
                        'serverside' => true,
                    ])
                    ->setTableId('pegawai-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters([
                        'dom' => "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                        'buttons'      => [],
                    ])
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
            Column::make('nis')->title('NIS'),
            Column::make('nama')->title('Nama Lengkap'),
            Column::make('kelas')->title('Kelas'),
            Column::make('created_at')->title('Tanggal Daftar'),
            Column::make('status')->title('Status'),
            Column::computed('action')->title('Aksi')
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
        return 'pegawai_' . date('YmdHis');
    }
}
