{{-- @if ($agent)
    <div class="gl-agent-card">
        <div class="gl-agent-profile">
            <div class="agent-avatar">
                <img src="{{ image($agent->avatar) }}" alt="{{ $agent->name }}">
                <span class="agent-online-badge"></span>
            </div>
            <div class="agent-info">
                <h4 class="agent-name">{{ $agent->full_name }}</h4>
                <div class="agent-subtitle">Môi giới chuyên nghiệp</div>
            </div>
        </div>

        <div class="agent-stats uk-grid uk-grid-collapse uk-text-center">
            <div class="uk-width-1-2">
                <div class="stat-val">24</div>
                <div class="stat-label">BĐS đăng</div>
            </div>
            <div class="uk-width-1-2">
                <div class="stat-val">5.0</div>
                <div class="stat-label">Đánh giá</div>
            </div>
        </div>

        <div class="agent-actions">
            @php
                $zaloNum = !empty($agent->zalo) ? $agent->zalo : (!empty($agent->phone) ? $agent->phone : '');
                $phoneNum = !empty($agent->phone) ? $agent->phone : (!empty($agent->zalo) ? $agent->zalo : '');
                
                $cleanZalo = preg_replace('/\D/', '', $zaloNum);
                $cleanPhone = preg_replace('/\D/', '', $phoneNum);
            @endphp
            <div class="uk-grid uk-grid-small" data-uk-grid-margin>
                <div class="uk-width-1-2">
                    <a href="tel:{{ $cleanPhone }}" class="gl-btn-agent btn-phone">
                        <i class="fa fa-phone"></i> Gọi ngay
                    </a>
                </div>
                <div class="uk-width-1-2">
                    <a href="https://zalo.me/{{ $cleanZalo }}" target="_blank" class="gl-btn-agent btn-zalo">
                        <i class="fa fa-comment-o"></i> Zalo chat
                    </a>
                </div>
                <div class="uk-width-1-1">
                    <a href="{{ route('contact.index') }}" class="gl-btn-agent btn-consult">
                        <i class="fa fa-envelope-o"></i> Yêu cầu tư vấn
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif --}}
