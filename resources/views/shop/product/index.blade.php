@extends('layouts.app')
@section('header-title', __('Product List'))

@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3 pb-3">
        <h4 class="mb-0">
            {{ __('Product List') }}
        </h4>
        @hasPermission('shop.product.create')
            <a href="{{ route('shop.product.create') }}" class="btn py-2 btn-primary">
                <i class="fa fa-plus-circle"></i>
                {{ __('Add Product') }}
            </a>
        @endhasPermission
    </div>

    <div class="container-fluid">
        <!-- Flash Deal Alert -->
        @if ($flashSale)
            <div>
                <div class="alert flash-deal-alert d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex flex-column">
                        <div class="deal-text">{{ $flashSale->name }}</div>
                        <div class="deal-title">{{ __('Coming Soon') }}</div>
                    </div>
                    <div class="countdown d-flex align-items-center">
                        <!-- Days -->
                        <div class="countdown-section">
                            <div class="countdown-label">{{ __('Days') }}</div>
                            <div id="days" class="countdown-time">00</div>
                        </div>
                        <!-- Hours -->
                        <div class="countdown-section">
                            <div class="countdown-label">{{ __('Hours') }}</div>
                            <div id="hours" class="countdown-time">00</div>
                        </div>
                        <!-- Minutes -->
                        <div class="countdown-section">
                            <div class="countdown-label">{{ __('Minutes') }}</div>
                            <div id="minutes" class="countdown-time">00</div>
                        </div>
                        <!-- Seconds -->
                        <div class="countdown-section">
                            <div class="countdown-label">{{ __('Seconds') }}</div>
                            <div id="seconds" class="countdown-time">00</div>
                        </div>
                    </div>
                    @hasPermission('shop.flashSale.show')
                        <a href="{{ route('shop.flashSale.show', $flashSale->id) }}" class="btn btn-primary py-2.5 addBtn">
                            Add Product
                        </a>
                    @endhasPermission
                </div>
            </div>
        @endif
        <!-- End Flash Deal Alert -->

        <!-- Filter Product Modal -->
        <form action="" method="GET">
            <div class="modal fade" id="filterProductModal" tabindex="-1" aria-labelledby="filterProductModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="filterProductModalLabel">{{ __('Filter Products') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <x-select label="Category" name="category" placeholder="Select Category">
                                    <option value="">
                                        {{ __('Select Category') }}
                                    </option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div class="mt-3">
                                <x-select label="Brand" name="brand" placeholder="All Brand">
                                    <option value="">
                                        {{ __('All Brand') }}
                                    </option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div class="mt-3">
                                <x-select label="Color" name="color" placeholder="All Color">
                                    <option value="">
                                        {{ __('All Color') }}
                                    </option>
                                    @foreach ($colors as $color)
                                        <option value="{{ $color->id }}"
                                            {{ request('color') == $color->id ? 'selected' : '' }}>
                                            {{ $color->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                        </div>
                        <div class=" modal-footer d-flex justify-content-between flex-wrap gap-2">
                            <a href="{{ route('shop.product.index') }}" class="btn btn-light py-2 px-4">
                                {{ __('Reset') }}
                            </a>

                            <button type="submit" class="btn btn-primary">
                                {{ __('Apply Filters') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- End Filter Product Modal -->

        <div class="mb-3 card">
            <div class="card-body">
                <form action="" class="d-flex align-items-center justify-content-end gap-3 mb-3 border-bottom pb-3 flex-wrap">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#filterProductModal">
                        {{ __('Filter') }}
                    </button>

                    <div class="input-group" style="max-width: 400px">
                        <input type="text" name="search" class="form-control"
                            placeholder="{{ __('Search by product name') }}" value="{{ request('search') }}">
                        <button type="submit" class="input-group-text btn btn-primary">
                            <i class="fa fa-search"></i> {{ __('Search') }}
                        </button>
                    </div>
                </form>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-4 g-4">
                    @forelse($products as $key => $product)
                        <div class="col">
                            <div class="card shadow-sm d-flex flex-column h-100">
                                <img src="{{ $product->thumbnail }}" alt="" class="card-img-top" loading="lazy"
                                    width="100%" style="height: 200px; object-fit: cover" />
                                <div class="card-body h-100">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                                            data-bs-title="{{ __('Update product status') }}">
                                            <a href="{{ route('shop.product.toggle', $product->id) }}">
                                                <input type="checkbox" {{ $product->is_active ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </a>
                                        </label>
                                        <div class="d-flex gap-2">
                                            @hasPermission('shop.product.show')
                                                <a href="{{ route('shop.product.show', $product->id) }}"
                                                    class="svg-bg btn-outline-primary circleIcon btn-sm"
                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                    data-bs-title="{{ __('View Product') }}">
                                                    <img src="{{ asset('assets/icons-admin/eye.svg') }}" alt="icon"
                                                        loading="lazy" />
                                                </a>
                                            @endhasPermission
                                            @hasPermission('shop.product.barcode')
                                                <a href="{{ route('shop.product.barcode', $product->id) }}"
                                                    class="btn-outline-info circleIcon btn-sm" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    data-bs-title="{{ __('Generate Barcode for this product') }}">
                                                    <i class="bi bi-upc-scan"></i>
                                                </a>
                                            @endhasPermission
                                            @hasPermission('shop.product.edit')
                                                <a href="{{ route('shop.product.edit', $product->id) }}"
                                                    class="btn-outline-info circleIcon btn-sm" data-bs-toggle="tooltip"
                                                    data-bs-placement="left" data-bs-title="{{ __('Edit Product') }}">
                                                    <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="icon"
                                                        loading="lazy" />
                                                </a>
                                            @endhasPermission
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <h5 class="card-title">{{ Str::limit($product->name, 50, '...') }}</h5>

                                        <p>
                                            {{ $product->short_description }}
                                        </p>

                                        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mt-3">
                                            <p class="card-text m-0 d-flex align-items-center gap-1">
                                                <strong>{{ showCurrency($product->price) }}</strong>
                                                @if ($product->discount_price)
                                                    <span class="badge bg-primary rounded-pill">
                                                        {{ showCurrency($product->discount_price) }}
                                                    </span>
                                                @endif
                                            </p>

                                            <div class="d-flex align-items-center gap-1">
                                                <i class="fa fa-star text-warning"></i>
                                                ({{ number_format($product->reviews->avg('rating'), 1) }})
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-body text-center">
                                    {{ __('No Data Found') }}
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="my-3">
            {{ $products->links() }}
        </div>

    </div>
@endsection
@push('scripts')
    <script>
        $(".confirmApprove").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            Swal.fire({
                title: "Are you sure?",
                text: "You want to approve this product",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Approve it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    </script>

    @if ($flashSale)
        <script>
            // Set the start and end date/time
            var startDateAndTime = "{{ $flashSale->start_date }}T{{ $flashSale->start_time }}";
            var endDateAndTime = "{{ $flashSale->end_date }}T{{ $flashSale->end_time }}";
            let startDate = new Date(startDateAndTime).getTime();
            let endDate = new Date(endDateAndTime).getTime();

            // Update the countdown every 1 second
            let countdownInterval = setInterval(() => {
                let now = new Date().getTime();

                // If current time is before the start date, show "Deal Coming" message
                if (now < startDate) {
                    let distanceToStart = startDate - now;

                    // Time calculations for days, hours, minutes, and seconds
                    let days = Math.floor(distanceToStart / (1000 * 60 * 60 * 24));
                    let hours = Math.floor((distanceToStart % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    let minutes = Math.floor((distanceToStart % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((distanceToStart % (1000 * 60)) / 1000);

                    // Display the countdown with a "Deal Coming" message
                    document.getElementById("days").innerHTML = String(days).padStart(2, '0');
                    document.getElementById("hours").innerHTML = String(hours).padStart(2, '0');
                    document.getElementById("minutes").innerHTML = String(minutes).padStart(2, '0');
                    document.getElementById("seconds").innerHTML = String(seconds).padStart(2, '0');
                    return;
                }

                // Once the current time is after the start date and before the end date, show the active countdown
                let distance = endDate - now;

                // If the deal has ended, stop the countdown and show the message
                if (distance < 0) {
                    clearInterval(countdownInterval);
                    document.getElementById("days").innerHTML = "00";
                    document.getElementById("hours").innerHTML = "00";
                    document.getElementById("minutes").innerHTML = "00";
                    document.getElementById("seconds").innerHTML = "00";
                    document.querySelector(".deal-text").innerHTML = "Deal Ended!";
                    return;
                }

                // Time calculations for days, hours, minutes, and seconds
                let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Display the result
                document.getElementById("days").innerHTML = String(days).padStart(2, '0');
                document.getElementById("hours").innerHTML = String(hours).padStart(2, '0');
                document.getElementById("minutes").innerHTML = String(minutes).padStart(2, '0');
                document.getElementById("seconds").innerHTML = String(seconds).padStart(2, '0');
            }, 1000);
        </script>
    @endif
@endpush
@push('css')
    <style>
        /* Flash Deal Alert Styles */
        .flash-deal-alert {
            background: url("{{ asset('assets/images/flash-sale.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 8px;
            color: white;
            border-radius: 8px;
            padding: 16px 32px;
        }

        .deal-title,
        .deal-text {
            font-size: 24px;
            font-weight: 600;
            color: white;
            margin: 0;
            line-height: 32px;
        }

        /* Countdown Timer Styles */
        .countdown {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .countdown-section {
            text-align: center;
            padding: 4px 8px;
            border-radius: 8px;
            background-color: white;
            min-width: 68px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .countdown-label {
            font-size: 12px;
            color: #000;
        }

        .countdown-time {
            font-size: 20px;
            font-weight: bold;
            color: var(--theme-color);
        }

        .addBtn {
            border-radius: 25px;
            padding: 10px 20px;
        }
    </style>
@endpush
