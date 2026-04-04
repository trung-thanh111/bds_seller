@extends('frontend.homepage.layout')
@section('header-class', 'header-inner')

@section('content')
    <div class="linden-page">
        <!-- Minimalist Header & Breadcrumbs -->
        <section class="hp-detail-header">
            <div class="uk-container uk-container-center">
                <ul class="uk-breadcrumb uk-flex uk-flex-middle">
                    <li><a href="{{ url('/') }}">Trang chủ</a></li>
                    <li class="uk-active"><span>Bài viết</span></li>
                    @if (isset($breadcrumb) && is_array($breadcrumb))
                        @foreach ($breadcrumb as $key => $val)
                            <li><a href="{{ $val['canonical'] }}">{{ $val['name'] }}</a></li>
                        @endforeach
                    @endif
                    @if (isset($postCatalogue) && $postCatalogue)
                        <li class="uk-active"><span>{{ $postCatalogue->languages->first()->pivot->name }}</span></li>
                    @endif
                </ul>
            </div>
        </section>


        <section class="hp-section bg-white">
            <div class="uk-container uk-container-center">
                <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                    <div class="uk-width-large-3-4">
                        <div class="hp-listing-top uk-flex uk-flex-middle uk-flex-space-between uk-margin-large-bottom">
                            <div class="hp-listing-title">
                                <h1 class="hp-category-name">
                                    @if (request('keyword'))
                                        Kết quả tìm kiếm: "{{ request('keyword') }}"
                                    @else
                                        {{ isset($postCatalogue) && $postCatalogue ? $postCatalogue->languages->first()->pivot->name : 'Tin tức & Sự kiện' }}
                                    @endif
                                </h1>
                                <div class="hp-listing-count">
                                    <i class="fa fa-newspaper-o uk-margin-small-right"></i>
                                    {{ $posts->total() }} bài viết
                                </div>
                            </div>
                        </div>

                        @if ($posts->count() > 0)
                            <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                                @foreach ($posts as $post)
                                    @php
                                        // Chỉ bài đầu tiên của trang 1 mới là Featured
                                        $isFeatured = $loop->first && (!request('page') || request('page') == 1);
                                    @endphp
                                    <div class="uk-width-1-1 uk-margin-bottom">
                                        @if ($isFeatured)
                                            @include('frontend.component.post_card_featured', [
                                                'post' => $post,
                                            ])
                                        @else
                                            @include('frontend.component.post_card_horizontal', [
                                                'post' => $post,
                                            ])
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="uk-margin-large-top">
                                {{ $posts->links('frontend.component.pagination') }}
                            </div>
                        @else
                            <div class="uk-alert uk-alert-warning">Đang cập nhật bài viết...</div>
                        @endif
                    </div>

                    <div class="uk-width-large-1-4">
                        @include('frontend.component.sidebar_posts')
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .hp-section {
            padding: 40px 0;
        }

        .hp-category-name {
            font-size: 28px;
            font-weight: 800;
            color: #111;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .hp-listing-count {
            font-size: 14px;
            color: #888;
        }

        /* Sidebar Styling */
        .hp-sidebar-sticky {
            position: sticky;
            top: 100px;
            z-index: 10;
        }

        .hp-sidebar-widget {
            background: #fff;
            border-radius: 5px;
            padding: 24px;
            margin-bottom: 30px;
            border: 1px solid #f5f5f5;
        }

        .hp-sidebar-title {
            font-size: 18px;
            font-weight: 700;
            color: #111;
        }

        .hp-sidebar-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .hp-sidebar-list li {
            margin-bottom: 8px;
        }

        .hp-sidebar-list li a {
            color: #555;
            font-size: 15px;
            transition: all 0.3s;
            display: block;
            text-decoration: none;
        }

        .hp-sidebar-list li.active a,
        .hp-sidebar-list li a:hover {
            color: var(--main-color);
            font-weight: 600;
        }

        /* Custom Search Box */
        .hp-search-container {
            position: relative;
        }

        .hp-search-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            z-index: 5;
        }

        .hp-search-container input {
            background: #f8f8f8 !important;
            border: 1px solid #eee !important;
            border-radius: 5px !important;
            padding: 12px 15px 12px 45px !important;
            font-size: 14px !important;
            height: auto !important;
            width: 100%;
            box-sizing: border-box;
        }

        .hp-search-container input:focus {
            background: #fff !important;
            border-color: var(--main-color) !important;
            outline: none;
        }

        @media (max-width: 959px) {
            .hp-sidebar-sticky {
                position: static;
                margin-top: 40px;
            }
        }
    </style>
@endsection
