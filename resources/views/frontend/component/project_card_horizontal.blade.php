@php
    $lang = $item->languages->first()->pivot;
    $timeUpdate = diff_for_humans($item->updated_at);

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
    for ($i = 0; $i <= 3; $i++) {
        if (isset($album[$i])) {
            $sideImgs[] = image($album[$i + 1]);
        }
    }
    while (count($sideImgs) < 4) {
        $sideImgs[] = $mainImg;
    }

    $displayPrice = isset($item->price) ? formatPrice($item->price) : 'Liên hệ';
@endphp

<div class="gl-property-card-horizontal gl-project-card-horizontal gl-card-refactor gl-card-stacked">
    <div class="gl-card-collage-container">
        <div class="gl-card-collage-main" style="height: 250px;">
            <a href="{{ url($lang->canonical . '.html') }}">
                <img src="{{ image($item->cover_image) }}" alt="{{ $lang->name }}">
            </a>
            <div class="gl-card-badge" style="background: #e8faf0; color: #2ecc71; top: 10px; left: 10px;">
                {{ $item->is_hot == 1 ? 'Đang mở bán' : 'Sắp mở bán' }}
            </div>
        </div>
        <div class="gl-card-collage-side" style="height: 250px;">
            @foreach ($sideImgs as $index => $sImg)
                <div class="gl-card-collage-side-item">
                    <img src="{{ $sImg }}" alt="">
                    @if ($index == 3 && $albumCount > 4)
                        <div class="gl-card-media-count">
                            <i class="fa fa-picture-o"></i>
                            <span>{{ $albumCount }}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="gl-card-body" style="padding: 10px 20px;">
        <h3 class="gl-card-title" style="margin-bottom: 2px; line-height: 1.4;">
            <a href="{{ url($lang->canonical . '.html') }}"
                style="color: #333; font-weight: 700; text-transform: uppercase; font-size: 16px;">
                {{ $lang->name }}
            </a>
        </h3>

        <div class="gl-card-info-row">
            <span class="gl-card-info-item price">{{ $displayPrice }}</span>
            <span class="gl-card-info-divider"></span>
            <span class="gl-card-info-item area">{{ $item->area }} {{ $item->area_unit ?? 'ha' }}</span>

            @if (isset($item->avg_price) && $item->avg_price > 0)
                <span class="gl-card-info-divider"></span>
                <span class="gl-card-info-item unit-price">{{ number_format($item->avg_price, 1, ',', '.') }}
                    tr/m²</span>
            @endif

            <span class="gl-card-info-divider"></span>
            <span class="gl-card-info-item">
                <i class="fa fa-map-marker"></i> {{ $item->province_name ?? '' }}
            </span>
        </div>

        <div class="gl-card-description-horizontal">
            {!! \Illuminate\Support\Str::limit(strip_tags($lang->description ?: $lang->content), 180) !!}
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
