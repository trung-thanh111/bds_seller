@php $menuMain = $menu['main-menu_array'] ?? []; @endphp

<div id="offcanvas-desktop" class="uk-offcanvas" uk-offcanvas="overlay: true">
    <div class="uk-offcanvas-bar hp-offcanvas-bar uk-offcanvas-bar-flip">
        <a class="uk-offcanvas-close hp-offcanvas-close">
            <i class="fa fa-times"></i>
        </a>

        <div class="gl-offcanvas-header">
            <a href="/">
                <img src="{{ $system['homepage_logo'] ?? asset('frontend/resources/img/homely/logo.webp') }}"
                    alt="Logo" style="max-height: 50px;" />
            </a>
            <div
                style="font-size: 11px; margin-top: 5px; color: var(--primary-color); font-weight: 700; text-transform: uppercase;">
                {{ $system['homepage_company'] ?? 'THÔNG TIN THẬT - GIÁ TRỊ THẬT' }}
            </div>
        </div>

        <nav class="gl-offcanvas-menu-nav">
            <ul class="uk-nav uk-nav-offcanvas">
                @foreach ($menuMain as $val)
                    @php
                        $name = $val['item']->languages->first()->pivot->name;
                        $canonical = write_url($val['item']->languages->first()->pivot->canonical, true, true);
                    @endphp
                    <li>
                        <a href="{{ $canonical }}" class="gl-offcanvas-link-new">
                            {{ $name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="gl-footer-text">
            Nhiều dự án quy hoạch, pháp lý an toàn, giá tốt đang chờ bạn.
        </div>

        <div style="text-align: center; padding: 10px; opacity: 0.5; font-size: 10px;">
            {{ $system['copyright'] ?? 'Copyright © 2024 Homedy' }}
        </div>
    </div>
</div>

<div id="offcanvas-mobile" class="uk-offcanvas" uk-offcanvas="overlay: true">
    <div class="uk-offcanvas-bar hp-offcanvas-bar">
        <a class="uk-offcanvas-close hp-offcanvas-close">
            <i class="fa fa-times"></i>
        </a>
        <div class="gl-offcanvas-header">
            <a href="/">
                <img src="{{ $system['homepage_logo'] ?? asset('frontend/resources/img/homely/logo.webp') }}"
                    alt="Logo" style="max-height: 40px;" />
            </a>
        </div>
        <nav class="hp-offcanvas-nav uk-margin-large-bottom">
            <ul class="uk-nav uk-nav-offcanvas" data-uk-nav>
                @foreach ($menuMain as $val)
                    @php
                        $name = $val['item']->languages->first()->pivot->name;
                        $canonical = write_url($val['item']->languages->first()->pivot->canonical, true, true);
                    @endphp
                    <li>
                        <a href="{{ $canonical }}">
                            {{ $name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</div>

<style>
    .hp-offcanvas-bar {
        background: #fff !important;
        width: 320px !important;
        padding: 40px 0 !important;
        box-shadow: -10px 0 50px rgba(0, 0, 0, 0.05) !important;
    }

    .hp-offcanvas-close {
        top: 20px !important;
        right: 20px !important;
        color: #999 !important;
        background: #f8f8f8 !important;
        width: 36px !important;
        height: 36px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: 0.3s !important;
    }

    .hp-offcanvas-close:hover {
        background: #eee !important;
        color: #333 !important;
        transform: rotate(90deg);
    }

    .gl-offcanvas-header {
        padding: 0 40px 30px;
        border-bottom: 1px solid #f5f5f5;
        margin-bottom: 20px;
    }

    .gl-offcanvas-menu-nav {
        padding: 10px 0;
    }

    .uk-nav-offcanvas {
        margin: 0 !important;
    }

    .gl-offcanvas-link-new {
        padding: 15px 40px !important;
        font-size: 16px !important;
        font-weight: 600 !important;
        color: #222 !important;
        display: block !important;
        transition: all 0.3s ease;
        position: relative;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .gl-offcanvas-link-new:hover,
    .gl-offcanvas-link-new.uk-active {
        background: #fcfcfc !important;
        color: var(--main-color) !important;
        padding-left: 50px !important;
        text-decoration: none !important;
    }

    .gl-offcanvas-link-new::after {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 0;
        background: var(--main-color);
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 0 4px 4px 0;
    }

    .gl-offcanvas-link-new:hover::after,
    .gl-offcanvas-link-new.uk-active::after {
        height: 70%;
    }

    .gl-footer-text {
        margin: 40px 40px 20px;
        font-size: 13px;
        color: #999;
        line-height: 1.6;
    }

    .gl-copyright-text {
        padding: 0 40px;
        font-size: 11px;
        color: #ccc;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Mobile adjustments */
    #offcanvas-mobile .hp-offcanvas-bar {
        width: 280px !important;
    }

    #offcanvas-mobile .gl-offcanvas-link-new {
        padding: 12px 30px !important;
        font-size: 14px !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Highlight active link based on current URL
        const currentPath = window.location.pathname;
        const links = document.querySelectorAll('.gl-offcanvas-link-new');
        links.forEach(link => {
            const href = link.getAttribute('href');
            if (currentPath === href || (href !== '/' && currentPath.includes(href))) {
                link.classList.add('uk-active');
            }
        });
    });
</script>
