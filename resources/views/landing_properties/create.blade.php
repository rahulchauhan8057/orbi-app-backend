@extends('Layout.app')

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Properties create</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="">Properties</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Add New Properties
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
                            <h5 class="card-title">Properties create</h5>
                        </div>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="content_body">
                        <div class="create_wrap">
                            <form action="" id="propertyForm" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Select User</label>
                                        <select name="user_id" class="form-control" required>
                                            <option value="">Select User</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}
                                                    ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Owner Name</label>
                                        <input type="text" value="{{ old('owner_name') }}" name="owner_name"
                                            class="form-control" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Email</label>
                                        <input type="text" value="{{ old('email') }}" name="email"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Contact Number</label>
                                        <input type="text" value="{{ old('contact_number') }}" name="contact_number"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Whatsapp Number</label>
                                        <input type="text" value="{{ old('whatsapp_number') }}" name="whatsapp_number"
                                            class="form-control" required>
                                    </div>
                                    <!-- Country -->
                                    <div class="col-md-6 mb-3">
                                        <label for="country">Country</label>
                                        <select name="country_id" id="country" class="form-control" required>
                                            <option value="">Select Country</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- City -->
                                    <div class="col-md-6 mb-3">
                                        <label for="city">City</label>
                                        <select name="city_id" id="city" class="form-control" required>
                                            <option value="">Select City</option>
                                        </select>
                                    </div>

                                    <!-- Location -->
                                    <div class="col-md-6 mb-3">
                                        <label for="location">Location</label>
                                        <select name="location_id" id="location" class="form-control" required>
                                            <option value="">Select Location</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Property Title</label>
                                        <input type="text" value="{{ old('title') }}" name="title"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Property Image</label>
                                        <input type="file" name="property_images[]" class="form-control" multiple>
                                        {{-- <input type="file" value="{{ old('name') }}" name="property_image" class="form-control"> --}}
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label>Description</label>
                                        <textarea value="{{ old('description') }}" name="description" class="form-control" rows="3"></textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Video Link (YouTube/Vimeo)</label>
                                        <input type="url" name="videoUrl" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Property Type</label>
                                        <select value="{{ old('property_type') }}" name="property_type"
                                            class="form-control" required>
                                            <option value="">Select Type</option>
                                            <option value="residential">Residential</option>
                                            <option value="commercial">Commercial</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Listing Type</label>
                                        <select value="{{ old('listing_type') }}" name="listing_type" class="form-control"
                                            required>
                                            <option value="">Select Listing</option>
                                            <option value="rent">Rent</option>
                                            <option value="sale">Sale</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Property Price</label>
                                        <input type="text" value="{{ old('property_price') }}" name="property_price"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Property Size (e.g. 10 yards)</label>
                                        <input type="text" value="{{ old('property_size') }}" name="property_size"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Property Area</label>
                                        <input type="text" value="{{ old('area') }}" name="area"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Latitude</label>
                                        <input type="text" value="{{ old('latitude') }}" name="latitude"
                                            class="form-control">
                                        <a class="help-block"
                                            href="https://www.latlong.net/convert-address-to-lat-long.html"
                                            target="_blank" rel="nofollow"> Go here to get Latitute from address. </a>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Longitude</label>
                                        <input type="text" value="{{ old('longitude') }}" name="longitude"
                                            class="form-control">
                                        <a class="help-block"
                                            href="https://www.latlong.net/convert-address-to-lat-long.html"
                                            target="_blank" rel="nofollow"> Go here to get Longitude from address. </a>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Property Location</label>
                                        <input type="text" name="property_location" class="form-control"
                                            value="{{ old('property_location') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Property Connectivity</label>
                                        <input type="text" value="{{ old('connectivity') }}" name="connectivity"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Property EMI</label>
                                        <input type="text" value="{{ old('emi') }}" name="emi"
                                            class="form-control">
                                    </div>
                                    {{-- <div class="col-md-6 mb-3">
                                        <label>Description</label>
                                        <textarea value="{{ old('description') }}" name="description" class="form-control" rows="3"></textarea>
                                    </div> --}}
                                    <div class="col-md-6 mb-3">
                                        <br>
                                        <label>Property Flags:</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="is_top" id="is_top" value="1" {{ old('is_top') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_top">isTop</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">isFeatured</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="is_popular" id="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_popular">isPopular</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Property Status</label>
                                        <select name="property_status" class="form-control">
                                            @foreach (\App\Models\Property::$propertyStatusLabels as $key => $label)
                                                <option value="{{ $key }}" @selected(old('property_status', $model->property_status ?? '') == $key)>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            @foreach (\App\Models\Property::$statusLabels as $key => $label)
                                                <option value="{{ $key }}" @selected(old('status', $model->status ?? '') == $key)>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Submit Button -->
                                    <div class="col-12 text-center">
                                        <a href="" class="btn btn-primary">Back</a>
                                        <button type="submit" class="btn btn-primary">Save Property</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // When country changes
            $('#country').on('change', function() {
                let countryID = $(this).val();
                $('#city').html('<option value="">Select City</option>');
                $('#location').html('<option value="">Select Location</option>');

                if (countryID) {
                    $.get('/admin/get-cities/' + countryID, function(data) {
                        $.each(data, function(key, city) {
                            $('#city').append('<option value="' + city.id + '">' + city
                                .name + '</option>');
                        });
                    });
                }
            });

            // When city changes
            $('#city').on('change', function() {
                let cityID = $(this).val();
                $('#location').html('<option value="">Select Location</option>');

                if (cityID) {
                    $.get('/admin/get-locations/' + cityID, function(data) {
                        $.each(data, function(key, location) {
                            $('#location').append('<option value="' + location.id + '">' +
                                location.name + '</option>');
                        });
                    });
                }
            });
        });
        $(document).ready(function() {

            $('#propertyForm').validate({
                rules: {
                    owner_name: {
                        required: true,
                        maxlength: 255
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    contact_number: {
                        required: true,
                        maxlength: 10
                    },
                    whatsapp_number: {
                        required: true,
                        maxlength: 10
                    },
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    'property_images[]': {
                        required: true,
                        extension: "jpg|jpeg|png"
                    },
                    videoUrl: {
                        required: true,
                        url: true
                    },
                    property_type: {
                        required: true
                    },
                    listing_type: {
                        required: true
                    },
                    property_price: {
                        required: true,
                        number: true
                    },
                    property_size: {
                        required: true,
                        maxlength: 100
                    },
                    country: {
                        required: true
                    },
                    city: {
                        required: true
                    },
                    location: {
                        required: true
                    },
                    latitude: {
                        required: true,
                        number: true
                    },
                    longitude: {
                        required: true,
                        number: true
                    },
                    property_location: {
                        required: true,
                        maxlength: 255
                    },
                    area: {
                        required: true,
                        maxlength: 100
                    },
                    connectivity: {
                        required: true,
                        maxlength: 100
                    },
                    emi: {
                        number: true
                    },
                    description: {
                        maxlength: 1000
                    }
                },
                messages: {
                    owner_name: {
                        required: "Please enter owner's name.",
                        maxlength: "Owner name can't exceed 255 characters."
                    },
                    email: {
                        required: "Please enter a valid email.",
                        email: "Email format is not valid."
                    },
                    contact_number: {
                        required: "Please enter contact number.",
                        maxlength: "Phone number can't exceed 10 characters."
                    },
                    whatsapp_number: {
                        required: "Please enter WhatsApp number.",
                        maxlength: "Phone number can't exceed 10 characters."
                    },
                    title: {
                        required: "Please enter property title.",
                        maxlength: "Title can't exceed 255 characters."
                    },
                    'property_images[]': {
                        required: "Please upload at least one image.",
                        extension: "Only JPG, JPEG, and PNG formats are allowed."
                    },
                    videoUrl: {
                        required: "Please enter a valid property video link.",
                        url: "Please enter a valid URL (http or https)."
                    },
                    property_type: {
                        required: "Please select a property type."
                    },
                    listing_type: {
                        required: "Please select a listing type."
                    },
                    property_price: {
                        required: "Please enter a price.",
                        number: "Please enter a valid number."
                    },
                    property_size: {
                        required: "Please enter property size.",
                        maxlength: "Size can't exceed 100 characters."
                    },
                    country: {
                        required: "Please select a country."
                    },
                    city: {
                        required: "Please select a city."
                    },
                    location: {
                        required: "Please select a location."
                    },
                    latitude: {
                        required: "Please enter latitude.",
                        number: "Latitude must be a valid number."
                    },
                    longitude: {
                        required: "Please enter longitude.",
                        number: "Longitude must be a valid number."
                    },
                    property_location: {
                        required: "Please enter the property location.",
                        maxlength: "Location can't exceed 255 characters."
                    },
                    area: {
                        required: "Please enter the area.",
                        maxlength: "Area can't exceed 100 characters."
                    },
                    connectivity: {
                        required: "Please enter connectivity details.",
                        maxlength: "Connectivity can't exceed 100 characters."
                    },
                    emi: {
                        number: "Please enter a valid EMI amount."
                    },
                    description: {
                        maxlength: "Description can be up to 1000 characters long."
                    }
                },
                errorElement: 'div',
                errorClass: 'text-danger mt-1'
            });
        });
    </script>
@endpush
