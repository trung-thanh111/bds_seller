@php
    $lang = $item->languages->first()->pivot;
    $timeUpdate = diff_for_humans($item->updated_at);
    $area = (float) ($item->area ?? 0);

    $unitId = $item->price_unit;
    $unitName = $attributeMap[$unitId] ?? '';
    $displayUnit = $unitName != '' && !in_array($unitName, ['Tổng', '[Chọn đơn vị]']) ? $unitName : '';

    $typeId = $item->transaction_type;
    $typeName = $attributeMap[$typeId] ?? '';

    $showSale = false;
    $showRent = false;
    if ($typeName) {
        $isSale = stripos($typeName, 'Bán') !== false;
        $isRent = stripos($typeName, 'Thuê') !== false;
        if ($isSale && $isRent) {
            $showSale = true;
            $showRent = true;
        } elseif ($isSale) {
            $showSale = true;
        } elseif ($isRent) {
            $showRent = true;
        }
    } else {
        $showSale = $item->price_sale > 0;
        $showRent = $item->price_rent > 0;
    }

    $prices = [];
    if ($showSale && $item->price_sale > 0) {
        $prices[] = ['label' => 'Bán:', 'val' => formatPrice($item->price_sale)];
    }
    if ($showRent && $item->price_rent > 0) {
        $prices[] = ['label' => 'Thuê:', 'val' => formatPrice($item->price_rent) . $displayUnit];
    }
    if (empty($prices)) {
        $prices[] = ['label' => 'Giá:', 'val' => 'Thỏa thuận'];
    }

    $mainPrice = $item->price_sale > 0 ? $item->price_sale : $item->price_rent;
    $pricePerM2 = 0;
    if ($mainPrice > 0 && $area > 0) {
        $pricePerM2 = $mainPrice / $area;
    }

    $album =
        isset($item->album) && is_array($item->album)
            ? $item->album
            : (!empty($item->album)
                ? json_decode($item->album, true)
                : []);
    $albumCount = count($album);

    // Collage Images
    $mainImg = image($item->image);
    $sideImgs = [];
    for ($i = 1; $i <= 4; $i++) {
        if (isset($album[$i])) {
            $sideImgs[] = image($album[$i]);
        }
    }
    while (count($sideImgs) < 4) {
        $sideImgs[] = $mainImg;
    }
@endphp

<div class="gl-property-card-horizontal gl-card-refactor gl-card-stacked">
    <div class="gl-card-collage-container">
        <div class="gl-card-collage-main">
            <a href="{{ url($lang->canonical . '.html') }}">
                <img src="{{ $mainImg }}" alt="{{ $lang->name }}">
            </a>
            @if ($item->is_hot)
                <div class="gl-card-hot-badge" style="top: 10px; left: 10px; background: #e03c31;">
                    <span>VIP KIM CƯƠNG</span>
                </div>
            @endif
        </div>
        <div class="gl-card-collage-side">
            @foreach ($sideImgs as $index => $sImg)
                <div class="gl-card-collage-side-item">
                    <img src="{{ $sImg }}" alt="">
                    @if ($index == 3 && $albumCount > 5)
                        <div class="gl-card-media-count">
                            <i class="fa fa-picture-o"></i>
                            <span>{{ $albumCount }}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Bottom: Content (Full Width) --}}
    <div class="gl-card-body" style="padding: 10px 20px;">
        <h3 class="gl-card-title" style="margin-bottom: 2px; line-height: 1.4;">
            <a href="{{ url($lang->canonical . '.html') }}"
                style="color: #333; font-weight: 700; text-transform: uppercase; font-size: 16px;">
                {{ $lang->name }}
            </a>
        </h3>

        {{-- Horizontal Info Bar --}}
        <div class="gl-card-info-row">
            <span class="gl-card-info-item price">{{ $prices[0]['val'] }}</span>
            <span class="gl-card-info-divider"></span>
            <span class="gl-card-info-item area">{{ $area }} m²</span>

            @if ($pricePerM2 > 0)
                <span class="gl-card-info-divider"></span>
                <span class="gl-card-info-item unit-price">{{ number_format($pricePerM2 / 1000000, 2, ',', '.') }}
                    tr/m²</span>
            @endif

            @if ($item->bedrooms)
                <span class="gl-card-info-divider"></span>
                <span class="gl-card-info-item">
                    {{ $item->bedrooms }} <i class="fa fa-bed"></i>
                </span>
            @endif

            @if ($item->bathrooms)
                <span class="gl-card-info-item">
                    {{ $item->bathrooms }} <i class="fa fa-bath"></i>
                </span>
            @endif

            <span class="gl-card-info-divider"></span>
            <span class="gl-card-info-item">
                {{ $item->province_name ?? '' }}
            </span>
        </div>

        <div class="gl-card-description-horizontal">
            {!! \Illuminate\Support\Str::limit(strip_tags($lang->content ?: $lang->description), 180) !!}
        </div>

        <div class="gl-card-footer uk-flex uk-flex-middle uk-flex-between mt15 pt10"
            style="border-top: 1px dotted #eee;">
            <span class="gl-card-code uk-text-muted" style="font-size: 12px;">
                Mã: {{ $item->code }}
            </span>
            <span class="gl-card-time uk-text-muted" style="font-size: 12px;">
                {{ $timeUpdate }}
            </span>
        </div>
    </div>
</div>
