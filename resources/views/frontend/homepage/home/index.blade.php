@extends('frontend.homepage.layout')
@section('content')
    @php
        $menuMain = $menu['main-menu_array'] ?? [];
        $menuMapping = [
            'Mua bán' => ['icon' => 'fa-home', 'bg' => '#fff8e1', 'color' => '#ff8f00'],
            'Cho thuê' => ['icon' => 'fa-key', 'bg' => '#e8f5e9', 'color' => '#2e7d32'],
            'Dự án' => ['icon' => 'fa-building', 'bg' => '#fff3e0', 'color' => '#ef6c00'],
            'Liên hệ' => ['icon' => 'fa-edit', 'bg' => '#f3e5f5', 'color' => '#7b1fa2'],
        ];
        $defaultMapping = ['icon' => 'fa-folder-open', 'bg' => '#f5f5f5', 'color' => '#666'];
    @endphp


    <style>
        @media (max-width: 959px) {
            .gl-hero-filter-container {
                width: 95% !important;
                bottom: 10% !important;
            }

            .gl-hero-filter-box {
                padding: 15px !important;
            }

            .gl-search-bar-hero {
                flex-direction: column !important;
                height: auto !important;
                background: transparent !important;
                box-shadow: none !important;
                gap: 10px !important;
            }

            .gl-search-bar-hero .gl-search-trigger {
                width: 100% !important;
                border-right: none !important;
                border-radius: 8px !important;
                background: #fff !important;
                height: 45px !important;
                padding: 0 15px !important;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .gl-hero-search-wrapper {
                position: relative;
                width: 100%;
                display: flex;
            }

            .gl-search-bar-hero .gl-search-input {
                width: 100% !important;
                border-radius: 8px !important;
                height: 45px !important;
                padding: 0 50px 0 15px !important;
            }

            .gl-search-bar-hero .gl-search-btn {
                position: absolute !important;
                right: 0 !important;
                top: 0 !important;
                height: 45px !important;
                width: 45px !important;
                border-radius: 0 8px 8px 0 !important;
            }

            /* Fix the overlap in screenshot */
            #label-location-hero {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 150px;
            }

            .gl-hero-tabs {
                gap: 5px !important;
            }

            .gl-hero-tab-item {
                padding: 8px 15px !important;
                font-size: 14px !important;
            }

            .gl-location-card {
                margin-bottom: 10px !important;
            }
        }

        /* Desktop fix for search wrapper */
        @media (min-width: 960px) {
            .gl-hero-search-wrapper {
                flex: 1;
                display: flex;
                position: relative;
            }

            .gl-search-bar-hero .gl-hero-search-input {
                flex: 1;
                border: none !important;
                outline: none !important;
                padding-right: 60px !important;
            }

            .gl-search-bar-hero .gl-search-btn {
                position: absolute !important;
                right: -8px !important;
                top: -8px !important;
                background: var(--main-color);
                color: #fff;
                width: 35px;
                height: 35px;
                border-radius: 5px;
                font-size: 16px;
                transition: transform 0.2s;
            }
        }
    </style>
    <section class="gl-hero-section">
        <div class="gl-hero-overlay"></div>
        <div class="gl-hero-filter-container">
            <div class="gl-hero-tabs-wrapper">
                <ul class="gl-hero-tabs">
                    <li class="gl-hero-tab-item active" data-tab="sale">Mua bán</li>
                    <li class="gl-hero-tab-item" data-tab="rent">Cho thuê</li>
                    <li class="gl-hero-tab-item" data-tab="project">Dự án</li>
                </ul>
            </div>

            <div class="gl-hero-filter-box">
                <!-- Row 1: Search & Location (Styled like Header) -->
                <div class="gl-search-bar gl-search-bar-hero">
                    <button class="gl-search-trigger" data-uk-modal="{target:'#modal-location'}">
                        <i class="fa fa-map-marker-alt"></i>
                        <span id="label-location-hero">
                            @php
                                $pCode = request('province_code', request('old_province_code', 0));
                                $pName = 'Toàn quốc';
                                if ($pCode != 0) {
                                    if (isset($provinces) && isset($provinces[$pCode])) {
                                        $pName = $provinces[$pCode];
                                    } elseif (isset($old_provinces) && isset($old_provinces[$pCode])) {
                                        $pName = $old_provinces[$pCode];
                                    }
                                }
                            @endphp
                            {{ $pName }}
                        </span>
                        <i class="fa fa-chevron-down"></i>
                    </button>
                    <div class="gl-hero-search-wrapper">
                        <input type="text" class="gl-search-input gl-hero-search-input"
                            placeholder="Tìm kiếm địa điểm, khu vực, tên đường...">
                        <button class="gl-search-btn btn-hero-submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>

                <!-- Row 2: Detailed Filters -->
                <div class="gl-hero-bottom-row">
                    <div class="gl-hero-pane active" id="pane-sale">
                        @include('frontend.component.filter_horizontal', [
                            'transactionType' => '74',
                            'showReset' => true,
                        ])
                    </div>
                    <div class="gl-hero-pane" id="pane-rent" style="display:none">
                        @include('frontend.component.filter_horizontal', [
                            'transactionType' => '75',
                            'showReset' => true,
                        ])
                    </div>
                    <div class="gl-hero-pane" id="pane-project" style="display:none">
                        @include('frontend.component.filter_horizontal_project', [
                            'showReset' => true,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.gl-hero-tab-item');
            const panes = document.querySelectorAll('.gl-hero-pane');
            const searchInput = document.querySelector('.gl-hero-search-input-main');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const target = this.getAttribute('data-tab');

                    // Update Tab Active
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // Update Pane Active
                    panes.forEach(pane => {
                        pane.style.display = 'none';
                        pane.classList.remove('active');
                    });

                    const activePane = document.getElementById(`pane-${target}`);
                    if (activePane) {
                        activePane.style.display = 'block';
                        activePane.classList.add('active');
                    }

                    // Update Search Placeholder
                    if (target === 'project') {
                        searchInput.placeholder = 'Tìm kiếm dự án, chủ đầu tư, khu vực...';
                    } else {
                        searchInput.placeholder = 'Tìm kiếm địa điểm, khu vực, tên đường...';
                    }
                });
            });

            // Redirection logic
            function getSearchUrl() {
                const activePane = document.querySelector('.gl-hero-pane.active');
                const activeTab = document.querySelector('.gl-hero-tab-item.active').getAttribute('data-tab');
                const keyword = searchInput.value;

                let baseUrl = '/mua-ban.html';
                if (activeTab === 'rent') baseUrl = '/cho-thue.html';
                if (activeTab === 'project') baseUrl = '/du-an.html';

                const params = new URLSearchParams();
                if (keyword) params.append('keyword', keyword);

                if (activePane) {
                    const inputs = activePane.querySelectorAll('.bar-sync-input');
                    inputs.forEach(input => {
                        const name = input.getAttribute('data-name') || input.name;
                        if (!name) return;

                        if (input.type === 'checkbox' || input.type === 'radio') {
                            if (input.checked && input.value !== '') params.append(name, input.value);
                        } else if (input.value && input.value !== '') {
                            params.append(name, input.value);
                        }
                    });
                }

                // Location Modal (header integration)
                const isAfter = $('#switch-location-mode').is(':checked');
                if (isAfter) {
                    const p = $('select[name=province_code]').val();
                    const d = $('select[name=district_code]').val();
                    const w = $('select[name=ward_code]').val();
                    if (p && p != '0') params.append('province_code', p);
                    if (d && d != '0') params.append('district_code', d);
                    if (w && w != '0') params.append('ward_code', w);
                } else {
                    const p = $('select[name=old_province_code]').val();
                    const d = $('select[name=old_district_code]').val();
                    const w = $('select[name=old_ward_code]').val();
                    if (p && p != '0') params.append('old_province_code', p);
                    if (d && d != '0') params.append('old_district_code', d);
                    if (w && w != '0') params.append('old_ward_code', w);
                }

                return `${baseUrl}?${params.toString()}`;
            }

            // Global Submit
            $('.btn-hero-submit').on('click', function(e) {
                e.preventDefault();
                window.location.href = getSearchUrl();
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    window.location.href = getSearchUrl();
                }
            });

            $(document).on('click', '.hp-btn-apply-bar', function(e) {
                e.preventDefault();
                window.location.href = getSearchUrl();
            });

            // Synchronize Location Label from Modal
            $(document).on('click', '#btn-apply-location', function() {
                setTimeout(() => {
                    let label = 'Toàn quốc';
                    const modal = $('#modal-location');
                    const isAfter = $('#switch-location-mode').is(':checked');
                    let pCode = '0';

                    if (isAfter) {
                        pCode = modal.find('select[name=province_code]').val();
                        label = modal.find('select[name=province_code] option:selected').text();
                        if (pCode === '0') label = 'Toàn quốc';

                        // Sync to all other province inputs on the page (excluding the modal itself)
                        const others = $('select[name=province_code]').not(modal.find('select'));
                        others.val(pCode).trigger('change');
                    } else {
                        pCode = modal.find('select[name=old_province_code]').val();
                        label = modal.find('select[name=old_province_code] option:selected').text();
                        if (pCode === '0') label = 'Toàn quốc';

                        const others = $('select[name=old_province_code]').not(modal.find(
                            'select'));
                        others.val(pCode).trigger('change');
                    }

                    $('#label-location-hero').text(label);
                    // Also sync labels in components if they exist (though trigger('change') might already handle it)
                    $('#bar-selected-location, #bar-selected-location-project').text(label);
                }, 100);
            });
        });
    </script>



    @php
        $recommendedRealEstates = collect();
        if (isset($homepageCatalogues)) {
            foreach ($homepageCatalogues as $cat) {
                if (isset($cat->real_estates)) {
                    $recommendedRealEstates = $recommendedRealEstates->merge($cat->real_estates);
                }
            }
        }
        $recommendedRealEstates = $recommendedRealEstates->unique('id')->values();
    @endphp

    @if ($recommendedRealEstates->count() > 0)
        <div class="gl-section gl-recommended-section ">
            <div class="uk-container uk-container-center">
                <h2 class="gl-section-title uk-margin-small-bottom">Bất động sản dành cho bạn</h2>
                <div class="uk-flex uk-flex-middle uk-flex-space-between uk-margin-bottom">
                    <ul class="gl-section-subnav uk-subnav uk-subnav-line uk-visible-large uk-margin-remove">
                        <li><a href="/mua-ban.html">Mua bán</a></li>
                        <li><a href="/cho-thue.html">Cho thuê</a></li>
                        <li><a href="/du-an.html">Dự án</a></li>
                    </ul>
                    <a href="/mua-ban.html" class="gl-view-more">Xem thêm <i class="fa fa-angle-right"></i></a>
                </div>

                <div class="gl-grid-wrapper">
                    <div class="uk-grid uk-grid-medium" data-uk-grid-margin style="margin-left: -20px;">
                        @foreach ($recommendedRealEstates->take(8) as $item)
                            <div class="uk-width-large-1-4 uk-width-medium-1-2 mb20" style="padding-left: 20px;">
                                @include('frontend.component.real_estate_card', [
                                    'item' => $item,
                                    'attributeMap' => $attributeMap,
                                ])
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="gl-btn-view-more-container uk-margin-top">
                    <a href="/mua-ban.html" class="gl-btn-view-more">
                        Xem thêm bất động sản <i class="fa fa-angle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if (isset($topProvinces) && count($topProvinces) > 0)
        @php
            $provinceImages = [
                'ho_chi_minh' =>
                    'https://images.unsplash.com/photo-1583417319070-4a69db38a482?auto=format&fit=crop&w=1000&q=80',
                'ha_noi' =>
                    'https://images.unsplash.com/photo-1509030450996-dd1a26dda07a?auto=format&fit=crop&w=1000&q=80',
                'da_nang' =>
                    'https://images.unsplash.com/photo-1559592442-7e18259f698b?auto=format&fit=crop&w=1000&q=80',
                'binh_duong' =>
                    'https://images.unsplash.com/photo-1596422846543-75c6fc197f07?auto=format&fit=crop&w=1000&q=80',
                'khanh_hoa' =>
                    'https://images.unsplash.com/photo-1623916523922-38d58006aad3?auto=format&fit=crop&w=1000&q=80',
                'hung_yen' =>
                    'https://images.unsplash.com/photo-1549488344-1f9b8d2bd1f3?auto=format&fit=crop&w=1000&q=80',
                'vung_tau' =>
                    'https://images.unsplash.com/photo-1583417319070-4a69db38a482?auto=format&fit=crop&w=1000&q=80',
            ];
        @endphp
        <div class="gl-section gl-location-section ">
            <div class="uk-container uk-container-center">
                <h2 class="gl-section-title uk-margin-bottom">Bất động sản theo địa điểm</h2>
                <div class="uk-grid" data-uk-grid-margin>
                    @php $first = $topProvinces[0]; @endphp
                    <div class="uk-width-large-1-2">
                        <a href="/mua-ban.html?province_code={{ $first->province_code }}"
                            class="gl-location-card gl-location-card-lg">
                            <div class="img-cover">
                                <img src="{{ $provinceImages[$first->province_code] ?? 'https://via.placeholder.com/600x400?text=' . urlencode($first->province_name) }}"
                                    alt="{{ $first->province_name }}">
                            </div>
                            <div class="gl-location-overlay">
                                <h3 class="gl-location-name">{{ $first->province_name }}</h3>
                                <div class="gl-location-count">{{ number_format($first->total_count, 0, ',', '.') }} tin
                                    đăng</div>
                            </div>
                        </a>
                    </div>

                    <div class="uk-width-large-1-2">
                        <div class="uk-grid" data-uk-grid-margin>
                            @foreach ($topProvinces->slice(1) as $loc)
                                <div class="uk-width-1-2">
                                    <a href="/mua-ban.html?province_code={{ $loc->province_code }}"
                                        class="gl-location-card gl-location-card-sm">
                                        <div class="img-cover">
                                            <img src="{{ $provinceImages[$loc->province_code] ?? 'https://via.placeholder.com/300x200?text=' . urlencode($loc->province_name) }}"
                                                alt="{{ $loc->province_name }}">
                                        </div>
                                        <div class="gl-location-overlay">
                                            <h3 class="gl-location-name">{{ $loc->province_name }}</h3>
                                            <div class="gl-location-count">
                                                {{ number_format($loc->total_count, 0, ',', '.') }} tin
                                                đăng</div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (isset($featuredProjects) && $featuredProjects->count() > 0)
        <div class="gl-section gl-featured-projects-section ">
            <div class="uk-container uk-container-center">
                <div class="gl-section-header uk-flex uk-flex-middle uk-flex-space-between uk-margin-bottom">
                    <h2 class="gl-section-title uk-margin-remove">Dự án nổi bật</h2>
                    <a href="/du-an.html" class="gl-view-more">Xem thêm <i class="fa fa-angle-right"></i></a>
                </div>
                <div class="gl-grid-wrapper">
                    <div class="uk-grid uk-grid-medium" data-uk-grid-margin style="margin-left: -20px;">
                        @foreach ($featuredProjects->take(4) as $item)
                            <div class="uk-width-large-1-4 uk-width-medium-1-2 mb20" style="padding-left: 20px;">
                                @include('frontend.component.project_card', [
                                    'item' => $item,
                                ])
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="gl-btn-view-more-container uk-margin-top">
                    <a href="/du-an.html" class="gl-btn-view-more">
                        Xem thêm dự án <i class="fa fa-angle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif




    <div class="gl-section">
        <div class="uk-container uk-container-center">
            <h2 class="gl-section-title uk-margin-bottom">Tin tức</h2>
            <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                @if (isset($posts) && count($posts) > 0)
                    @foreach ($posts as $key => $post)
                        @if ($key < 4)
                            <div class="uk-width-large-1-4 uk-width-medium-1-2 mb20">
                                @include('frontend.component.post_card', ['post' => $post])
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="gl-btn-view-more-container uk-margin-top">
                <a href="/bai-viet.html" class="gl-btn-view-more">
                    Xem thêm tin tức <i class="fa fa-angle-right"></i>
                </a>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.gl-hero-search-input');

            // Sync Placeholder & Labels based on UIkit Tab event
            $('[data-uk-tab]').on('change.uk.tab', function(event, area) {
                const activeTab = $(area).attr('data-tab');
                if (activeTab === 'project') {
                    searchInput.placeholder = 'Tìm kiếm dự án, chủ đầu tư, khu vực...';
                } else {
                    searchInput.placeholder = 'Tìm kiếm địa điểm, khu vực, tên đường...';
                }
            });

            // Update button label when selection changes
            $(document).on('change', '.bar-sync-input', function() {
                const $input = $(this);
                const $dropdown = $input.closest('.hp-filter-dropdown');
                const $label = $dropdown.find('span[id^="bar-selected-"]');
                const placeholder = $label.attr('data-placeholder') || "Tất cả";

                if ($input.attr('type') === 'radio') {
                    const text = $input.closest('label').find('span').text();
                    $label.text($input.val() === '' ? placeholder : text);
                } else {
                    // For checkboxes (Property Types)
                    const checkedCount = $dropdown.find('input:checked').length;
                    if (checkedCount === 0) {
                        $label.text(placeholder);
                    } else if (checkedCount === 1) {
                        $label.text($dropdown.find('input:checked').closest('label').find('span').text());
                    } else {
                        $label.text(`${checkedCount} lựa chọn`);
                    }
                }
            });

            // Redirection logic
            function getSearchUrl() {
                const activePane = document.querySelector('.gl-hero-pane.active');
                const activeTabEl = document.querySelector('.gl-hero-tab-item.active');
                const activeTab = activeTabEl ? activeTabEl.getAttribute('data-tab') : 'sale';
                const keyword = searchInput.value;

                let baseUrl = '/mua-ban.html';
                if (activeTab === 'rent') baseUrl = '/cho-thue.html';
                if (activeTab === 'project') baseUrl = '/du-an.html';

                const params = new URLSearchParams();
                if (keyword && keyword.trim() !== '') params.append('keyword', keyword.trim());

                // Use a helper function to add unique params (Top-level scope of getSearchUrl)
                const addParam = (name, val) => {
                    if (!name || name === '_token' || name === 'transaction_type') return;
                    if (val === '' || val === '0' || val === null || val === undefined) return;

                    // For arrays (name ending with []), we always append
                    if (name.endsWith('[]')) {
                        // Check if this specific combination already exists to avoid duplicates
                        const existing = params.getAll(name);
                        if (!existing.includes(val)) params.append(name, val);
                    } else {
                        // For single values, overwrite/set only if not already present
                        if (!params.has(name)) params.set(name, val);
                    }
                };

                if (activePane) {
                    // Define modal ID based on tab
                    let currentModalId = '#modal-all-filters-project';
                    if (activeTab === 'sale') currentModalId = '#modal-all-filters-74';
                    if (activeTab === 'rent') currentModalId = '#modal-all-filters-75';

                    // 1. Collect from the bar inputs
                    activePane.querySelectorAll('.bar-sync-input').forEach(input => {
                        const name = input.getAttribute('data-name') || input.name;
                        if (input.type === 'checkbox' || input.type === 'radio') {
                            if (input.checked) addParam(name, input.value);
                        } else {
                            addParam(name, input.value);
                        }
                    });

                    // 2. Collect from modal (exhaustively)
                    const $modal = $(currentModalId);
                    if ($modal.length) {
                        $modal.find('input, select, textarea').each(function() {
                            const name = $(this).attr('name');
                            const val = $(this).val();
                            if ($(this).is(':checkbox') || $(this).is(':radio')) {
                                if ($(this).is(':checked')) addParam(name, val);
                            } else {
                                addParam(name, val);
                            }
                        });
                    }
                }

                // Location Modal (header integration)
                const locationModal = $('#modal-location');
                if (locationModal.length) {
                    const isAfter = $('#switch-location-mode').is(':checked');
                    if (isAfter) {
                        addParam('province_code', locationModal.find('select[name=province_code]').val());
                        addParam('district_code', locationModal.find('select[name=district_code]').val());
                    } else {
                        addParam('old_province_code', locationModal.find('select[name=old_province_code]').val());
                        addParam('old_district_code', locationModal.find('select[name=old_district_code]').val());
                    }
                }

                const queryString = params.toString();
                return queryString ? `${baseUrl}?${queryString}` : baseUrl;
            }

            // Global Submit
            $('.btn-hero-submit').on('click', function(e) {
                e.preventDefault();
                window.location.href = getSearchUrl();
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    window.location.href = getSearchUrl();
                }
            });

            $(document).on('click', '.hp-btn-apply-bar, .hp-btn-modal-apply', function(e) {
                e.preventDefault();
                window.location.href = getSearchUrl();
            });

            // Reset
            $('.btn-hero-reset').on('click', function(e) {
                e.preventDefault();
                searchInput.value = '';
                const activePane = document.querySelector('.gl-hero-pane.active');
                if (activePane) {
                    $(activePane).find('.bar-sync-input').prop('checked', false).val('');
                    $(activePane).find('span[id^="bar-selected-"]').each(function() {
                        $(this).text($(this).attr('data-placeholder') || "Tất cả");
                    });
                }
            });

            // Initialize Swipers
            if (typeof Swiper !== 'undefined') {
                // Featured Projects Swiper (4 items per view)
                new Swiper('.gl-featured-projects-swiper', {
                    slidesPerView: 4,
                    slidesPerGroup: 4,
                    spaceBetween: 20,
                    loop: true,
                    autoplay: {
                        delay: 6000,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.gl-featured-projects-section .gl-swiper-next',
                        prevEl: '.gl-featured-projects-section .gl-swiper-prev',
                    },
                    breakpoints: {
                        320: {
                            slidesPerView: 1.2,
                            slidesPerGroup: 1,
                            spaceBetween: 10
                        },
                        768: {
                            slidesPerView: 2.2,
                            slidesPerGroup: 2,
                            spaceBetween: 15
                        },
                        1024: {
                            slidesPerView: 4,
                            slidesPerGroup: 4,
                            spaceBetween: 20
                        }
                    },
                    speed: 1000,
                });

                // Recommended Real Estate Swiper (Slide contains 8 items)
                new Swiper('.gl-recommended-swiper', {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.gl-recommended-section .gl-swiper-next',
                        prevEl: '.gl-recommended-section .gl-swiper-prev',
                    },
                    speed: 800,
                });
            }
        });
    </script>
@endsection
