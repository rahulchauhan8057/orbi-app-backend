@extends('Layout.app')

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Properties Details</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Properties
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card_header1 mb-3">
                        <div class="title">
                            <h5 class="card-title">Properties List</h5>
                        </div>

                        <div class="addNew_property">
                            <a href="" class="add-new">Add New Properties</a>
                        </div>
                    </div>
                    <!-- Display Any Type of Message (Success, Error, etc.) -->
                    @if (session('message'))
                    <div class="alert alert-{{ session('message.type') }}">
                        {{ session('message.content') }}
                    </div>
                    @endif
                    {{-- <div class="properties_dropdown">
                        <div class="dropdown_select">
                            <select class="form-select select-users" id="user_id" name="user_id">
                                <option value="">Select users</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button id="resetFilter" class="btn">Reset</button>
                    </div> --}}

                    <div class="content_body">
                        <div class="table-responsive">
                            <table class="table table-bordered service-datatable" id="landing-properties-table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Property Image</th>
                                        <th>Property Type</th>
                                        <th>Property Area</th>
                                        <th>Price</th>
                                        <th>Property Location</th>
                                        <th>Status</th>
                                        <th>created_at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            console.log("Initializing DataTable...");
            $('#landing-properties-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "",
                    type: 'GET',
                },
                columns: [
                 
                    {
                        data: 'owner_name',
                        name: 'owner_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    // { data: 'image', name: 'image', orderable: false, searchable: false },
                    // { data: 'type', name: 'type' },
                    // { data: 'listing', name: 'listing' },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'size',
                        name: 'size'
                    },
                    // { data: 'location', name: 'location' },
                    // { data: 'area', name: 'area' },
                    // { data: 'connectivity', name: 'connectivity' },
                    // { data: 'emi', name: 'emi' },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
            });
        });
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            var propertyId = $(this).data('id'); // Get the property ID dynamically

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        // url: "{{ route('properties.destroy', ':id') }}".replace(':id', propertyId),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire(
                                    'Deleted!',
                                    'Property has been deleted.',
                                    'success'
                                ).then(() => {
                                    $('#landing-properties-table').DataTable().ajax.reload();
                                });

                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Something went wrong while deleting.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

    </script>
@endpush --}}
