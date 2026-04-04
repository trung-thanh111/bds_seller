@php
    $lang = $item->languages->first()->pivot;
    $timeUpdate = diff_for_humans($item->updated_at);
    $displayAddress = format_address($item);
    $mapUrl =
        extract_map_url($item->iframe_map) ?:
        'https://www.google.com/maps/search/?api=1&query=' . urlencode($displayAddress);

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
            <img src="{{ image($item->cover_image) }}" alt="{{ $lang->name }}">
        </a>

        <div class="gl-card-badge">
            {{ $item->publish == 2 ? 'Đang mở bán' : 'Sắp mở bán' }}
        </div>

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

        <div class="gl-card-area-info mt5">
            <span style="font-size: 16px; font-weight: 600; color: #333;">
                {{ $item->area }} {{ $item->area_unit ?? 'ha' }}
            </span>
        </div>

        <div class="gl-card-location">
            <i class="fa fa-map-marker"></i>
            <span>{{ $item->ward_name ?? '' }}, {{ $item->province_name ?? '' }}</span>
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
