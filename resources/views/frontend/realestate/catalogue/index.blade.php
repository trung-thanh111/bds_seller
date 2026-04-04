@extends('frontend.homepage.layout')

@php
    $currentCatalogue =
        $realEstateCatalogue ?? ($attributeCatalogue ?? ($amenityCatalogue ?? ($attribute ?? $amenity)));
    $realEstates = $realEstates ?? new \Illuminate\Pagination\LengthAwarePaginator([], 0, 8);
    $attributeMap = $attributeMap ?? [];
@endphp

@section('header-class', 'header-inner')
@section('content')
    <style>
    @media (max-width: 767px) {
        .hp-listing-top {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
        .hp-listing-sort {
            margin-top: 15px;
            width: 100%;
        }
        .hp-sort-btn {
            width: 100%;
            justify-content: space-between;
        }
    }
</style>
<div id="scroll-progress"></div>


    <section class="hp-detail-header uk-margin-bottom">
        <div class="uk-container uk-container-center">
            <ul class="uk-breadcrumb uk-flex uk-flex-middle">
                <li><a href="{{ url('/') }}">Trang chủ</a></li>
                <li class="uk-active"><span>{{ $currentCatalogue->name }}</span></li>
            </ul>
        </div>
    </section>

    <section class="hp-catalogue-filter-wrap">
        <div class="uk-container uk-container-center">
            <div class="hp-sticky-filter" data-uk-sticky="{offset: 85, media: 960}">
                @include('frontend.component.filter_horizontal')
            </div>
        </div>
    </section>


    <div class="uk-container uk-container-center">
        <div class="uk-grid uk-grid-medium" data-uk-grid-margin id="main-listing-grid">

            <div class="uk-width-large-7-10">

                <div class="hp-listing-top uk-flex uk-flex-middle uk-flex-space-between uk-margin-bottom">
                    @php
                        $currentSort = request('sort') ?: 'id:desc';
                    @endphp
                    <div class="hp-listing-title">
                        <h1 class="hp-category-name uk-margin-remove">{{ $currentCatalogue->name }}</h1>
                        <div class="hp-listing-count uk-text-muted mt5">
                            <i class="fas fa-home uk-margin-small-right"></i>
                            Có <strong>{{ number_format($realEstates->total(), 0, ',', '.') }}</strong> bất động sản đang
                            giao dịch
                        </div>
                    </div>

                    <div class="hp-listing-sort">
                        <div class="hp-sort-dropdown" data-uk-dropdown="{mode:'click', pos:'bottom-right'}">
                            <button class="hp-sort-btn uk-flex uk-flex-middle">
                                <span id="sort-label">{{ $sorts[$currentSort] ?? 'Mặc định' }}</span>
                                <i class="fas fa-chevron-down uk-margin-small-left"></i>
                            </button>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="hp-sort-list">
                                    @foreach ($sorts as $key => $label)
                                        <li class="{{ $currentSort == $key ? 'uk-active' : '' }}">
                                            <a href="#" class="ajax-sort"
                                                data-sort="{{ $key }}">{{ $label }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>


                <hr class="uk-margin-medium-bottom">

                <div id="ajax-listing-container">
                    @include('frontend.realestate.catalogue.listing_results', [
                        'realEstates' => $realEstates,
                        'attributeMap' => $attributeMap,
                    ])
                </div>
            </div>


            <div class="uk-width-large-3-10">
                <aside class="hp-sidebar hp-sidebar-sticky">
                    @include('frontend.component.sidebar_filters')

                    @if (isset($widgets['featured-products']))
                        <div class="hp-sidebar-widget uk-margin-top">
                            <h4 class="hp-sidebar-title">BĐS tiêu biểu</h4>
                            <div class="hp-sidebar-projects">
                                @foreach ($widgets['featured-products']->items as $p)
                                    <a href="{{ url($p->canonical . '.html') }}" class="hp-mini-item uk-flex">
                                        <div class="img">
                                            <img src="{{ image($p->image) }}" alt="{{ $p->name }}">
                                        </div>
                                        <div class="info">
                                            <h5 class="title">{{ $p->name }}</h5>
                                            <div class="meta">{{ number_format($p->price, 0, ',', '.') }}
                                                {{ $p->price_unit ?? '' }}</div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </div>
@endsection
