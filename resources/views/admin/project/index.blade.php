<x-app-layout>
    <div class="content">
        <!-- Page Header -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-briefcase me-2 text-primary"></i>
                    Kelola Project
                </h3>
                <div class="block-options">
                    @if(in_array(auth()->guard('admin')->user()->level, ['Content Planner']))
                    <a href="{{ route('admin.project.create') }}" class="btn btn-sm btn-primary fs-base">
                        <i class="fa fa-plus me-1"></i>
                        Tambah Project
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row" id="stats-container">
            <div class="col-lg-3 col-6">
                <div class="block block-rounded">
                    <div class="block-content p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary-light rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fa fa-briefcase fa-lg text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fs-3 fw-bold text-dark" id="total-projects">0</div>
                                <div class="fs-xs text-muted">Total Project</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="block block-rounded">
                    <div class="block-content p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-success-light rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fa fa-check-circle fa-lg text-success"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fs-3 fw-bold text-dark" id="active-projects">0</div>
                                <div class="fs-xs text-muted">Project Aktif</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="block block-rounded">
                    <div class="block-content p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-info-light rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fa fa-tasks fa-lg text-info"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fs-3 fw-bold text-dark" id="total-tasks">0</div>
                                <div class="fs-xs text-muted">Total Tugas</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="block block-rounded">
                    <div class="block-content p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-warning-light rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fa fa-clock fa-lg text-warning"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fs-3 fw-bold text-dark" id="pending-tasks">0</div>
                                <div class="fs-xs text-muted">Tugas Pending</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="block block-rounded">
            <div class="block-content">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa fa-search"></i>
                            </span>
                            <input type="text" class="form-control fs-base" id="project-search" placeholder="Cari project...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select fs-base" id="payment-filter">
                            <option value="">Semua Status Pembayaran</option>
                            <option value="Belum Bayar">Belum Bayar</option>
                            <option value="Down Payment">Down Payment</option>
                            <option value="Lunas">Lunas</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select fs-base" id="package-filter">
                            <option value="">Semua Paket</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-secondary w-100" id="reset-filters">
                            <i class="fa fa-refresh me-1"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="row" id="projects-container">
            <!-- Projects will be loaded here -->
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4" id="pagination-container">
            <div class="fs-base text-muted">
                Menampilkan <span id="showing-count">0</span> dari <span id="total-count">0</span> project
            </div>
            <nav aria-label="Project pagination">
                <ul class="pagination pagination-sm mb-0" id="project-pagination">
                    <!-- Pagination will be generated by JavaScript -->
                </ul>
            </nav>
        </div>

        <!-- No Results -->
        <div id="no-results" class="text-center py-5" style="display: none;">
            <i class="fa fa-search fa-4x text-muted mb-4"></i>
            <h4 class="fs-base fw-bold text-muted mb-2">Tidak ada project yang ditemukan</h4>
            <p class="fs-base text-muted">Coba ubah kata kunci pencarian atau filter</p>
        </div>
    </div>
    
    @push('scripts')
        <script>
            $(document).ready(function() {
                const itemsPerPage = 6;
                let currentPage = 1;
                let filteredProjects = [];
                let allProjects = [];
                let allPackages = [];

                // Load initial data
                loadProjects();

                // Filter and search functionality
                $('#project-search').on('input', debounce(filterProjects, 300));
                $('#payment-filter, #package-filter').on('change', filterProjects);
                $('#reset-filters').on('click', resetFilters);

                // Pagination click handler
                $(document).on('click', '#project-pagination .page-link', function(e) {
                    e.preventDefault();
                    const page = parseInt($(this).data('page'));
                    if (page && page !== currentPage) {
                        currentPage = page;
                        updateDisplay();
                        $('html, body').animate({
                            scrollTop: $('#projects-container').offset().top - 100
                        }, 500);
                    }
                });

                function loadProjects() {
                    $.ajax({
                        url: "{{ route('admin.project.index') }}",
                        type: "GET",
                        data: { ajax: true },
                        success: function(response) {
                            if (response.data) {
                                allProjects = response.data;
                                filteredProjects = [...allProjects];
                                
                                // Extract unique packages for filter
                                allPackages = [...new Set(allProjects.map(p => p.order.paket.nama))];
                                populatePackageFilter();
                                
                                // Update statistics
                                updateStatistics();
                                
                                // Initial display
                                updateDisplay();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading projects:', error);
                        }
                    });
                }

                function populatePackageFilter() {
                    const packageSelect = $('#package-filter');
                    packageSelect.find('option:not(:first)').remove();
                    allPackages.forEach(package => {
                        packageSelect.append(`<option value="${package}">${package}</option>`);
                    });
                }

                function updateStatistics() {
                    const totalProjects = allProjects.length;
                    const activeProjects = allProjects.filter(p => p.order.status_pembayaran === 'Lunas').length;
                    const totalTasks = allProjects.reduce((sum, p) => sum + parseInt(p.task_count || 0, 10), 0);
                    const pendingTasks = allProjects.reduce((sum, p) => sum + parseInt(p.pending_tasks || 0, 10), 0);

                    $('#total-projects').text(totalProjects);
                    $('#active-projects').text(activeProjects);
                    $('#total-tasks').text(totalTasks);
                    $('#pending-tasks').text(pendingTasks);
                }

                function filterProjects() {
                    const searchTerm = $('#project-search').val().toLowerCase();
                    const paymentFilter = $('#payment-filter').val();
                    const packageFilter = $('#package-filter').val();

                    filteredProjects = allProjects.filter(project => {
                        const matchesSearch = searchTerm === '' || 
                            project.nama.toLowerCase().includes(searchTerm) ||
                            project.user.nama.toLowerCase().includes(searchTerm) ||
                            project.order.nomor.toLowerCase().includes(searchTerm);
                        
                        const matchesPayment = paymentFilter === '' || 
                            project.order.status_pembayaran === paymentFilter;
                        
                        const matchesPackage = packageFilter === '' || 
                            project.order.paket.nama === packageFilter;
                        
                        return matchesSearch && matchesPayment && matchesPackage;
                    });

                    currentPage = 1;
                    updateDisplay();
                }

                function resetFilters() {
                    $('#project-search').val('');
                    $('#payment-filter').val('');
                    $('#package-filter').val('');
                    filteredProjects = [...allProjects];
                    currentPage = 1;
                    updateDisplay();
                }

                function updateDisplay() {
                    const container = $('#projects-container');
                    container.empty();

                    const totalItems = filteredProjects.length;
                    const totalPages = Math.ceil(totalItems / itemsPerPage);
                    const startIndex = (currentPage - 1) * itemsPerPage;
                    const endIndex = Math.min(startIndex + itemsPerPage, totalItems);

                    // Show projects for current page
                    for (let i = startIndex; i < endIndex; i++) {
                        if (filteredProjects[i]) {
                            container.append(createProjectCard(filteredProjects[i]));
                        }
                    }

                    // Update pagination info
                    $('#showing-count').text(endIndex - startIndex);
                    $('#total-count').text(totalItems);

                    // Update pagination controls
                    updatePagination(totalPages);

                    // Handle no results
                    if (totalItems === 0) {
                        $('#no-results').show();
                        $('#pagination-container').hide();
                    } else {
                        $('#no-results').hide();
                        $('#pagination-container').show();
                    }
                }

                function createProjectCard(project) {
                    const completedTasks = project.completed_tasks || 0;
                    const totalTasks = project.task_count || 0;
                    const progress = totalTasks > 0 ? (completedTasks / totalTasks) * 100 : 0;
                    let statusBadge = '';
                    switch(project.order.status_pembayaran) {
                        case 'Belum Bayar':
                            statusBadge = '<span class="badge bg-danger fs-xs">Belum Bayar</span>';
                            break;
                        case 'Down Payment':
                            statusBadge = '<span class="badge bg-warning fs-xs">Down Payment</span>';
                            break;
                        case 'Lunas':
                            statusBadge = '<span class="badge bg-success fs-xs">Lunas</span>';
                            break;
                    }

                    return `
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="block block-rounded h-100">
                                <div class="block-header bg-body-light">
                                    <div class="w-100 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="block-title fs-base fw-bold mb-1">${project.nama}</h4>
                                            <div class="fs-xs text-muted">${project.order.nomor}</div>
                                        </div>
                                        <div>${statusBadge}</div>
                                    </div>
                                </div>
                                <div class="block-content flex-grow-1 d-flex flex-column">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0">
                                            <i class="fa fa-user fa-2x text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fs-base fw-bold mb-1">${project.user.nama}</div>
                                            <div class="fs-xs text-muted">${project.order.paket.nama} - ${project.order.durasi} Bulan</div>
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <div class="p-2 bg-body rounded text-center">
                                                <div class="fs-xs text-muted">Total Tugas</div>
                                                <div class="fs-base fw-bold text-primary">${totalTasks}</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 bg-body rounded text-center">
                                                <div class="fs-xs text-muted">Selesai</div>
                                                <div class="fs-base fw-bold text-success">${completedTasks}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between fs-xs text-muted mb-1">
                                            <span>Progress Tugas</span>
                                            <span>${Math.round(progress)}%</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: ${progress}%"></div>
                                        </div>
                                    </div>
                                    <div class="mt-auto">
                                        <div class="d-flex gap-2">
                                            <a href="/admin/project/${project.id}" class="btn btn-primary btn-sm flex-grow-1 fs-xs">
                                                <i class="fa fa-eye me-1"></i>
                                                Detail
                                            </a>
                                            <a href="/admin/project/${project.id}/edit" class="btn btn-outline-warning btn-sm fs-xs">
                                                <i class="fa fa-edit me-1"></i>
                                                Edit
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm fs-xs" onclick="hapus(${project.id})">
                                                <i class="fa fa-trash me-1"></i>
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }

                function updatePagination(totalPages) {
                    const pagination = $('#project-pagination');
                    pagination.empty();

                    if (totalPages <= 1) {
                        return;
                    }

                    // Previous button
                    const prevDisabled = currentPage === 1 ? 'disabled' : '';
                    pagination.append(`
                        <li class="page-item ${prevDisabled}">
                            <a class="page-link" href="#" data-page="${currentPage - 1}">
                                <i class="fa fa-chevron-left"></i>
                            </a>
                        </li>
                    `);

                    // Page numbers
                    const startPage = Math.max(1, currentPage - 2);
                    const endPage = Math.min(totalPages, currentPage + 2);

                    if (startPage > 1) {
                        pagination.append(`
                            <li class="page-item">
                                <a class="page-link" href="#" data-page="1">1</a>
                            </li>
                        `);
                        if (startPage > 2) {
                            pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                        }
                    }

                    for (let i = startPage; i <= endPage; i++) {
                        const active = i === currentPage ? 'active' : '';
                        pagination.append(`
                            <li class="page-item ${active}">
                                <a class="page-link" href="#" data-page="${i}">${i}</a>
                            </li>
                        `);
                    }

                    if (endPage < totalPages) {
                        if (endPage < totalPages - 1) {
                            pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                        }
                        pagination.append(`
                            <li class="page-item">
                                <a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a>
                            </li>
                        `);
                    }

                    // Next button
                    const nextDisabled = currentPage === totalPages ? 'disabled' : '';
                    pagination.append(`
                        <li class="page-item ${nextDisabled}">
                            <a class="page-link" href="#" data-page="${currentPage + 1}">
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </li>
                    `);
                }

                function debounce(func, wait) {
                    let timeout;
                    return function executedFunction(...args) {
                        const later = () => {
                            clearTimeout(timeout);
                            func(...args);
                        };
                        clearTimeout(timeout);
                        timeout = setTimeout(later, wait);
                    };
                }
            });

            function hapus(id) {
                Swal.fire({
                    icon: 'warning',
                    text: 'Hapus Project?',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: `Tidak, Jangan!`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/project/${id}/delete`,
                            type: "DELETE",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function(data) {
                                if (data.fail == false) {
                                    Swal.fire({
                                        toast: true,
                                        title: "Berhasil",
                                        text: "Project Berhasil Dihapus!",
                                        timer: 1500,
                                        showConfirmButton: false,
                                        icon: 'success',
                                        position: 'top-end'
                                    }).then((result) => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        toast: true,
                                        title: "Gagal",
                                        text: "Project Gagal Dihapus!",
                                        timer: 1500,
                                        showConfirmButton: false,
                                        icon: 'error',
                                        position: 'top-end'
                                    });
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                Swal.fire({
                                    toast: true,
                                    title: "Gagal",
                                    text: "Terjadi Kesalahan Di Server!",
                                    timer: 1500,
                                    showConfirmButton: false,
                                    icon: 'error',
                                    position: 'top-end'
                                });
                            }
                        });
                    }
                });
            }
        </script>
    @endpush

</x-app-layout>

