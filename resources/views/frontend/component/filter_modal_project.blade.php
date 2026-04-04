<div id="modal-all-filters" class="uk-modal hp-modal-filter">
    <div class="uk-modal-dialog">

        <div id="hp-view-main-project" class="hp-modal-view active">
            <div class="uk-modal-header uk-flex uk-flex-middle uk-flex-space-between">
                <h3 class="uk-modal-title uk-margin-remove">Bộ lọc dự án</h3>
                <a class="uk-modal-close"><i class="fa fa-times" style="font-size: 20px; color: #999;"></i></a>
            </div>
            <div class="uk-modal-body">
                <form action="{{ request()->url() }}" method="GET" id="filter-form">
                    <div class="uk-grid uk-grid-medium" data-uk-grid-margin>

                        <div class="uk-width-1-1">
                            <label class="group-label">Loại hình dự án</label>
                            <div class="hp-input-box uk-flex uk-flex-middle uk-flex-space-between"
                                onclick="showSubView_project('hp-view-property-project')">
                                <span class="uk-text-muted" id="selected-property-text-project">Tất cả loại hình</span>
                                <span class="uk-text-primary uk-text-small" style="font-weight:600">+ Thêm</span>
                            </div>
                        </div>


                        <div class="uk-width-1-1 uk-margin-top">
                            <label class="group-label">Khu vực</label>
                            <div class="hp-input-box uk-flex uk-flex-middle uk-flex-space-between"
                                onclick="showSubView_project('hp-view-location-project')">
                                <div class="uk-flex uk-flex-middle">
                                    <i class="fa fa-map-marker-alt uk-margin-small-right"
                                        style="color:var(--main-color)"></i>
                                    <span class="uk-text-muted" id="selected-location-text-project">Trên toàn
                                        quốc</span>
                                </div>
                                <i class="fa fa-chevron-right uk-text-muted"></i>
                            </div>
                        </div>


                        <div class="uk-width-1-1 uk-margin-top">
                            <div class="uk-grid uk-grid-small" data-uk-grid-margin>
                                <div class="uk-width-1-1">
                                    <label class="group-label">Diện tích (m²)</label>
                                    <div class="hp-input-box uk-flex uk-flex-middle uk-flex-space-between"
                                        onclick="showSubView_project('hp-view-area-project')">
                                        <div class="uk-flex uk-flex-middle">
                                            <i class="fa fa-expand uk-margin-small-right"
                                                style="color:var(--main-color)"></i>
                                            <span class="uk-text-muted" id="selected-area-text-project">Tất cả</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-modal-close hp-btn-modal-cancel">Hủy</button>
                <button type="submit" form="filter-form" class="uk-button hp-btn-modal-apply">Xem kết
                    quả</button>
            </div>
        </div>


        <div id="hp-view-property-project" class="hp-modal-view">
            <div class="uk-modal-header uk-flex uk-flex-middle">
                <a onclick="hideSubView_project()" class="uk-margin-right"><i class="fa fa-chevron-left"
                        style="color:#333"></i></a>
                <h3 class="uk-modal-title uk-margin-remove">Loại hình dự án</h3>
            </div>
            <div class="uk-modal-body">
                <div class="hp-selection-list">
                    @if (isset($projectCatalogues))
                        @foreach ($projectCatalogues as $cat)
                            <label class="hp-selection-item uk-flex uk-flex-middle uk-flex-space-between">
                                <span>{{ $cat->languages->first()->pivot->name ?? '' }}</span>
                                <input type="checkbox" class="uk-checkbox" name="project_catalogue_id[]"
                                    value="{{ $cat->id }}" form="filter-form"
                                    @if (is_array(request('project_catalogue_id')) && in_array($cat->id, request('project_catalogue_id'))) checked @endif>
                            </label>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="uk-modal-footer">
                <button class="uk-button hp-btn-main uk-width-1-1" onclick="hideSubView_project()">Xác nhận</button>
            </div>
        </div>


        <div id="hp-view-location-project" class="hp-modal-view">
            <div class="uk-modal-header uk-flex uk-flex-middle">
                <a onclick="hideSubView_project()" class="uk-margin-right"><i class="fa fa-chevron-left"
                        style="color:#333"></i></a>
                <h3 class="uk-modal-title uk-margin-remove">Khu vực</h3>
            </div>
            <div class="uk-modal-body">
                <div class="gl-switch-container">
                    <span class="gl-switch-text">Tìm theo địa chỉ mới sau sáp nhập</span>
                    <label class="gl-switch">
                        <input type="checkbox" id="switch-location-mode-filter-project" name="is_after_merger"
                            value="1" {{ request('is_after_merger') ? 'checked' : '' }}
                            onclick="handleLocationSwitch_project(this)">
                        <span class="gl-slider"></span>
                    </label>
                </div>

                <script>
                    function handleLocationSwitch_project(el) {
                        const isAfter = el.checked;
                        const modal = $(el).closest('.hp-modal-view');
                        if (isAfter) {
                            modal.find('.gl-location-after-filter').show();
                            modal.find('.gl-location-before-filter').hide();
                            modal.find('.gl-location-before-filter select').val('0').trigger('change.select2');
                        } else {
                            modal.find('.gl-location-after-filter').hide();
                            modal.find('.gl-location-before-filter').show();
                            modal.find('.gl-location-after-filter select').val('0').trigger('change.select2');
                        }
                    }
                </script>

                {{-- After Merger (2 Levels) --}}
                <div class="gl-location-after-filter" style="{{ request('is_after_merger') ? '' : 'display: none;' }}">
                    <div class="gl-form-group">
                        <label class="gl-form-label">Thành Phố</label>
                        <select name="province_code" class="gl-select location province setupSelect2Filter"
                            data-target="wards" data-source="after" form="filter-form">
                            <option value="0">[Chọn Thành Phố]</option>
                            @if (isset($provinces))
                                @foreach ($provinces as $key => $val)
                                    <option value="{{ $key }}"
                                        {{ request('province_code') == $key ? 'selected' : '' }}>{{ $val }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="gl-form-group">
                        <label class="gl-form-label">Phường/Xã</label>
                        <select name="ward_code" class="gl-select wards setupSelect2Filter" data-source="after"
                            form="filter-form">
                            <option value="0">[Chọn Phường/Xã]</option>
                        </select>
                    </div>
                </div>

                {{-- Before Merger (3 Levels) --}}
                <div class="gl-location-before-filter"
                    style="{{ request('is_after_merger') ? 'display: none;' : '' }}">
                    <div class="gl-form-group">
                        <label class="gl-form-label">Thành Phố</label>
                        <select name="old_province_code" class="gl-select location province setupSelect2Filter"
                            data-target="old_districts" data-source="before" form="filter-form">
                            <option value="0">[Chọn Thành Phố]</option>
                            @if (isset($old_provinces))
                                @foreach ($old_provinces as $key => $val)
                                    <option value="{{ $key }}"
                                        {{ request('old_province_code') == $key ? 'selected' : '' }}>
                                        {{ $val }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="gl-form-group">
                        <label class="gl-form-label">Quận/Huyện</label>
                        <select name="old_district_code" class="gl-select location old_districts setupSelect2Filter"
                            data-target="old_wards" data-source="before" form="filter-form">
                            <option value="0">[Chọn Quận/Huyện]</option>
                        </select>
                    </div>

                    <div class="gl-form-group">
                        <label class="gl-form-label">Phường/Xã</label>
                        <select name="old_ward_code" class="gl-select old_wards setupSelect2Filter"
                            data-source="before" form="filter-form">
                            <option value="0">[Chọn Phường/Xã]</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="uk-modal-footer">
                <button class="uk-button hp-btn-main uk-width-1-1" onclick="hideSubView_project()">Xác nhận</button>
            </div>
        </div>


        <div id="hp-view-area-project" class="hp-modal-view">
            <div class="uk-modal-header uk-flex uk-flex-middle">
                <a onclick="hideSubView_project()" class="uk-margin-right"><i class="fa fa-chevron-left"
                        style="color:#333"></i></a>
                <h3 class="uk-modal-title uk-margin-remove">Diện tích</h3>
            </div>
            <div class="uk-modal-body">
                <div class="hp-custom-range-wrapper uk-margin-bottom">
                    <label class="group-label">Nhập diện tích tùy chỉnh (m²)</label>
                    <div class="uk-grid uk-grid-small" data-uk-grid-margin>
                        <div class="uk-width-1-2">
                            <input type="number" name="area_min" placeholder="Từ"
                                class="uk-width-1-1 hp-custom-input" form="filter-form"
                                value="{{ request('area_min') }}">
                        </div>
                        <div class="uk-width-1-2">
                            <input type="number" name="area_max" placeholder="Đến"
                                class="uk-width-1-1 hp-custom-input" form="filter-form"
                                value="{{ request('area_max') }}">
                        </div>
                    </div>
                </div>
                @php
                    $areaOptions = [
                        '' => 'Tất cả diện tích',
                        '0-30' => 'Dưới 30 m²',
                        '30-50' => '30 - 50 m²',
                        '50-80' => '50 - 80 m²',
                        '80-100' => '80 - 100 m²',
                        '100-150' => '100 - 150 m²',
                        '150-200' => '150 - 200 m²',
                        '200-300' => '200 - 300 m²',
                        '300-500' => '300 - 500 m²',
                        '500-99999' => 'Trên 500 m²',
                    ];
                @endphp
                @include('frontend.component.filter_range_list', [
                    'name' => 'area',
                    'options' => $areaOptions,
                ])
            </div>
            <div class="uk-modal-footer"><button class="uk-button hp-btn-main uk-width-1-1"
                    onclick="hideSubView_project()">Xác nhận</button></div>
        </div>
    </div>
</div>
