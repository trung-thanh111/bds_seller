<style>
    .hp-sidebar-filters,
    .hp-sidebar-filters * {
        font-family: 'Roboto', sans-serif !important;
    }

    .hp-mini-item .info .title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        font-weight: 500;
        margin-bottom: 5px;
        line-height: 1.4;
        font-size: 13px;
    }

    .hp-mini-item .info .meta {
        color: var(--main-color);
        font-weight: 600;
        font-size: 13px;
    }

    .hp-location-list {
        margin: 0;
        padding: 0;
    }

    .hp-location-list li {
        border-bottom: 1px dashed #eee;
        padding: 8px 0;
    }

    .hp-location-list li:last-child {
        border-bottom: none;
    }

    .hp-location-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        color: #444;
        transition: all 0.2s;
        text-decoration: none !important;
    }

    .hp-location-link:hover {
        color: var(--main-color);
        padding-left: 5px;
    }

    .hp-location-link .count {
        color: #999;
        font-weight: 500;
        font-size: 13px;
    }
</style>
<div class="hp-sidebar-filters">
    @php
        $isProject = $isProject ?? isset($projectCatalogue) || (isset($projects) && !isset($realEstates));
    @endphp

    @if ($isProject)
        @if (isset($projectCatalogues) && count($projectCatalogues))
            <div class="hp-sidebar-widget">
                <h4 class="hp-sidebar-title">Danh mục dự án</h4>
                <ul class="hp-sidebar-list">
                    @foreach ($projectCatalogues->take(8) as $cat)
                        <li>
                            <a href="{{ url($cat->languages->first()->pivot->canonical . '.html') }}">
                                {{ $cat->languages->first()->pivot->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @else
        @if (isset($realEstateCatalogues) && count($realEstateCatalogues))
            <div class="hp-sidebar-widget">
                <h4 class="hp-sidebar-title">Danh mục BĐS</h4>
                <ul class="hp-sidebar-list">
                    @foreach ($realEstateCatalogues->take(8) as $cat)
                        <li>
                            <a href="{{ url($cat->languages->first()->pivot->canonical . '.html') }}">
                                {{ $cat->languages->first()->pivot->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif

    @if (isset($topProvinces) && count($topProvinces) > 0)
        <div class="hp-sidebar-widget">
            <h4 class="hp-sidebar-title">Bất động sản theo khu vực</h4>
            <div class="hp-sidebar-locations">
                <ul class="uk-list hp-location-list">
                    @foreach ($topProvinces as $loc)
                        <li>
                            <a href="{{ url('mua-ban.html?province_code=' . $loc->province_code) }}" class="hp-location-link">
                                <span class="name">{{ $loc->province_name }}</span>
                                <span class="count">{{ number_format($loc->total_count, 0, ',', '.') }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if (isset($newestRealEstates) && count($newestRealEstates))
        <div class="hp-sidebar-widget">
            <h4 class="hp-sidebar-title">BĐS Mới Nhất</h4>
            <div class="hp-sidebar-projects">
                @foreach ($newestRealEstates->take(8) as $re)
                    @php
                        $reName = $re->languages->first()->pivot->name ?? 'Untitled';
                        $reCanonical = $re->languages->first()->pivot->canonical ?? '#';
                        $rePrice = 0;
                        if ($re->transaction_type == 75) {
                            $rePrice = $re->price_rent;
                        } else {
                            $rePrice = $re->price_sale;
                        }
                    @endphp
                    <a href="{{ url($reCanonical . '.html') }}" class="hp-mini-item uk-flex">
                        <div class="img">
                            <img src="{{ $re->image ? asset($re->image) : asset('frontend/resources/img/no-image.jpg') }}"
                                alt="{{ $reName }}">
                        </div>
                        <div class="info">
                            <h5 class="title">{{ $reName }}</h5>
                            <div class="meta">
                                @if ($rePrice > 0)
                                    @if ($re->transaction_type == 75)
                                        {{ number_format($rePrice / 1000000, 1) }} triệu/tháng
                                    @else
                                        {{ number_format($rePrice / 1000000000, 1) }} tỷ
                                    @endif
                                @else
                                    Liên hệ
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if (isset($featuredProjects) && count($featuredProjects))
        <div class="hp-sidebar-widget">
            <h4 class="hp-sidebar-title">Dự Án Nổi Bật</h4>
            <div class="hp-sidebar-projects">
                @foreach ($featuredProjects->take(8) as $pro)
                    @php
                        $proName = $pro->languages->first()->pivot->name ?? 'Untitled';
                        $proCanonical = $pro->languages->first()->pivot->canonical ?? '#';
                        $proImage = $pro->cover_image ?? ($pro->image ?? '');
                    @endphp
                    <a href="{{ url($proCanonical . '.html') }}" class="hp-mini-item uk-flex">
                        <div class="img">
                            <img src="{{ $proImage ? asset($proImage) : asset('frontend/resources/img/no-image.jpg') }}"
                                alt="{{ $proName }}">
                        </div>
                        <div class="info">
                            <h5 class="title">{{ $proName }}</h5>
                            <div class="meta">Quy mô: {{ $pro->area }} m²</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
