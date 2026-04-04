@php
    $lang = $item->languages->first()->pivot;
    $timeUpdate = diff_for_humans($item->updated_at);
    $area = $item->area ?? 0;

    $displayAddress = format_address($item);
    $mapUrl =
        extract_map_url($item->iframe_map) ?:
        'https://www.google.com/maps/search/?api=1&query=' . urlencode($displayAddress);

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
        $label = stripos($typeName, 'Bán') !== false && !$showRent ? $typeName . ':' : 'Bán:';
        $prices[] = ['label' => $label, 'val' => formatPrice($item->price_sale)];
    }
    if ($showRent && $item->price_rent > 0) {
        $label = stripos($typeName, 'Thuê') !== false && !$showSale ? $typeName . ':' : 'Thuê:';
        $prices[] = ['label' => $label, 'val' => formatPrice($item->price_rent) . $displayUnit];
    }
    if (empty($prices)) {
        $prices[] = ['label' => 'Giá:', 'val' => 'Thỏa thuận'];
    }

    $mainPrice = $item->price_sale > 0 ? $item->price_sale : $item->price_rent;
    $pricePerM2 = $mainPrice > 0 && $area > 0 ? $mainPrice / $area : 0;

    $album =
        isset($item->album) && is_array($item->album)
            ? $item->album
            : (!empty($item->album)
                ? json_decode($item->album, true)
                : []);
    $albumCount = count($album);
@endphp

<div class="gl-property-card">
    <div class="gl-card-img-wrapper">
        <a href="{{ url($lang->canonical . '.html') }}">
            <img src="{{ image($item->image) }}" alt="{{ $lang->name }}">
        </a>
        @if ($item->is_hot)
            <div class="gl-card-hot-badge"><span>HOT</span></div>
        @endif

        @if ($albumCount > 0)
            <div class="gl-card-photo-count">
                <i class="fa fa-camera"></i> {{ $albumCount }}
            </div>
        @endif
    </div>

    <div class="gl-card-body">
        <h3 class="gl-card-title">
            <a href="{{ url($lang->canonical . '.html') }}">{{ $lang->name }}</a>
        </h3>

        <div class="gl-card-price-area-row">
            <span class="gl-card-price-val">
                @foreach ($prices as $index => $p)
                    {{ $p['val'] }}@if ($index < count($prices) - 1)
                        -
                    @endif
                @endforeach
            </span>
            @if ($area > 0)
                <span class="gl-card-area">{{ $area }} m²</span>
            @endif
        </div>

        <div class="gl-card-location">
            <i class="fa fa-map-marker"></i>
            <span>{{ $item->ward_name ?? '' }}, {{ $item->province_name ?? '' }}</span>
        </div>

        <div class="gl-card-footer uk-flex uk-flex-middle uk-flex-between pt10" style="border-top: 1px dotted #eee;">
            <span class="gl-card-code uk-text-muted" style="font-size: 12px;">
                Mã: {{ $item->code }}
            </span>
            <span class="gl-card-time uk-text-muted" style="font-size: 12px;">
                {{ $timeUpdate }}
            </span>
        </div>
    </div>
</div>
