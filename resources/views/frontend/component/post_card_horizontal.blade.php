@php
    $lang = $post->languages->first()->pivot;
    $time = isset($post->released_at)
        ? date('d/m/Y H:i', strtotime($post->released_at))
        : date('d/m/Y H:i', strtotime($post->created_at));
    $cat = $post->post_catalogues->first();
    $catName = $cat ? $cat->languages->first()->pivot->name : 'Tin tức';
    $authorName = $post->user ? $post->user->name : 'Lan Chi'; // Mặc định Lan Chi nếu chưa có user
@endphp

<div class="gl-post-card-horizontal-v2">
    <a href="{{ url($post->canonical . '.html') }}" class="gl-card-img-wrapper">
        <img src="{{ image($post->image) }}" alt="{{ $post->name }}">
        <span class="gl-post-badge">{{ $catName }}</span>
    </a>
    <div class="gl-card-body">
        <div class="gl-card-meta">
            <span>{{ $time }}</span>
        </div>
        <h3 class="gl-post-card-title">
            <a href="{{ url($post->canonical . '.html') }}">
                {{ $post->name }}
            </a>
        </h3>
        <div class="gl-card-description-horizontal">
            {!! strip_tags($lang->description ?: $lang->content) !!}
        </div>
    </div>
</div>
