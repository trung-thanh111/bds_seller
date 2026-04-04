@php
    $lang = $post->languages->first()->pivot;
    $time = isset($post->released_at) ? date('d/m/Y H:i', strtotime($post->released_at)) : date('d/m/Y H:i', strtotime($post->created_at));
    $cat = $post->post_catalogues->first();
    $catName = $cat ? $cat->languages->first()->pivot->name : 'Tin tức';
@endphp

<div class="gl-post-card-featured">
    <a href="{{ url($post->canonical . '.html') }}" class="gl-card-img-wrapper">
        <img src="{{ image($post->image) }}" alt="{{ $post->name }}">
        <span class="gl-post-badge">{{ $catName }}</span>
    </a>
    <div class="gl-card-overlay">
        <div class="gl-card-meta">
            <span>{{ $time }}</span>
            <span class="separator">•</span>
            <span>{{ $catName }}</span>
        </div>
        <h3 class="gl-card-title">
            <a href="{{ url($post->canonical . '.html') }}" class="gl-line-clamp-3">
                {{ $post->name }}
            </a>
        </h3>
        <div class="gl-card-description-horizontal gl-line-clamp-3">
            {!! strip_tags($lang->description ?: $lang->content) !!}
        </div>
    </div>
</div>
