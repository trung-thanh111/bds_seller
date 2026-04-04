@extends('frontend.homepage.layout')
@section('header-class', 'header-inner')
@section('content')
    <style>
        .hp-contact-page {
            font-family: var(--font-main), sans-serif;
            color: var(--text-main);
            background: #fff;
            padding-bottom: 80px;
        }

        .hp-contact-content-wrap {
            padding: 60px 0;
        }

        @media (max-width: 767px) {
            .hp-contact-content-wrap {
                padding: 20px 0;
            }

            .hp-detail-header {
                margin-bottom: 10px !important;
            }
        }

        .hp-contact-sidebar {
            background: #fcfdfe;
            padding: 40px 30px;
            border-radius: 8px;
            border: 1px solid #eee;
            height: 100%;
        }

        .hp-sidebar-header {
            margin-bottom: 35px;
            padding-left: 15px;
            border-left: 3px solid var(--main-color);
        }

        .hp-sidebar-header h2 {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            color: #111;
        }

        .hp-contact-item {
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
        }

        .hp-contact-item .icon-box {
            font-size: 18px;
            color: var(--main-color);
            margin-top: 3px;
        }

        .hp-contact-item h4 {
            font-size: 14px;
            font-weight: 700;
            color: #222;
            margin: 0 0 5px;
            text-transform: uppercase;
        }

        .hp-contact-item p,
        .hp-contact-item a {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            margin: 0;
            text-decoration: none;
        }

        .hp-contact-item a:hover {
            color: var(--main-color);
        }

        /* 4. Contact Form Card */
        .hp-contact-form-box {
            padding: 20px 0 20px 40px;
        }

        @media (max-width: 959px) {
            .hp-contact-form-box {
                padding: 40px 0;
            }
        }

        .hp-form-title-group {
            margin-bottom: 40px;
        }

        .hp-form-title-group h2 {
            font-size: 28px;
            font-weight: 700;
            color: #111;
            margin: 0 0 8px;
        }

        .hp-form-title-group p {
            color: #888;
            font-size: 15px;
        }

        /* Input Styling */
        .gl-input-group {
            margin-bottom: 35px;
        }

        .gl-label {
            display: block;
            font-weight: 600;
            font-size: 13px;
            color: #555;
            margin-bottom: 8px;
        }

        .gl-input-field {
            font-family: inherit !important;
            width: 100%;
            border: 1px solid #e1e1e1 !important;
            border-radius: 6px !important;
            padding: 12px 16px !important;
            font-size: 14px !important;
            transition: all 0.2s;
            background: #fff;
        }

        .gl-input-field:focus {
            border-color: var(--main-color) !important;
            outline: none;
        }

        .hp-submit-button {
            font-family: inherit !important;
            background: var(--main-color) !important;
            color: #fff !important;
            border: none !important;
            padding: 16px 30px !important;
            border-radius: 6px !important;
            font-weight: 700 !important;
            font-size: 15px !important;
            cursor: pointer !important;
            width: auto;
            min-width: 200px;
            transition: all 0.2s;
            text-align: end;
        }

        .hp-submit-button:hover {
            opacity: 0.9;
        }

        .hp-map-bottom-wrap {
            margin-top: 40px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #eee;
            height: 450px;
        }

        .hp-map-bottom-wrap iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }

        /* Success Message */
        .contact-form-success {
            display: none;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 20px;
            border-radius: 6px;
            text-align: center;
            margin-top: 25px;
        }
    </style>

    <div id="scroll-progress"></div>
    <div class="hp-contact-page">

        <section class="hp-detail-header uk-margin-bottom">
            <div class="uk-container uk-container-center">
                <ul class="uk-breadcrumb uk-flex uk-flex-middle">
                    <li><a href="{{ url('/') }}">Trang chủ</a></li>
                    <li class="uk-active"><span>Liên hệ</span></li>
                </ul>
            </div>
        </section>

        <!-- Main Content Area -->
        <div class="hp-contact-content-wrap">
            <div class="uk-container uk-container-center">
                <div class="uk-grid uk-grid-collapse" data-uk-grid-margin>

                    <!-- Sidebar Column (1/3) -->
                    <div class="uk-width-large-1-4 uk-width-medium-1-3">
                        <aside class="hp-contact-sidebar">
                            <div class="hp-sidebar-header">
                                <h2>Thông tin</h2>
                            </div>

                            <div class="hp-contact-item">
                                <div class="icon-box"><i class="fas fa-map-marker-alt"></i></div>
                                <div>
                                    <h4>Địa chỉ</h4>
                                    <p>{{ $system['contact_address'] ?? '88 Nguyễn Hữu Cảnh, Phường 22, Bình Thạnh, Hồ Chí Minh' }}
                                    </p>
                                </div>
                            </div>

                            <div class="hp-contact-item">
                                <div class="icon-box"><i class="fas fa-phone-alt"></i></div>
                                <div>
                                    <h4>Điện thoại</h4>
                                    <p><a href="tel:{{ str_replace(' ', '', $system['contact_hotline'] ?? '0987654321') }}">
                                            {{ $system['contact_hotline'] ?? '0987 654 321' }}
                                        </a></p>
                                    <p class="uk-text-small uk-text-muted">Zalo:
                                        {{ $system['contact_hotline'] ?? '0987 654 321' }}</p>
                                </div>
                            </div>

                            <div class="hp-contact-item">
                                <div class="icon-box"><i class="fas fa-envelope"></i></div>
                                <div>
                                    <h4>Email</h4>
                                    <p><a href="mailto:{{ $system['contact_email'] ?? 'homepark@gmail.com' }}">
                                            {{ $system['contact_email'] ?? 'homepark@gmail.com' }}
                                        </a></p>
                                </div>
                            </div>

                            <div class="hp-contact-item">
                                <div class="icon-box"><i class="fas fa-clock"></i></div>
                                <div>
                                    <h4>Làm việc</h4>
                                    <p>Thứ 2 - Thứ 7: 8h00 - 18h00</p>
                                    <p>Chủ nhật: 9h00 - 16h00</p>
                                </div>
                            </div>
                        </aside>
                    </div>

                    <!-- Form Column (3/4) -->
                    <div class="uk-width-large-3-4 uk-width-medium-2-3">
                        <div class="hp-contact-form-box">
                            <div class="hp-form-title-group">
                                <h2>Gửi yêu cầu tư vấn</h2>
                                <p>Để lại thông tin bên dưới, nhân viên hỗ trợ của chúng tôi sẽ liên hệ trong thời gian sớm
                                    nhất.</p>
                            </div>

                            <form id="contact-request-form" class="ajax-contact-form" method="post"
                                action="{{ route('contact-request.store') }}">
                                @csrf
                                <div class="uk-grid uk-grid-medium">
                                    <div class="uk-width-medium-1-2 uk-margin-bottom">
                                        <div class="gl-input-group">
                                            <label class="gl-label">Họ và tên *</label>
                                            <input type="text" name="full_name" required class="gl-input-field"
                                                placeholder="Nhập họ và tên...">
                                        </div>
                                    </div>
                                    <div class="uk-width-medium-1-2 uk-margin-bottom">
                                        <div class="gl-input-group">
                                            <label class="gl-label">Số điện thoại *</label>
                                            <input type="text" name="phone" required class="gl-input-field"
                                                placeholder="Nhập số điện thoại...">
                                        </div>
                                    </div>
                                    <div class="uk-width-medium-1-2 uk-margin-bottom">
                                        <div class="gl-input-group">
                                            <label class="gl-label">Email</label>
                                            <input type="email" name="email" class="gl-input-field"
                                                placeholder="Nhập địa chỉ email...">
                                        </div>
                                    </div>
                                    <div class="uk-width-medium-1-2 uk-margin-bottom">
                                        <div class="gl-input-group">
                                            <label class="gl-label">Chủ đề</label>
                                            <input type="text" name="subject" class="gl-input-field"
                                                placeholder="Ví dụ: Tư vấn mua nhà, pháp lý...">
                                        </div>
                                    </div>
                                    <div class="uk-width-1-1 uk-margin-bottom">
                                        <div class="gl-input-group">
                                            <label class="gl-label">Lời nhắn / Yêu cầu chi tiết *</label>
                                            <textarea name="content" required class="gl-input-field" rows="6" placeholder="Vui lòng mô tả yêu cầu của bạn..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="hp-submit-button">
                                    GỬI YÊU CẦU NGAY
                                </button>

                                <div class="contact-form-success">
                                    <h4 class="uk-text-success uk-margin-small-bottom">Gửi thành công!</h4>
                                    <p>Cảm ơn bạn đã liên hệ. Chúng tôi đã nhận được thông tin và sẽ phản hồi sớm nhất.</p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Full Width Map Section -->
                <div class="hp-map-bottom-wrap" data-uk-scrollspy="{cls:'uk-animation-fade', delay:300}">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4602324243567!2d106.718144975838!3d10.776019489372922!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f44dc1b78ad%3A0xc07ce67822989f92!2sLinden%20Residences!5e0!3m2!1svi!2s!4v1710145241085!5m2!1svi!2s"
                        allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>

    </div>
@endsection
