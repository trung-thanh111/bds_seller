<div class="gl-post-card">
    <a href="{{ url($post->canonical . '.html') }}" class="gl-card-img-wrapper">
        <img src="{{ asset($post->image) }}" alt="{{ $post->name }}">
    </a>
    <div class="gl-card-body">
        <h3 class="gl-post-title">
            <a href="{{ url($post->canonical . '.html') }}">
                @if(isset($loop) && $loop->iteration < 10)
                    <span class="gl-post-rank">{{ sprintf('%02d', $loop->iteration) }}</span>
                @elseif(isset($loop))
                    <span class="gl-post-rank">{{ $loop->iteration }}</span>
                @endif
                {{ $post->name }}
            </a>
        </h3>
        <div class="gl-card-footer mt10 pt5" style="border-top: 1px dotted #eee;">
            <span class="gl-card-time uk-text-muted" style="font-size: 12px;">
                {{ diff_for_humans($post->released_at ?? $post->created_at) }}
            </span>
        </div>
    </div>
</div>
