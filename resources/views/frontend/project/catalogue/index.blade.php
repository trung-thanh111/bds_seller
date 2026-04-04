@extends('frontend.homepage.layout')

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
                @if (isset($breadcrumb) && is_array($breadcrumb))
                    @foreach ($breadcrumb as $key => $val)
                        @if ($key == count($breadcrumb) - 1)
                            <li class="uk-active"><span>{{ $val['name'] }}</span></li>
                        @else
                            <li><a href="{{ $val['canonical'] }}">{{ $val['name'] }}</a></li>
                        @endif
                    @endforeach
                @else
                    <li><a href="{{ url('/') }}">Trang chủ</a></li>
                    <li class="uk-active"><span>Dự án</span></li>
                @endif
            </ul>
        </div>
    </section>


    <section class="hp-catalogue-filter-wrap uk-margin-bottom">
        <div class="uk-container uk-container-center">
            <div class="hp-sticky-filter" data-uk-sticky="{offset: 85, media: 960}">
                @include('frontend.component.filter_horizontal_project')
            </div>
        </div>
    </section>


    <div class="uk-container uk-container-center">
        <div class="uk-grid uk-grid-medium" data-uk-grid-margin id="main-listing-grid">

            <div class="uk-width-large-7-10">

                <div class="hp-listing-top uk-flex uk-flex-middle uk-flex-space-between uk-margin-bottom">
                    <div class="hp-listing-title">
                        <h1 class="hp-category-name uk-margin-remove">
                            {{ isset($projectCatalogue) ? $projectCatalogue->name : 'Tất cả dự án' }}
                        </h1>
                        <div class="hp-listing-count uk-text-muted mt5">
                            <i class="fas fa-city uk-margin-small-right"></i>
                            Có <strong id="total-records">{{ number_format($projects->total(), 0, ',', '.') }}</strong> dự án đang được giới
                            thiệu
                        </div>
                    </div>
                    @php
                        $currentSort = request('sort') ?: 'id:desc';
                    @endphp
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
                    @include('frontend.component.project_list', ['projects' => $projects])
                </div>
            </div>


            <div class="uk-width-large-3-10">
                <aside class="hp-sidebar hp-sidebar-sticky">
                    @include('frontend.component.sidebar_filters')

                    @if (isset($widgets['product-category']))
                        <div class="hp-sidebar-widget uk-margin-remove-top">
                            <h4 class="hp-sidebar-title">Loại hình sản phẩm</h4>
                            <ul class="hp-sidebar-list">
                                @foreach ($widgets['product-category']->items as $cat)
                                    <li><a href="{{ url($cat->canonical . '.html') }}">{{ $cat->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </div>
@include('frontend.component.filter_modal_project')
@endsection
