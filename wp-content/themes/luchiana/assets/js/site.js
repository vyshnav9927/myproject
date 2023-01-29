(function ($, root, undefined) {
	"use strict";
	
	$.migrateMute = true;
	$.migrateTrace = false;
	
	if (!ideapark_empty(requirejs)) {
		requirejs.config({
			baseUrl    : ideapark_wp_vars.themeUri + '/assets/js',
			paths      : {
				text: 'requirejs/text',
				css : 'requirejs/css',
				json: 'requirejs/json'
			},
			urlArgs    : function (id, url) {
				var args = '';
				
				if (url.indexOf('/css/photoswipe/') !== -1) {
					args = 'v=' + ideapark_wp_vars.stylesHash;
				}
				
				if (id === 'photoswipe/photoswipe.min' || id === 'photoswipe/photoswipe-ui-default.min') {
					args = 'v=' + ideapark_wp_vars.scriptsHash;
				}
				
				return args ? (url.indexOf('?') === -1 ? '?' : '&') + args : '';
			},
			waitSeconds: 0
		});
		
		root.old_define = root.define;
		root.define = null;
	}
	
	root.ideapark_videos = [];
	root.ideapark_players = [];
	root.ideapark_env_init = false;
	root.ideapark_slick_paused = false;
	root.ideapark_is_mobile = false;
	
	root.old_windows_width = 0;
	
	var $window = $(window);
	
	var ideapark_all_is_loaded = false;
	var ideapark_mega_menu_initialized = 0;
	var $ideapark_masonry_grid = $('.js-post-masonry');
	var $ideapark_masonry_sidebar = $('.c-post-sidebar');
	var ideapark_post_with_thumb = $('.c-post-list--with-thumb').length > 0;
	var ideapark_post_no_thumb = $('.c-post-list--no-thumb').length > 0;
	var ideapark_masonry_grid_on = !!$ideapark_masonry_grid.length && (ideapark_post_with_thumb && ideapark_post_no_thumb);
	var ideapark_masonry_sidebar_on = !!$ideapark_masonry_sidebar.length && $ideapark_masonry_sidebar.find('.widget').length > 2;
	var ideapark_is_masonry_init = false;
	var ideapark_masonry_sidebar_object = null;
	var $ideapark_store_notice_top = $('.woocommerce-store-notice--top');
	var $ideapark_mobile_advert_bar_above = $('.c-header__advert_bar--above');
	var ideapark_mobile_advert_bar_above_delta = 0;
	var $ideapark_mobile_menu = $('.js-mobile-menu');
	var ideapark_mobile_menu_initialized = false;
	var ideapark_mobile_menu_active = false;
	var ideapark_mobile_menu_page = -1;
	
	var $ideapark_cart_sidebar = $('.js-cart-sidebar');
	var $ideapark_cart_sidebar_wrap = $('.js-cart-sidebar-wrap');
	var ideapark_cart_sidebar_initialized = false;
	var ideapark_cart_sidebar_active = false;
	
	var $ideapark_shop_sidebar = $('.js-shop-sidebar');
	var $ideapark_shop_sidebar_wrap = $('.js-shop-sidebar-wrap');
	var ideapark_shop_sidebar_filter_desktop = $ideapark_shop_sidebar.hasClass('c-shop-sidebar--desktop-filter');
	var ideapark_shop_sidebar_initialized = false;
	var ideapark_shop_sidebar_active = false;
	
	var ideapark_search_popup_active = false;
	var ideapark_search_popup_initialized = false;
	
	var $ideapark_page_header = $('.c-page-header');
	var ideapark_page_header_top = 0;
	var ideapark_header_height = 0;
	var $ideapark_desktop_sticky_row = $('.js-header-desktop');
	var $ideapark_mobile_sticky_row = $('.js-header-mobile');
	var $ideapark_header_outer = $('.c-header__outer--desktop');
	var ideapark_sticky_desktop_active = false;
	var ideapark_sticky_desktop_init = false;
	var ideapark_sticky_mobile_active = false;
	var ideapark_sticky_mobile_init = false;
	
	var $ideapark_sticky_sidebar = $('.js-sticky-sidebar');
	var $ideapark_sticky_sidebar_nearby = $('.js-sticky-sidebar-nearby');
	var ideapark_sticky_sidebar_old_style = null;
	var ideapark_is_sticky_sidebar_inner = !!$ideapark_sticky_sidebar_nearby.find('.js-sticky-sidebar').length;
	
	var $ideapark_to_top_button = $('.js-to-top-button');
	var $ideapark_quickview_container = $('.js-quickview-container');
	var $ideapark_quickview_popup = $('.js-quickview-popup');
	var $ideapark_simple_container = $('.js-simple-container');
	
	var $ideapark_infinity_loader;
	var ideapark_has_loader = false;
	
	var ideapark_nav_text = [
		'<i class="ip-right h-carousel__prev"></i>',
		'<i class="ip-right h-carousel__next"></i>'
	];
	var $ideapark_header_row = $('.c-header--sticky');
	
	document.onreadystatechange = function () {
		if (document.readyState === 'complete') {
			ideapark_all_is_loaded = true;
			$('select.orderby').addClass('js-auto-width');
			ideapark_init_auto_select_width();
			ideapark_mega_menu_init();
		}
	};
	
	$(window).on("pageshow", function (e) {
		if (e.originalEvent.persisted) {
			if (ideapark_is_mobile_layout) {
				ideapark_sidebar_popup(false);
				ideapark_cart_sidebar_popup(false);
			}
			setTimeout(function () {
				try {
					var wc_fragments = JSON.parse(sessionStorage.getItem(wc_cart_fragments_params.fragment_name));
					if (wc_fragments && wc_fragments['div.widget_shopping_cart_content']) {
						$('div.widget_shopping_cart_content').replaceWith(wc_fragments['div.widget_shopping_cart_content']);
					}
				} catch (err) {
				}
			}, 500);
		}
	});
	
	$(function () {
		
		$ideapark_to_top_button.on('click', function () {
			$('html, body').animate({scrollTop: 0}, 800);
		});
		
		$('.js-mobile-top-menu a[href^="#"], .js-top-menu a[href^="#"]').on('click', ideapark_hash_menu_animate);
		
		$('ul.wc-tabs a').on('click', function () {
			setTimeout(ideapark_sticky_sidebar, 100);
		});
		
		$ideapark_desktop_sticky_row.addClass('c-header--init');
		
		$('.js-login-form-toggle').on('click', function (e) {
			var $this = $(this);
			var $active_tab = $this.closest('.c-login__form');
			var $new_tab = $('.c-login__form:not(.c-login__form--active)');
			
			e.preventDefault();
			
			$active_tab.removeClass('c-login__form--active');
			$new_tab.addClass('c-login__form--active');
		});
		
		$(".js-wishlist-share-link").on('focus', function () {
			$(this).trigger('select');
			document.execCommand('copy');
		});
		
		$(document.body)
			.one('click', '.js-search-button,.js-mobile-menu-open,.js-filter-show-button,.js-cart-sidebar-open,.js-accordion-title,.js-ip-tabs-link', function (e) {
				e.preventDefault();
				if (!ideapark_defer_action_done()) {
					var $this = $(this);
					$(document).one('ideapark.defer.done', function () {
						$this.trigger('click');
					});
					ideapark_defer_action_run();
				}
			})
			.on('keydown', function (e) {
				if (e.keyCode === 27) {
					$('button.js-callback-close').trigger('click');
					$('button.js-search-close').trigger('click');
					$('button.js-filter-close-button').trigger('click');
				}
				
				if (e.keyCode === 37 || e.keyCode === 39) {
					var $carousel = $('.js-single-product-carousel.owl-loaded');
					if ($carousel.length === 1) {
						if (e.keyCode === 37) { // prev
							$carousel.trigger('prev.owl.carousel');
						} else if (e.keyCode === 39) { // next
							$carousel.trigger('next.owl.carousel');
						}
					}
					var $nav_prev = $('.c-post__nav-prev');
					if ($nav_prev.length && e.keyCode === 37) {
						document.location.href = $nav_prev.attr('href');
					}
					var $nav_next = $('.c-post__nav-next');
					if ($nav_next.length && e.keyCode === 39) {
						document.location.href = $nav_next.attr('href');
					}
				}
			})
			.on('click', '.h-link-yes', function (e) {
				e.preventDefault();
				var $scope = $(this);
				if ($scope.data('ip-url') && $scope.data('ip-link') == 'yes') {
					if ($scope.data('ip-new-window') == 'yes') {
						window.open($scope.data('ip-url'));
					} else {
						location.href = $scope.data('ip-url');
					}
				}
			})
			.on('click', ".js-mobile-modal", function (e) {
				$(this).parent().find(".js-product-modal").first().trigger('click');
			})
			.on('click', ".js-product-modal", function (e) {
				e.preventDefault();
				var $button = $(this);
				var $play_button = $('.c-play', $button);
				var $button_loading = $play_button.length ? $play_button : $('.js-loading-wrap', $button);
				if ($button_loading.hasClass('js-loading')) {
					return;
				}
				var index = 0;
				if (ideapark_isset($button.data('index'))) {
					$button_loading.ideapark_button('loading', 25);
					index = $button.data('index');
				} else {
					$button_loading.ideapark_button('loading');
				}
				var $product = $button.closest('.product');
				var variation_id = $product.find('.variation_id').val();
				root.define = root.old_define;
				require([
					'photoswipe/photoswipe.min',
					'photoswipe/photoswipe-ui-default.min',
					'json!' + ideapark_wp_vars.ajaxUrl + '?action=ideapark_product_images&index=' + index + '&product_id=' + $button.data('product-id') + (!ideapark_empty(variation_id) ? '&variation_id=' + variation_id : '') + '!bust',
					'css!' + ideapark_wp_vars.themeUri + '/assets/css/photoswipe/photoswipe',
					'css!' + ideapark_wp_vars.themeUri + '/assets/css/photoswipe/default-skin/default-skin'
				], function (PhotoSwipe, PhotoSwipeUI_Default, images) {
					root.define = null;
					$button_loading.ideapark_button('reset');
					if (images.images.length) {
						var options = {
							index              : index ? index : 0,
							showHideOpacity    : true,
							bgOpacity          : 1,
							loop               : false,
							closeOnVerticalDrag: false,
							mainClass          : '',
							barsSize           : {top: 0, bottom: 0},
							captionEl          : false,
							fullscreenEl       : false,
							zoomEl             : true,
							shareEl            : false,
							counterEl          : false,
							tapToClose         : true,
							tapToToggleControls: false
						};
						
						var pswpElement = $('.pswp')[0];
						
						ideapark_wpadminbar_resize();
						
						var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, images.images, options);
						gallery.init();
						
						gallery.listen('afterChange', function () {
							if (!ideapark_empty(gallery.currItem.html)) {
								// $('.pswp__video-wrap').fitVids();
							}
						});
						
						gallery.listen('close', function () {
							$('.pswp__video-wrap').html('');
						});
						
						// $('.pswp__video-wrap').fitVids();
					}
				});
			})
			.on('click', ".js-video", function (e) {
				e.preventDefault();
				ideapark_init_venobox($(this));
			})
			.on('click', ".js-ajax-search-all", function (e) {
				$(this).closest('.js-ajax-search').find('.js-search-form').submit();
			})
			.on('click', '.js-load-more', function (e) {
				ideapark_infinity_loader($(this), e);
			})
			.on('wc_cart_button_updated', function (e, $button) {
				var $view_cart_button = $button.parent().find('.added_to_cart');
				$view_cart_button.addClass('c-product-grid__atc');
			})
			.on('click', '.js-notice-close', function (e) {
				e.preventDefault();
				var $notice = $(this).closest('.woocommerce-notice');
				$notice.animate({
					opacity: 0,
				}, 500, function () {
					$notice.remove();
				});
			})
			.on('adding_to_cart', function (e, $button) {
				$button.ideapark_button('loading', 16);
			})
			.on('added_to_cart', function (e, fragments, cart_hash, $button) {
				if (ideapark_is_mobile_layout && ideapark_wp_vars.popupCartOpenMobile || !ideapark_is_mobile_layout && ideapark_wp_vars.popupCartOpenDesktop) {
					ideapark_cart_sidebar_popup(true);
				} else {
					if (typeof fragments.ideapark_notice !== 'undefined') {
						ideapark_show_notice(fragments.ideapark_notice);
					}
				}
			})
			.on('wc_fragments_loaded wc_fragment_refresh wc_fragments_refreshed', function (e) {
				if (ideapark_masonry_sidebar_object) {
					ideapark_masonry_sidebar_object.layout();
				}
			})
			.on('click', ".js-quantity-minus", function (e) {
				e.preventDefault();
				var $input = $(this).parent().find('input[type=number]');
				var quantity = $input.val().trim();
				var min = $input.attr('min');
				quantity--;
				if (quantity < (min !== '' ? min : 1)) {
					quantity = (min !== '' ? min : 1);
				}
				$input.val(quantity);
				$input.trigger('change');
				
			})
			.on('click', ".js-quantity-plus", function (e) {
				e.preventDefault();
				var $input = $(this).parent().find('input[type=number]');
				var quantity = $input.val().trim();
				var max = $input.attr('max');
				quantity++;
				if ((max !== '') && (quantity > max)) {
					quantity = max;
				}
				if (quantity > 0) {
					$input.val(quantity);
					$input.trigger('change');
				}
			})
			.on('click', '.js-cart-coupon', function (e) {
				e.preventDefault();
				var $coupon = $(".c-cart__coupon-from-wrap");
				$coupon.toggleClass('c-cart__coupon-from-wrap--opened');
				$('.c-cart__select-icon').toggleClass('c-cart__select-icon--opened');
				if ($coupon.hasClass('c-cart__coupon-from-wrap--opened')) {
					setTimeout(function () {
						$coupon.find('input[type=text]').first().trigger('focus');
					}, 500);
				}
				return false;
			})
			.on('checkout_error updated_checkout applied_coupon removed_coupon updated_wc_div', function (e) {
				ideapark_search_notice();
			})
			.on('click', "#ip-checkout-apply-coupon", function () {
				
				var params = null;
				var is_cart = false;
				
				if (typeof wc_checkout_params != 'undefined') {
					params = wc_checkout_params;
					is_cart = false;
				}
				
				if (typeof wc_cart_params != 'undefined') {
					params = wc_cart_params;
					is_cart = true;
				}
				
				if (!params) {
					return false;
				}
				
				var $collaterals = $(this).closest('.c-cart__collaterals');
				
				if ($collaterals.is('.processing')) {
					return false;
				}
				
				$collaterals.addClass('processing').block({
					message   : null,
					overlayCSS: {
						background: '#fff',
						opacity   : 0.6
					}
				});
				
				var data = {
					security   : params.apply_coupon_nonce,
					coupon_code: $collaterals.find('input[name="coupon_code"]').val()
				};
				
				$.ajax({
					type    : 'POST',
					url     : params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
					data    : data,
					success : function (code) {
						if (code) {
							ideapark_show_notice(code);
							if (is_cart) {
								$.ajax({
									url     : params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_cart_totals'),
									dataType: 'html',
									success : function (response) {
										$collaterals.html(response);
									},
									complete: function () {
										$collaterals.removeClass('processing').unblock();
									}
								});
								$('.c-cart__shop-update-button').prop('disabled', false).trigger('click');
							} else {
								$collaterals.removeClass('processing').unblock();
								$(document.body).trigger('update_checkout', {update_shipping_method: false});
							}
						}
					},
					dataType: 'html'
				});
				
				return false;
			})
			.on('click', 'a.woocommerce-review-link', function (e) {
				e.preventDefault();
				var $quickview_container = $(this).closest('.c-product--quick-view');
				if ($quickview_container.length) {
					var product_url = $quickview_container.find('.woocommerce-LoopProduct-link').first().attr('href') + '#reviews';
					document.location.href = product_url;
					return false;
				} else {
					setTimeout(function () {
						ideapark_hash_menu_animate(e);
					}, 100);
				}
			})
			.on('click', '.woocommerce-store-notice__dismiss-link', function () {
				setTimeout(function () {
					$(document).trigger('ideapark.wpadminbar.scroll', ideapark_adminbar_visible_height);
				}, 100);
			})
			.on('click', '.c-product-grid__thumb-wrap a', function (e) {
				var width = $(window).width();
				var $this = $(this);
				var $item = $this.closest('.c-product-grid__item');
				var $atc = $item.find('.c-product-grid__atc');
				
				if (!ideapark_wp_vars.hideButtons) {
					if (width >= 360 && width <= 619 && $item.hasClass('c-product-grid__item--2-per-row') && (ideapark_wp_vars.withButtons || $atc.length && !$atc.hasClass('c-product-grid__atc--hide-mobile-2'))) {
						e.preventDefault();
					} else if (ideapark_is_mobile_layout && $item.hasClass('c-product-grid__item--compact') && (ideapark_wp_vars.withButtons || $atc.length)) {
						e.preventDefault();
					}
				}
			})
			.on('click', '.c-product-grid__thumb-wrap', function () {
				if (ideapark_is_mobile_layout && !ideapark_wp_vars.hideButtons) {
					var $this = $(this);
					if (!$this.hasClass('c-product-grid__thumb-wrap--hover')) {
						$('.c-product-grid__thumb-wrap--hover').removeClass('c-product-grid__thumb-wrap--hover');
						$this.addClass('c-product-grid__thumb-wrap--hover');
					}
				}
			})
			.on('click', '.js-grid-zoom', function () {
				var $button = $(this),
					ajax_url,
					product_id = $button.data('product-id'),
					data = {
						product_id: product_id,
						lang      : $button.data('lang')
					};
				if (product_id) {
					if (typeof wc_add_to_cart_params !== 'undefined') {
						ajax_url = wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'ideapark_ajax_product');
					} else {
						ajax_url = ideapark_wp_vars.ajaxUrl;
						data.action = 'ideapark_ajax_product';
					}
					
					$.ajax({
						type      : 'POST',
						url       : ajax_url,
						data      : data,
						dataType  : 'html',
						cache     : false,
						headers   : {'cache-control': 'no-cache'},
						beforeSend: function () {
							$button.ideapark_button('loading', 16, true);
						},
						success   : function (data) {
							$ideapark_quickview_container.html(data);
							var $currentContainer = $ideapark_quickview_popup.find('#product-' + product_id);
							if ($currentContainer.hasClass('product-type-variable')) {
								var $productForm = $currentContainer.find('form.cart');
								$productForm.wc_variation_form().find('.variations select:eq(0)').trigger('change');
							}
							ideapark_init_product_carousel();
							if (typeof IP_Wishlist !== 'undefined') {
								IP_Wishlist.init_product_button();
							}
							$('.c-header__callback-wrap').addClass('c-header__callback-wrap--quickview');
							$ideapark_quickview_popup.trigger('ip-open');
							$button.ideapark_button('reset');
							$('.c-play--disabled').removeClass('c-play--disabled');
							ideapark_init_zoom();
							ideapark_init_ajax_add_to_cart();
						}
					});
				}
			});
		
		ideapark_wpadminbar_resize();
		ideapark_scroll_actions();
		ideapark_resize_actions();
		ideapark_init_notice();
		ideapark_init_masonry();
		ideapark_init_subcat_carousel();
		ideapark_init_ajax_add_to_cart();
		ideapark_init_product_carousel();
		ideapark_init_product_thumbs_carousel();
		
		ideapark_scroll_action_add(function () {
			ideapark_to_top_button();
			ideapark_sticky_sidebar();
			ideapark_header_sticky();
			ideapark_advert_bar();
			ideapark_infinity_loading();
		});
		
		ideapark_resize_action_layout_add(function () {
			ideapark_search_popup(false);
			ideapark_header_sticky_init();
			ideapark_mobile_menu_popup(false);
			ideapark_init_mobile_menu();
			ideapark_init_shop_sidebar();
			ideapark_init_cart_sidebar();
			ideapark_sticky_sidebar();
			ideapark_header_sticky();
			ideapark_mega_menu_init();
			ideapark_init_zoom();
		});
		
		ideapark_defer_action_add(function () {
			if (typeof ideapark_redirect_url !== 'undefined' && ideapark_redirect_url) {
				location.href = ideapark_redirect_url;
				return;
			}
			ideapark_header_sticky_init(true);
			ideapark_header_sticky();
			ideapark_init_top_menu();
			ideapark_init_mobile_menu();
			ideapark_init_search();
			ideapark_init_zoom();
			ideapark_init_shop_sidebar();
			ideapark_init_cart_sidebar();
			ideapark_init_product_layout();
			ideapark_init_post_image_carousel();
			ideapark_init_tabs_carousel();
			ideapark_init_callback_popup();
			ideapark_init_review_placeholder();
			ideapark_grid_color_var_init();
			
			ideapark_resize_action_500_add(function () {
				ideapark_init_tabs_carousel();
				ideapark_init_masonry();
				ideapark_init_subcat_carousel();
				
				if (ideapark_is_mobile_layout) {
					$('.c-product-grid__thumb-wrap--hover').removeClass('c-product-grid__thumb-wrap--hover');
				}
			});
			
			$ideapark_infinity_loader = $('.js-load-infinity');
			ideapark_has_loader = $ideapark_infinity_loader.length || $('.js-load-more').length;
			$('.c-play--disabled').removeClass('c-play--disabled');
			$('.entry-content').fitVids();
			$(document.body)
				.trigger('ideapark.wpadminbar.scroll', ideapark_adminbar_visible_height)
				.trigger('theme_init');
		});
		
		if (!ideapark_wp_vars.jsDelay || ideapark_wp_vars.elementorPreview || ($window.width() >= 768 && $window.width() <= 1189)) {
			ideapark_defer_action_run();
		}
		
		$(document)
			.on('ideapark.wpadminbar.scroll ideapark.sticky ideapark.sticky.late', ideapark_set_notice_offset)
			.trigger('ideapark.wpadminbar.scroll', ideapark_adminbar_visible_height);
		
		$('body.h-preload').removeClass('h-preload');
	});
	
	root.ideapark_grid_color_var_init = function () {
		var ideapark_color_var_timeout = null;
		$('.js-grid-color-var').on('click', function () {
			if (ideapark_color_var_timeout !== null) {
				clearTimeout(ideapark_color_var_timeout);
				ideapark_color_var_timeout = null;
			}
			var $this = $(this);
			var $product = $this.closest('.c-product-grid__item');
			var $image = $product.find('.c-product-grid__thumb').first();
			if ($this.hasClass('current')) {
				$product.find('.c-product-grid__color-item.current').removeClass('current hover');
				$image.attr('src', $image.data('src'));
				$image.attr('srcset', $image.data('srcset'));
				$product.find('.c-product-grid__thumb-button-list').show();
				$product.find('.c-product-grid__atc').show();
				var $hover = $product.find('.c-product-grid__thumb--hover');
				if ($hover.length) {
					$hover.show();
					$image.addClass('c-product-grid__thumb--base');
				}
				return;
			}
			$product.find('.c-product-grid__thumb--hover').hide();
			$product.find('.c-product-grid__thumb--base').removeClass('c-product-grid__thumb--base');
			$product.find('.c-product-grid__color-item.current').removeClass('current hover');
			if (ideapark_is_mobile_layout || !$product.hasClass('c-product-grid__item--always')) {
				if (!(ideapark_is_mobile_layout && ($product.hasClass('c-product-grid__item--1-per-row') || window.innerWidth < 360 || window.innerWidth >= 620))) {
					$product.find('.c-product-grid__thumb-button-list').hide();
					$product.find('.c-product-grid__atc').hide();
				}
			}
			
			$this.addClass('current hover');
			if ($image.length) {
				if (typeof $image.data('src') === 'undefined') {
					$image.data('src', $image.attr('src'));
					$image.data('srcset', $image.attr('srcset'));
				}
				$image.attr('src', $this.data('src'));
				$image.attr('srcset', $this.data('srcset'));
			}
		}).on('mouseout', function () {
			var $this = $(this);
			$this.removeClass('hover');
			if ($this.hasClass('current') && ideapark_color_var_timeout === null) {
				ideapark_color_var_timeout = setTimeout(function () {
					var $product = $this.closest('.c-product-grid__item');
					$product.find('.c-product-grid__thumb-button-list').show();
					$product.find('.c-product-grid__atc').show();
					ideapark_color_var_timeout = null;
				}, 800);
			}
		});
		ideapark_resize_action_500_add(function () {
			$('.js-grid-color-var.hover').trigger('mouseout');
		});
	};
	
	root.ideapark_search_popup = function (show) {
		if (show && !ideapark_search_popup_active) {
			ideapark_mobile_menu_popup(false);
			ideapark_search_popup_active = true;
			$('.c-header-search').addClass('c-header-search--active');
			bodyScrollLock.disableBodyScroll($('.c-header-search__result')[0]);
		} else if (ideapark_search_popup_active) {
			ideapark_search_popup_active = false;
			$('.c-header-search').removeClass('c-header-search--active');
			bodyScrollLock.clearAllBodyScrollLocks();
		}
	};
	
	root.ideapark_init_top_menu = function () {
		var $ideapark_top_menu = $('.js-top-menu');
		
		if ($ideapark_top_menu.length) {
			$ideapark_top_menu.find('.c-top-menu__subitem--has-children').each(function () {
				var $li = $(this);
				if ($li.find('ul').length) {
					$li.append('<i class="ip-menu-right c-top-menu__more-svg"></i>');
				} else {
					$li.removeClass('c-top-menu__subitem--has-children');
				}
			});
			$ideapark_top_menu.find('a[href^="#"]').on('click', ideapark_hash_menu_animate);
		}
	};
	
	root.ideapark_advert_bar = function () {
		if (ideapark_sticky_mobile_init && ideapark_is_mobile_layout && $ideapark_mobile_sticky_row.length && ($ideapark_mobile_advert_bar_above.length || $ideapark_store_notice_top.length && $ideapark_store_notice_top.css('display') !== 'none')) {
			var scroll_top_mobile = window.scrollY;
			var advert_bar_height = ($ideapark_mobile_advert_bar_above.length ? $ideapark_mobile_advert_bar_above.outerHeight() : 0) + ($ideapark_store_notice_top.length && $ideapark_store_notice_top.css('display') !== 'none' ? $ideapark_store_notice_top.outerHeight() : 0);
			if (scroll_top_mobile - ideapark_adminbar_height < advert_bar_height && (!ideapark_adminbar_visible_height || ideapark_adminbar_position === 'fixed')) {
				$(document).trigger('ideapark.wpadminbar.scroll', ideapark_adminbar_visible_height);
			} else if (ideapark_mobile_advert_bar_above_delta > 0 && (!ideapark_adminbar_visible_height || ideapark_adminbar_position === 'fixed')) {
				$(document).trigger('ideapark.wpadminbar.scroll', ideapark_adminbar_visible_height);
			}
		}
	};
	
	root.ideapark_header_sticky_wpadmin = function (event, wpadminbar_height) {
		
		if (ideapark_sticky_desktop_init && !ideapark_is_mobile_layout && $ideapark_desktop_sticky_row.length) {
			$ideapark_desktop_sticky_row.css({
				top: wpadminbar_height + 'px'
			});
		}
		
		if (ideapark_sticky_mobile_init && ideapark_is_mobile_layout && $ideapark_mobile_sticky_row.length) {
			var top = wpadminbar_height;
			if ($ideapark_mobile_advert_bar_above.length || $ideapark_store_notice_top.length && $ideapark_store_notice_top.css('display') !== 'none') {
				var scroll_top_mobile = window.scrollY;
				var advert_bar_height = ($ideapark_mobile_advert_bar_above.length ? $ideapark_mobile_advert_bar_above.outerHeight() : 0) + ($ideapark_store_notice_top.length && $ideapark_store_notice_top.css('display') !== 'none' ? $ideapark_store_notice_top.outerHeight() : 0);
				if (scroll_top_mobile - ideapark_adminbar_height < advert_bar_height) {
					var delta;
					if (ideapark_adminbar_position === 'fixed') {
						delta = (advert_bar_height - scroll_top_mobile);
					} else if (top > 0) {
						delta = advert_bar_height;
					} else {
						delta = (advert_bar_height - scroll_top_mobile + ideapark_adminbar_height);
					}
					if (delta < 0) {
						delta = 0;
					}
					ideapark_mobile_advert_bar_above_delta = delta;
					top += delta;
				} else {
					ideapark_mobile_advert_bar_above_delta = 0;
				}
			}
			$ideapark_mobile_sticky_row.css({
				top: top + 'px'
			});
		}
	};
	
	root.ideapark_header_sticky_init = function (force) {
		var $inner = $('.l-inner').first();
		if (!$inner.length) {
			return;
		}
		ideapark_page_header_top = $ideapark_page_header.length ? $ideapark_page_header.offset().top : $inner.offset().top;
		ideapark_header_height = $ideapark_desktop_sticky_row.outerHeight();
		
		// Desktop
		if ((!ideapark_sticky_desktop_init || force) && !ideapark_is_mobile_layout && ideapark_wp_vars.stickyMenuDesktop && $ideapark_desktop_sticky_row.length) {
			ideapark_sticky_desktop_active = false;
			ideapark_sticky_desktop_init = true;
		}
		
		// Mobile
		if ((!ideapark_sticky_mobile_init || force) && ideapark_is_mobile_layout && ideapark_wp_vars.stickyMenuMobile) {
			ideapark_sticky_mobile_active = false;
			ideapark_sticky_mobile_init = true;
		}
		
		$(document).off('ideapark.wpadminbar.scroll', ideapark_header_sticky_wpadmin);
		$(document).on('ideapark.wpadminbar.scroll', ideapark_header_sticky_wpadmin);
	};
	
	root.ideapark_header_sticky = function () {
		var scroll_top, header_height, is_sticky_area;
		if (ideapark_wp_vars.stickyMenuDesktop && ideapark_sticky_desktop_init && !ideapark_is_mobile_layout) {
			if ($ideapark_desktop_sticky_row.length) {
				scroll_top = window.scrollY;
				if (ideapark_wp_vars.headerType == 'header-type-1' || ideapark_wp_vars.headerType == 'header-type-3' || ideapark_wp_vars.headerType == 'header-type-4' || ideapark_wp_vars.headerType == 'header-type-5') {
					is_sticky_area = scroll_top + ideapark_adminbar_height >= ideapark_page_header_top + (ideapark_wp_vars.headerType == 'header-type-1' || ideapark_wp_vars.headerType == 'header-type-3' ? ideapark_header_height : 0);
				} else if (ideapark_wp_vars.headerType == 'header-type-2') {
					header_height = ideapark_page_header_top + 30;
					if (header_height < 240) {
						header_height = 240;
					}
					is_sticky_area = scroll_top + ideapark_adminbar_height >= header_height;
					
				}
				if (ideapark_sticky_desktop_active) {
					if (!is_sticky_area) {
						var f = function () {
							f = null;
							setTimeout(function () {
								if (ideapark_wp_vars.headerType == 'header-type-2' || ideapark_wp_vars.headerType == 'header-type-4' || ideapark_wp_vars.headerType == 'header-type-5') {
									$ideapark_header_outer.css({'min-height': ''});
								}
							}, 500);
							$ideapark_desktop_sticky_row.removeClass('c-header--transition c-header--sticky');
						};
						var f2 = function () {
							if (f) {
								f();
							}
						};
						ideapark_on_transition_end_callback($ideapark_desktop_sticky_row, f2);
						setTimeout(f2, 500);
						$ideapark_desktop_sticky_row.removeClass('c-header--active');
						ideapark_sticky_desktop_active = false;
						$(document).trigger('ideapark.sticky');
						setTimeout(function () {
							$(document).trigger('ideapark.sticky.late');
						}, 600);
					}
				} else {
					if (is_sticky_area) {
						if (ideapark_wp_vars.headerType == 'header-type-2' || ideapark_wp_vars.headerType == 'header-type-4' || ideapark_wp_vars.headerType == 'header-type-5') {
							$ideapark_header_outer.css({'min-height': $ideapark_header_outer.outerHeight() + 'px'});
						}
						$ideapark_desktop_sticky_row.addClass('c-header--sticky');
						ideapark_sticky_desktop_active = true;
						setTimeout(function () {
							$ideapark_desktop_sticky_row.addClass('c-header--transition c-header--active');
						}, 50);
						$(document).trigger('ideapark.sticky');
						setTimeout(function () {
							$(document).trigger('ideapark.sticky.late');
						}, 600);
					}
				}
			}
		}
		if (ideapark_wp_vars.stickyMenuMobile && ideapark_sticky_mobile_init && ideapark_is_mobile_layout) {
			if ($ideapark_mobile_sticky_row.length) {
				scroll_top = window.scrollY;
				is_sticky_area = scroll_top > 5 && (!ideapark_adminbar_visible_height || ideapark_adminbar_position === 'fixed') && !ideapark_mobile_advert_bar_above_delta;
				
				if (ideapark_sticky_mobile_active) {
					if (!is_sticky_area) {
						$ideapark_mobile_sticky_row.removeClass('c-header--sticky');
						ideapark_sticky_mobile_active = false;
						$(document).trigger('ideapark.sticky');
						setTimeout(function () {
							$(document).trigger('ideapark.sticky.late');
						}, 600);
					}
				} else {
					if (is_sticky_area) {
						$ideapark_mobile_sticky_row.addClass('c-header--sticky');
						ideapark_sticky_mobile_active = true;
						$(document).trigger('ideapark.sticky');
						setTimeout(function () {
							$(document).trigger('ideapark.sticky.late');
						}, 600);
					}
				}
				$ideapark_mobile_sticky_row.addClass('c-header--init');
			}
		}
	};
	
	root.ideapark_init_search = function () {
		if (ideapark_search_popup_initialized) {
			return;
		}
		ideapark_search_popup_initialized = true;
		
		$('.js-ajax-search').each(function () {
			var $ideapark_search = $(this);
			var $ideapark_search_result = $('.js-ajax-search-result', $ideapark_search);
			var $ideapark_search_form = $('.js-search-form', $ideapark_search);
			var $ideapark_search_input = $('.js-ajax-search-input', $ideapark_search);
			var $ideapark_search_type = $('.js-ajax-search-type', $ideapark_search);
			var $ideapark_search_clear = $('.js-search-clear', $ideapark_search);
			var $ideapark_search_loader = $('<i class="h-loading c-header-search__loading"></i>');
			var ideapark_search_input_filled = false;
			var ajaxSearchFunction = ideapark_debounce(function () {
				var search = $ideapark_search_input.val().trim();
				if (ideapark_empty(search)) {
					$ideapark_search_result.html('');
				} else {
					$ideapark_search_loader.insertBefore($ideapark_search_input);
					$.ajax({
						url    : ideapark_wp_vars.ajaxUrl,
						type   : 'POST',
						data   : {
							action   : 'ideapark_ajax_search',
							s        : search,
							post_type: $ideapark_search_type.val(),
							lang     : $('input[name="lang"]', $ideapark_search_form).val()
						},
						success: function (results) {
							$ideapark_search_loader.remove();
							$ideapark_search_result.html((ideapark_empty($ideapark_search_input.val().trim())) ? '' : results);
						}
					});
				}
			}, 500);
			
			$ideapark_search_input.on('keydown', function (e) {
				var $this = $(this);
				var is_not_empty = !ideapark_empty($this.val().trim());
				
				if (e.keyCode == 13) {
					e.preventDefault();
					if ($this.hasClass('no-ajax') && is_not_empty) {
						$this.closest('form').submit();
					}
				} else if (e.keyCode == 27) {
					ideapark_search_popup(false);
				}
			}).on('input', function () {
				var $this = $(this);
				var is_not_empty = !ideapark_empty($this.val().trim());
				
				if (is_not_empty && !ideapark_search_input_filled) {
					ideapark_search_input_filled = true;
					$('.js-search-clear').addClass('active');
					
				} else if (!is_not_empty && ideapark_search_input_filled) {
					ideapark_search_input_filled = false;
					$('.js-search-clear').removeClass('active');
				}
				ajaxSearchFunction();
			});
			
			$ideapark_search_clear.on('click', function () {
				$ideapark_search_input.val('').trigger('input').trigger('focus');
			});
			
			$ideapark_search.removeClass('disabled');
		});
		
		$('.js-search-to-top').on('click', function () {
			$('html, body').animate({scrollTop: 0}, 800, function () {
				$('.c-header__search-input').trigger('focus');
			});
		});
		
		$('.js-search-button').on('click', function () {
			ideapark_search_popup(true);
			setTimeout(function () {
				$('.c-header-search__input').trigger('focus');
			}, 500);
		});
		
		$('.js-search-close').on('click', function () {
			if (ideapark_search_popup_active) {
				ideapark_on_transition_end_callback($('.c-header-search'), function () {
					$('.c-header-search__input').val('').trigger('input').trigger('focus');
				});
				ideapark_search_popup(false);
			}
		});
		
		$(document).on('ideapark.wpadminbar.scroll', function (event, wpadminbar_height) {
			$('.c-header-search').css({
				transform   : 'translateY(' + wpadminbar_height + 'px)',
				'max-height': 'calc(100% - ' + wpadminbar_height + 'px)'
			});
		});
	};
	
	root.ideapark_mobile_menu_popup = function (show) {
		if (ideapark_mobile_menu_initialized) {
			if (show && !ideapark_mobile_menu_active) {
				ideapark_mobile_menu_active = true;
				$ideapark_mobile_menu.addClass('c-header__menu--active');
			} else if (ideapark_mobile_menu_active) {
				ideapark_mobile_menu_active = false;
				$ideapark_mobile_menu.removeClass('c-header__menu--active');
				bodyScrollLock.clearAllBodyScrollLocks();
			}
		}
	};
	
	root.ideapark_init_mobile_menu = function () {
		if (ideapark_is_mobile_layout && !ideapark_mobile_menu_initialized && $ideapark_mobile_menu.length) {
			ideapark_mobile_menu_initialized = true;
			
			var $wrap = $('.js-mobile-menu-wrap');
			var $back = $('.js-mobile-menu-back');
			var action_lock = false;
			var ideapark_mobile_menu_init_page = function (page, $ul) {
				var $page = $('<div class="c-header__menu-page js-menu-page" data-page="' + page + '"></div>');
				var $ul_new = $ul.clone();
				if (!page) {
					var $li = $('<li></li>');
					$('.js-mobile-blocks').clone().removeClass('js-mobile-blocks').appendTo($li);
					$li.appendTo($ul_new);
				}
				$ul_new.appendTo($page);
				$page.appendTo($wrap);
			};
			var ideapark_mobile_menu_scroll_lock = function () {
				var $submenu = $('.js-menu-page[data-page="' + ideapark_mobile_menu_page + '"]');
				bodyScrollLock.clearAllBodyScrollLocks();
				bodyScrollLock.disableBodyScroll($submenu[0]);
			};
			
			$(document).on('ideapark.wpadminbar.scroll', function (event, wpadminbar_height) {
				$ideapark_mobile_menu.css({
					transform   : 'translateY(' + wpadminbar_height + 'px)',
					'max-height': 'calc(100% - ' + wpadminbar_height + 'px)'
				});
			});
			
			$ideapark_mobile_menu.find('.c-mobile-menu__item--has-children, .c-mobile-menu__subitem--has-children').each(function () {
				var $li = $(this);
				var $a = $li.children('a').first();
				var $ul_submenu = $li.children('.c-mobile-menu__submenu').first();
				if ($a.length && $ul_submenu.length) {
					if ($a.attr('href') != '#' && $a.attr('href')) {
						var $li_new = $ul_submenu.prop("tagName") == 'UL' ?
							$('<li class="c-mobile-menu__subitem c-mobile-menu__subitem--parent"></li>') :
							$('<div class="c-mobile-menu__subitem c-mobile-menu__subitem--parent c-mobile-menu__subitem--parent-div"></div>');
						$a.clone().appendTo($li_new);
						$ul_submenu.prepend($li_new);
					}
				}
			});
			
			$(document.body).on('click', '.c-mobile-menu__item--has-children > a:first-child, .c-mobile-menu__subitem--has-children > a:first-child', function (e) {
				e.preventDefault();
				if (action_lock) {
					return;
				}
				action_lock = true;
				var $submenu = $(this).closest('li').children('.c-mobile-menu__submenu');
				ideapark_mobile_menu_page++;
				ideapark_mobile_menu_init_page(ideapark_mobile_menu_page, $submenu);
				ideapark_on_transition_end_callback($wrap, function () {
					action_lock = false;
				});
				$wrap.addClass('c-header__menu-wrap--page-' + ideapark_mobile_menu_page);
				$back.addClass('c-header__menu-back--active');
				ideapark_mobile_menu_scroll_lock();
			});
			
			$back.on('click', function () {
				if (action_lock || ideapark_mobile_menu_page <= 0) {
					return;
				}
				action_lock = true;
				ideapark_on_transition_end_callback($wrap, function () {
					$('.js-menu-page[data-page="' + ideapark_mobile_menu_page + '"]').remove();
					ideapark_mobile_menu_page--;
					if (!ideapark_mobile_menu_page) {
						$back.removeClass('c-header__menu-back--active');
					}
					ideapark_mobile_menu_scroll_lock();
					action_lock = false;
				});
				$wrap.removeClass('c-header__menu-wrap--page-' + ideapark_mobile_menu_page);
			});
			
			$('.js-mobile-menu-open').on('click', function () {
				if (ideapark_mobile_menu_page === -1) {
					ideapark_mobile_menu_page = 0;
					ideapark_mobile_menu_init_page(ideapark_mobile_menu_page, $('.c-mobile-menu__list'));
				}
				ideapark_mobile_menu_popup(true);
				ideapark_mobile_menu_scroll_lock();
			});
			
			$('.js-mobile-menu-close').on('click', function () {
				ideapark_mobile_menu_popup(false);
			});
		}
	};
	
	root.ideapark_sidebar_popup = function (show) {
		if (ideapark_shop_sidebar_initialized) {
			if (show && !ideapark_shop_sidebar_active) {
				ideapark_shop_sidebar_active = true;
				$ideapark_shop_sidebar.addClass('c-shop-sidebar--active');
				$ideapark_shop_sidebar_wrap.addClass('c-shop-sidebar__wrap--active');
				$('body').addClass('filter-open');
				// bodyScrollLock.disableBodyScroll($('.js-shop-sidebar-content')[0]);
			} else if (ideapark_shop_sidebar_active) {
				ideapark_shop_sidebar_active = false;
				$ideapark_shop_sidebar.removeClass('c-shop-sidebar--active');
				$ideapark_shop_sidebar_wrap.removeClass('c-shop-sidebar__wrap--active');
				$('body').removeClass('filter-open');
				// bodyScrollLock.clearAllBodyScrollLocks();
			}
		}
	};
	
	root.ideapark_init_shop_sidebar = function () {
		if ((ideapark_is_mobile_layout || !ideapark_is_mobile_layout && ideapark_shop_sidebar_filter_desktop) && !ideapark_shop_sidebar_initialized && $ideapark_shop_sidebar.length) {
			ideapark_shop_sidebar_initialized = true;
			
			$(document).on('ideapark.wpadminbar.scroll', function (event, wpadminbar_height) {
				if (ideapark_is_mobile_layout || ideapark_shop_sidebar_filter_desktop) {
					$ideapark_shop_sidebar.css({
						transform   : 'translateY(' + wpadminbar_height + 'px)',
						'max-height': 'calc(100% - ' + wpadminbar_height + 'px)'
					});
				} else {
					$ideapark_shop_sidebar.css({
						transform   : '',
						'max-height': ''
					});
				}
			});
			$('.js-filter-show-button').on('click', function () {
				ideapark_sidebar_popup(true);
			});
			
			$('.js-filter-close-button').on('click', function () {
				ideapark_sidebar_popup(false);
			});
		}
	};
	
	root.ideapark_cart_sidebar_popup = function (show) {
		if (ideapark_cart_sidebar_initialized) {
			if (show && !ideapark_cart_sidebar_active) {
				ideapark_cart_sidebar_active = true;
				$ideapark_cart_sidebar.addClass('c-shop-sidebar--active');
				$ideapark_cart_sidebar_wrap.addClass('c-shop-sidebar__wrap--active');
			} else if (ideapark_cart_sidebar_active) {
				ideapark_cart_sidebar_active = false;
				$ideapark_cart_sidebar.removeClass('c-shop-sidebar--active');
				$ideapark_cart_sidebar_wrap.removeClass('c-shop-sidebar__wrap--active');
			}
		}
	};
	
	root.ideapark_init_cart_sidebar = function () {
		
		if ((ideapark_is_mobile_layout || ideapark_wp_vars.popupCartLayout === 'sidebar') && !ideapark_cart_sidebar_initialized && $ideapark_cart_sidebar.length) {
			ideapark_cart_sidebar_initialized = true;
			
			$(document).on('ideapark.wpadminbar.scroll', function (event, wpadminbar_height) {
				if (ideapark_is_mobile_layout || ideapark_wp_vars.popupCartLayout === 'sidebar') {
					$ideapark_cart_sidebar.css({
						transform   : 'translateY(' + wpadminbar_height + 'px)',
						'max-height': 'calc(100% - ' + wpadminbar_height + 'px)'
					});
				}
			});
			$('.js-cart-sidebar-open').on('click', function (e) {
				e.preventDefault();
				ideapark_cart_sidebar_popup(true);
			});
			
			$('.js-cart-sidebar-close').on('click', function () {
				ideapark_cart_sidebar_popup(false);
			});
		}
	};
	
	root.ideapark_init_post_image_carousel = function () {
		$('.js-post-image-carousel:not(.owl-carousel)')
			.each(function () {
				var $this = $(this);
				$this
					.addClass('owl-carousel')
					.on('resized.owl.carousel', ideapark_owl_hide_arrows)
					.owlCarousel({
						items        : 1,
						center       : false,
						autoWidth    : false,
						loop         : false,
						margin       : 0,
						rtl          : !!ideapark_wp_vars.isRtl,
						nav          : !$this.hasClass('h-carousel--nav-hide'),
						dots         : !$this.hasClass('h-carousel--dots-hide'),
						navText      : ideapark_nav_text,
						onInitialized: ideapark_owl_hide_arrows
					});
			});
	};
	
	root.ideapark_init_product_carousel = function () {
		$('.js-single-product-carousel:not(.owl-carousel)')
			.each(function () {
				var $this = $(this);
				var is_zoom = !!$this.find(".js-product-zoom").length;
				var is_zoom_mobile_hide = !!$this.find(".js-product-zoom--mobile-hide").length;
				if ($this.children().length > 1) {
					$this
						.addClass('owl-carousel')
						.on('resized.owl.carousel', ideapark_owl_hide_arrows)
						.owlCarousel({
							items        : 1,
							center       : false,
							autoHeight   : true,
							loop         : false,
							mouseDrag    : !is_zoom,
							touchDrag    : !is_zoom || is_zoom_mobile_hide,
							margin       : 0,
							rtl          : !!ideapark_wp_vars.isRtl,
							nav          : !$this.hasClass('h-carousel--nav-hide'),
							dots         : !$this.hasClass('h-carousel--dots-hide'),
							navText      : ideapark_nav_text,
							onInitialized: ideapark_owl_hide_arrows
						})
						.on('changed.owl.carousel', function (event) {
							var currentItem = event.item.index;
							$('.c-product__thumbs-item.active').removeClass('active');
							$('.c-product__thumbs-item').eq(currentItem).addClass('active');
							$('.js-product-thumbs-carousel').trigger('to.owl.carousel', [currentItem, 300]);
						});
				}
			});
	};
	
	root.ideapark_init_product_thumbs_carousel = function () {
		$('.js-product-thumbs-carousel:not(.owl-carousel)').each(function () {
			var $this = $(this);
			$this
				.addClass('owl-carousel')
				.on('resized.owl.carousel', ideapark_owl_hide_arrows)
				.owlCarousel({
					center       : false,
					loop         : false,
					margin       : 0,
					autoWidth    : true,
					items        : 1,
					rtl          : !!ideapark_wp_vars.isRtl,
					nav          : !$(this).hasClass('h-carousel--nav-hide'),
					dots         : !$(this).hasClass('h-carousel--dots-hide'),
					navText      : ideapark_nav_text,
					onInitialized: ideapark_owl_hide_arrows
				});
			$('.js-single-product-thumb:not(.init)', $(this)).addClass('init').on('click', function () {
				var index = $(this).data('index');
				var $item = $(this).closest('.c-product__thumbs-item');
				$('.c-product__thumbs-item.active').removeClass('active');
				$item.addClass('active');
				$('.js-single-product-carousel').trigger("to.owl.carousel", [index, 300]);
			});
		});
	};
	
	root.ideapark_init_tabs_carousel = function () {
		$('.js-tabs-list:not(.owl-carousel)').each(function () {
			var $this = $(this);
			var container_width = $this.closest('.c-product__tabs-wrap').outerWidth();
			var items_width = -46;
			$this.find('.c-product__tabs-item').each(function () {
				items_width += $(this).outerWidth() + 46;
			});
			if (items_width >= container_width) {
				$(this)
					.addClass('owl-carousel')
					.owlCarousel({
						center   : false,
						loop     : false,
						margin   : 46,
						autoWidth: true,
						items    : 1,
						rtl      : !!ideapark_wp_vars.isRtl,
						nav      : !$(this).hasClass('h-carousel--nav-hide'),
						dots     : !$(this).hasClass('h-carousel--dots-hide'),
						navText  : ideapark_nav_text
					});
				
				$('.js-tabs-item-link', $(this)).on('click', function () {
					var $this = $(this);
					var index = $this.data('index');
					$this.closest('.js-tabs-list').trigger("to.owl.carousel", [index, 300]);
				});
			}
		});
	};
	
	root.ideapark_init_auto_select_width = function () {
		var f = function () {
			var $this = $(this);
			var value = $this.val();
			var $cloned = $this.clone();
			$cloned.css({width: 'auto'});
			$cloned.addClass('h-invisible-total');
			$cloned.find('option:not([value=' + value + '])').remove();
			$this.after($cloned);
			var width = $cloned.outerWidth();
			$cloned.remove();
			$this.css({width: width + 'px'});
		};
		$('select.js-auto-width:not(.init)').on('change', f).each(f).addClass('init');
	};
	
	root.ideapark_to_top_button = function () {
		if ($ideapark_to_top_button.length) {
			if ($window.scrollTop() > 500) {
				if (!$ideapark_to_top_button.hasClass('c-to-top-button--active')) {
					$ideapark_to_top_button.addClass('c-to-top-button--active');
				}
			} else {
				if ($ideapark_to_top_button.hasClass('c-to-top-button--active')) {
					$ideapark_to_top_button.removeClass('c-to-top-button--active');
				}
			}
		}
	};
	
	root.ideapark_sticky_sidebar = function () {
		
		if (ideapark_wp_vars.stickySidebar && $ideapark_sticky_sidebar.length && $ideapark_sticky_sidebar_nearby.length) {
			
			var sb = $ideapark_sticky_sidebar;
			var content = $ideapark_sticky_sidebar_nearby;
			var is_disable_transition = false;
			var is_enable_transition = false;
			
			if (ideapark_is_mobile_layout) {
				
				if (ideapark_sticky_sidebar_old_style !== null) {
					sb.attr('style', ideapark_sticky_sidebar_old_style);
					ideapark_sticky_sidebar_old_style = null;
				}
				
			} else {
				
				var sb_height = sb.outerHeight(true);
				var content_height = content.outerHeight(true);
				var content_top = content.offset().top;
				var scroll_offset = $window.scrollTop();
				var window_width = $window.width();
				
				var top_panel_fixed_height = ideapark_sticky_desktop_active ? $ideapark_desktop_sticky_row.outerHeight() + ideapark_adminbar_visible_height + 25 : ideapark_adminbar_visible_height;
				
				if (sb_height < content_height && scroll_offset + top_panel_fixed_height > content_top) {
					
					var sb_init = {
						'position': 'undefined',
						'float'   : 'none',
						'top'     : 'auto',
						'bottom'  : 'auto'
					};
					
					if (typeof ideapark_scroll_offset_last == 'undefined') {
						root.ideapark_sb_top_last = content_top;
						root.ideapark_scroll_offset_last = scroll_offset;
						root.ideapark_scroll_dir_last = 1;
						root.ideapark_window_width_last = window_width;
					}
					
					var scroll_dir = scroll_offset - ideapark_scroll_offset_last;
					if (scroll_dir === 0) {
						scroll_dir = ideapark_scroll_dir_last;
					} else {
						scroll_dir = scroll_dir > 0 ? 1 : -1;
					}
					
					var sb_big = sb_height + 30 >= $window.height() - top_panel_fixed_height,
						sb_top = sb.offset().top;
					
					if (sb_top < 0) {
						sb_top = ideapark_sb_top_last;
					}
					
					if (sb_big) {
						
						if (scroll_dir != ideapark_scroll_dir_last && sb.css('position') == 'fixed') {
							sb_init.top = sb_top - content_top;
							sb_init.position = 'absolute';
							
						} else if (scroll_dir > 0) {
							if (scroll_offset + $window.height() >= content_top + content_height + 30) {
								if (ideapark_is_sticky_sidebar_inner || ideapark_has_loader) {
									sb_init.top = (content_height - sb_height) + 'px';
									is_disable_transition = true;
								} else {
									sb_init.bottom = 0;
								}
								sb_init.position = 'absolute';
								
							} else if (scroll_offset + $window.height() >= (sb.css('position') == 'absolute' ? sb_top : content_top) + sb_height + 30) {
								sb_init.bottom = 30;
								sb_init.position = 'fixed';
								is_enable_transition = true;
							}
							
						} else {
							
							if (scroll_offset + top_panel_fixed_height <= sb_top) {
								sb_init.top = top_panel_fixed_height;
								sb_init.position = 'fixed';
								is_enable_transition = true;
							}
						}
						
					} else {
						if (scroll_offset + top_panel_fixed_height >= content_top + content_height - sb_height) {
							if (ideapark_is_sticky_sidebar_inner || ideapark_has_loader) {
								sb_init.top = (content_height - sb_height) + 'px';
								is_disable_transition = true;
								
							} else {
								sb_init.bottom = 0;
							}
							sb_init.position = 'absolute';
						} else {
							sb_init.top = top_panel_fixed_height;
							sb_init.position = 'fixed';
							is_enable_transition = true;
						}
					}
					
					if (is_disable_transition) {
						is_disable_transition = false;
						sb.addClass('js-sticky-sidebar--disable-transition');
					}
					
					if (sb_init.position != 'undefined') {
						
						if (sb.css('position') != sb_init.position || ideapark_scroll_dir_last != scroll_dir || ideapark_window_width_last != window_width) {
							
							root.ideapark_window_width_last = window_width;
							sb_init.width = sb.parent().width();
							
							if (ideapark_sticky_sidebar_old_style === null) {
								var style = sb.attr('style');
								if (!style) {
									style = '';
								}
								ideapark_sticky_sidebar_old_style = style;
							}
							sb.css(sb_init);
						}
					}
					
					if (is_enable_transition) {
						is_enable_transition = false;
						setTimeout(function () {
							sb.removeClass('js-sticky-sidebar--disable-transition');
						}, 20);
					}
					
					root.ideapark_sb_top_last = sb_top;
					root.ideapark_scroll_offset_last = scroll_offset;
					root.ideapark_scroll_dir_last = scroll_dir;
					
				} else {
					if (ideapark_sticky_sidebar_old_style !== null) {
						sb.attr('style', ideapark_sticky_sidebar_old_style);
						ideapark_sticky_sidebar_old_style = null;
					}
					setTimeout(function () {
						sb.removeClass('js-sticky-sidebar--disable-transition');
					}, 20);
				}
			}
			
		}
	};
	
	root.ideapark_hash_menu_animate = function (e) {
		if (typeof ideapark_hash_menu_animate.cnt === 'undefined') {
			ideapark_hash_menu_animate.cnt = 0;
		} else {
			ideapark_hash_menu_animate.cnt ++;
		}
		var $this = $(this), $el;
		if (ideapark_isset(e)) {
			e.preventDefault();
			$this = $(e.target);
		}
		var element_selector = $this.attr('href');
		if ($('.c-product--layout-1').length && $('.c-product__tabs--wide').length == 0 && element_selector == '#reviews') {
			element_selector = ideapark_is_mobile_layout ? '.c-product__tabs--mobile .woocommerce-Reviews' : '.c-product__tabs--desktop .woocommerce-Reviews';
		}
		if (typeof element_selector !== 'undefined' && element_selector.length > 1 && ($el = $(element_selector)) && $el.length) {
			if ($el.offset().top == 0 && ideapark_hash_menu_animate.cnt < 5) {
				setTimeout(function () {
					ideapark_hash_menu_animate(e);
				}, 100);
				return;
			}
			var offset = $el.offset().top - 25 - (ideapark_adminbar_position === 'fixed' ? ideapark_adminbar_height : 0);
			if (ideapark_is_mobile_layout) {
				ideapark_mobile_menu_popup(false);
				if ($ideapark_mobile_sticky_row.length) {
					offset -= $ideapark_mobile_sticky_row.outerHeight();
				}
			} else if (ideapark_sticky_desktop_init && $ideapark_desktop_sticky_row.length) {
				offset -= $ideapark_desktop_sticky_row.outerHeight();
			}
			$('html, body').animate({scrollTop: offset}, 800);
		}
	};
	
	root.ideapark_owl_hide_arrows = function (event) {
		var $element;
		if (event instanceof jQuery) {
			$element = event;
		} else {
			$element = $(event.target);
		}
		var $prev = $element.find('.owl-prev');
		var $next = $element.find('.owl-next');
		var dot_count = $element.find('.owl-dot').length;
		if (!$element.hasClass('h-carousel--dots-hide')) {
			if (dot_count > 1) {
				$element.find('.owl-dots').removeClass('disabled');
			} else {
				$element.find('.owl-dots').addClass('disabled');
			}
		}
		if (!$element.hasClass('h-carousel--nav-hide')) {
			$element.find('.owl-nav').removeClass('disabled');
			if ($prev.length && $next.length) {
				if ($prev.hasClass('disabled') && $next.hasClass('disabled')) {
					$prev.addClass('h-hidden');
					$next.addClass('h-hidden');
					$element.find('.owl-nav').addClass('disabled');
				} else {
					$prev.removeClass('h-hidden');
					$next.removeClass('h-hidden');
				}
			}
		}
	};
	
	root.ideapark_init_product_layout = function () {
		var $layout_1 = $('.c-product--layout-1');
		if ($layout_1.length) {
			$('.c-product__tabs:not(.c-product__tabs--wide)')
				.clone()
				.removeClass('c-product__tabs--desktop')
				.addClass('c-product__tabs--mobile')
				.insertAfter($('.c-product__col-2'));
		}
	};
	
	root.ideapark_set_notice_offset = function (offset) {
		var $notice = $('.woocommerce-notices-wrapper--ajax');
		if ($notice.length) {
			if (typeof offset !== 'number') {
				offset = ideapark_adminbar_visible_height;
				if ((ideapark_sticky_mobile_active || !$ideapark_store_notice_top.length && !$ideapark_mobile_advert_bar_above.length) && $window.width() < 768) {
					offset += $ideapark_mobile_sticky_row.outerHeight();
				}
			}
			$notice.css({
				transform: 'translateY(' + offset + 'px)'
			});
		}
	};
	
	root.ideapark_init_notice = function () {
		var $n1, $n2;
		var $wrapper_main = $('.woocommerce-notices-wrapper--ajax');
		if (!$wrapper_main.length) {
			$wrapper_main = $('<div class="woocommerce-notices-wrapper woocommerce-notices-wrapper--ajax"></div>');
			$('body').append($wrapper_main);
		}
		$('.woocommerce-notices-wrapper:not(.woocommerce-notices-wrapper--ajax)').each(function () {
			var $wrapper = $(this);
			if ($wrapper.text().trim() != '') {
				$n1 = $wrapper.find('.woocommerce-notice').detach();
				if ($n1 && $n1.length) {
					ideapark_show_notice($n1);
				}
			}
			$wrapper.remove();
		});
		
		$n2 = $('.woocommerce .woocommerce-notice').detach();
		if ($n2 && $n2.length) {
			ideapark_show_notice($n2);
		}
	};
	
	root.ideapark_search_notice = function () {
		var $notices;
		$('.woocommerce-notices-wrapper:not(.woocommerce-notices-wrapper--ajax)').each(function () {
			var $wrapper = $(this);
			if ($wrapper.text().trim() != '') {
				$notices = $wrapper.find('.woocommerce-notice').detach();
				if ($notices && $notices.length) {
					ideapark_show_notice($notices);
				}
			}
			$wrapper.remove();
		});
		$notices = $('div.woocommerce-notice:not(.shown), div.woocommerce-error:not(.shown), div.woocommerce-message:not(.shown)');
		if ($notices.length) {
			$notices.detach();
			ideapark_show_notice($notices);
		}
	};
	
	root.ideapark_show_notice = function (notice) {
		if (ideapark_empty(notice)) {
			return;
		}
		ideapark_set_notice_offset();
		var $wrapper = $('.woocommerce-notices-wrapper');
		var $notices = notice instanceof jQuery ? notice : $(notice);
		var is_new = !$wrapper.find('.woocommerce-notice').length;
		if (is_new) {
			$wrapper.css({display: 'none'});
		}
		$notices.addClass('shown');
		$wrapper.append($notices);
		if (is_new) {
			var dif = $wrapper.outerHeight() + 150;
			var top_orig = ideapark_is_mobile_layout ? 0 : parseInt($wrapper.css('top').replace('px', ''));
			$wrapper.css({top: (top_orig - dif) + 'px'});
			$wrapper.css({display: ''});
			$({y: top_orig}).animate({y: top_orig + dif}, {
				step    : function (y) {
					$wrapper.css({
						top: (y - dif) + 'px',
					});
				},
				duration: 500,
				complete: function () {
					$wrapper.css({
						top: '',
					});
					$wrapper.addClass('woocommerce-notices-wrapper--transition');
				}
			});
		}
		
		$notices.find('.js-notice-close').each(function () {
			var $close = $(this);
			setTimeout(function () {
				$close.trigger('click');
			}, 5000);
		});
	};
	
	root.ideapark_show_notice_error = function (message) {
		ideapark_show_notice($('<div class="woocommerce-notice  shown" role="alert">\n' +
			'\t\t<i class="ip-wc-error woocommerce-notice-error-svg"></i>\n' +
			'\t\t' + message + '\t\t<button class="h-cb h-cb--svg woocommerce-notice-close js-notice-close"><i class="ip-close-small woocommerce-notice-close-svg"></i></button>\n' +
			'\t</div>'));
	};
	
	root.ideapark_init_callback_popup = function () {
		var $ideapark_callback_popup = $('.js-callback-popup');
		if ($ideapark_callback_popup.length) {
			
			$ideapark_callback_popup.each(function () {
				var $popup = $(this);
				
				var open_popup = function (e) {
					e.preventDefault();
					ideapark_mobile_menu_popup(false);
					$popup.removeClass('c-header__callback-popup--disabled');
					setTimeout(function () {
						$popup.addClass('c-header__callback-popup--active');
					}, 20);
					bodyScrollLock.disableBodyScroll($('.c-header__callback-wrap', $popup)[0]);
				};
				
				$popup.on('ip-open', open_popup);
				
				if ($popup.data('button')) {
					$(document).on('click', $popup.data('button'), open_popup);
				}
				
				$('.js-callback-close', $popup).on('click', function () {
					if ($popup.hasClass('c-header__callback-popup--active')) {
						ideapark_on_transition_end_callback($popup, function () {
							$('.c-header__callback-wrap').attr('class', 'c-header__callback-wrap');
							$popup.addClass('c-header__callback-popup--disabled');
						});
						$popup.toggleClass('c-header__callback-popup--active');
						
						bodyScrollLock.clearAllBodyScrollLocks();
					}
				});
				
				$(document).on('ideapark.wpadminbar.scroll', function (event, wpadminbar_height) {
					$popup.css({
						transform   : 'translateY(' + wpadminbar_height + 'px)',
						'max-height': 'calc(100% - ' + wpadminbar_height + 'px)'
					});
				});
			});
		}
	};
	
	root.ideapark_init_review_placeholder = function () {
		$('#reviews #commentform textarea, #reviews #commentform input, .woocommerce-Input--text').each(function () {
			var $this = $(this);
			var $label = $this.parent().find('label');
			if ($label.length) {
				$this.attr('placeholder', $label.text());
			}
		});
	};
	
	root.ideapark_init_masonry = function () {
		if (ideapark_masonry_grid_on || ideapark_masonry_sidebar_on) {
			var window_width = $window.width();
			var is_sidebar_masonry_width = window_width >= 630 && window_width <= 1189;
			if (!ideapark_is_masonry_init) {
				
				ideapark_is_masonry_init = true;
				
				if (ideapark_masonry_grid_on) {
					$ideapark_masonry_grid.addClass('js-masonry');
				}
				
				var init_f = function () {
					if (ideapark_masonry_sidebar_on && is_sidebar_masonry_width) {
						ideapark_masonry_sidebar_object = new Masonry($ideapark_masonry_sidebar[0], {
							itemSelector   : '.widget',
							percentPosition: true
						});
						$ideapark_masonry_sidebar.addClass('init-masonry');
					}
				};
				
				if (typeof root.Masonry !== 'undefined') {
					init_f();
				} else {
					require([
						ideapark_wp_vars.masonryUrl
					], function () {
						init_f();
					});
				}
			} else {
				if (ideapark_masonry_sidebar_on) {
					var is_init = $ideapark_masonry_sidebar.hasClass('init-masonry');
					if (is_sidebar_masonry_width && !is_init) {
						ideapark_masonry_sidebar_object = new Masonry($ideapark_masonry_sidebar[0], {
							itemSelector   : '.widget',
							percentPosition: true
						});
						$ideapark_masonry_sidebar.addClass('init-masonry');
					} else if (!is_sidebar_masonry_width && is_init) {
						ideapark_masonry_sidebar_object.destroy();
						ideapark_masonry_sidebar_object = null;
						$ideapark_masonry_sidebar.removeClass('init-masonry');
						setTimeout(function () {
							$ideapark_masonry_sidebar.find('.widget').css({left: '', top: ''});
						}, 300);
					}
				}
			}
		}
	};
	
	root.ideapark_menu_fix_position = function ($ul) {
		if (!ideapark_is_mobile_layout && $ideapark_simple_container.length) {
			var delta;
			var window_width = $window.width();
			var is_fullwidth = !!$('.c-header--header-type-5').length;
			var container_width = is_fullwidth ? window_width - 60 : 1160;
			var container_left = is_fullwidth ? 30 : $ideapark_simple_container.offset().left;
			var container_right = container_left + container_width;
			var ul_left = $ul.offset().left;
			var ul_right = ul_left + $ul.outerWidth();
			
			if (ul_left < container_left) {
				if (ideapark_wp_vars.isRtl) {
					delta = Math.round(parseInt($ul.css('right').replace('px', '')) - container_left + ul_left);
					$ul.css({
						right: delta
					});
				} else {
					delta = Math.round(parseInt($ul.css('left').replace('px', '')) + container_left - ul_left);
					$ul.css({
						left: delta
					});
				}
				
			}
			if (ul_right > container_right) {
				if (ideapark_wp_vars.isRtl) {
					delta = Math.round(parseInt($ul.css('right').replace('px', '')) + ul_right - container_right);
					$ul.css({
						right: delta
					});
				} else {
					delta = Math.round(parseInt($ul.css('left').replace('px', '')) - ul_right + container_right);
					$ul.css({
						left: delta
					});
				}
			}
		}
	};
	
	root.ideapark_mega_menu_init = function () {
		if (!ideapark_is_mobile_layout && ideapark_mega_menu_initialized === 0 && ideapark_all_is_loaded) {
			var window_width = $window.width();
			
			$('.c-top-menu__submenu--columns-1').addClass('initialized').closest('li').addClass('initialized');
			
			var main_items = $('.c-top-menu__submenu--columns-2, .c-top-menu__submenu--columns-3, .c-top-menu__submenu--columns-4');
			if (main_items.length) {
				main_items.each(function () {
					
					var $ul_main = $(this);
					var cols = 1;
					if ($ul_main.hasClass('c-top-menu__submenu--columns-2')) {
						cols = 2;
					} else if ($ul_main.hasClass('c-top-menu__submenu--columns-3')) {
						cols = 3;
					} else if ($ul_main.hasClass('c-top-menu__submenu--columns-4')) {
						cols = 4;
					}
					var $ul = $ul_main;
					var padding_top = $ul.css('padding-top') ? parseInt($ul.css('padding-top').replace('px', '')) : 0;
					var padding_bottom = $ul.css('padding-bottom') ? parseInt($ul.css('padding-bottom').replace('px', '')) : 0;
					var heights = [];
					var max_height = 0;
					var all_sum_height = 0;
					$ul.children('li').each(function () {
						var $li = $(this);
						var height = $li.outerHeight();
						if (height > max_height) {
							max_height = height;
						}
						all_sum_height += height;
						heights.push(height);
					});
					var test_cols = 0;
					var cnt = 0;
					var test_height = max_height - 1;
					do {
						test_height++;
						cnt++;
						test_cols = 1;
						var sum_height = 0;
						for (var i = 0; i < heights.length; i++) {
							sum_height += heights[i];
							if (sum_height > test_height) {
								sum_height = 0;
								i--;
								test_cols++;
							}
						}
					} while (test_cols > cols && cnt < 1000);
					
					if (test_cols <= cols && test_height > 0) {
						$ul.css({height: (test_height + padding_top + padding_bottom) + 'px'}).addClass('mega-menu-break');
					}
					
					ideapark_menu_fix_position($ul);
					ideapark_resize_action_500_add(function () {
						ideapark_menu_fix_position($ul);
					});
					
					$ul_main.addClass('initialized');
					$ul_main.closest('li').addClass('initialized');
				});
			}
			
			$('.c-top-menu__submenu--inner').each(function () {
				var $ul = $(this);
				var cond = ideapark_wp_vars.isRtl ? ($ul.offset().left < 0) : ($ul.offset().left + $ul.width() > window_width);
				if (cond) {
					$ul.addClass('c-top-menu__submenu--rtl');
					$ul.closest('li').find('.c-top-menu__more-svg').addClass('c-top-menu__more-svg--rtl');
				}
			});
			
			$('.c-top-bar-menu__submenu,.wpml-ls-sub-menu').each(function () {
				var $ul = $(this);
				var cond = ideapark_wp_vars.isRtl ? ($ul.offset().left < 0) : ($ul.offset().left + $ul.width() > window_width);
				if (cond) {
					$ul.addClass('c-top-bar-menu__submenu--rtl');
					$ul.closest('.c-top-bar-menu__subitem').addClass('c-top-bar-menu__subitem--rtl');
				}
				$ul.addClass('initialized');
			});
			
			
			ideapark_mega_menu_initialized = 1;
		}
	};
	
	root.ideapark_init_zoom = function () {
		if (ideapark_is_mobile_layout) {
			$(".js-product-zoom--mobile-hide.init").each(function () {
				var $this = $(this);
				$this.removeClass('init').trigger('zoom.destroy');
			});
			$(".js-product-zoom:not(.js-product-zoom--mobile-hide):not(.init)").each(function () {
				var $this = $(this);
				$this.addClass('init').zoom({
					url      : $this.data('img'),
					duration : 0,
					onZoomIn : function () {
						$(this).parent().addClass('zooming');
					},
					onZoomOut: function () {
						$(this).parent().removeClass('zooming');
					}
				});
			});
		} else {
			$(".js-product-zoom:not(.init)").each(function () {
				var $this = $(this);
				$this.addClass('init').zoom({
					url      : $this.data('img'),
					duration : 0,
					onZoomIn : function () {
						$(this).parent().addClass('zooming');
					},
					onZoomOut: function () {
						$(this).parent().removeClass('zooming');
					}
				});
			});
		}
	};
	
	root.ideapark_init_subcat_carousel = function () {
		$('.js-header-subcat').each(function () {
			var $this = $(this);
			var container_width = $this.closest('.c-page-header__sub-cat').outerWidth();
			var items = 0;
			var items_width = 0;
			$this.find('.c-page-header__sub-cat-item').each(function () {
				items_width += $(this).outerWidth();
				items++;
			});
			if (items_width >= container_width && items > 1) {
				if (!$this.hasClass('owl-carousel')) {
					$this
						.addClass('owl-carousel')
						.owlCarousel({
							center    : false,
							margin    : 0,
							loop      : false,
							autoWidth : true,
							items     : 1,
							rtl       : !!ideapark_wp_vars.isRtl,
							dots      : !$this.hasClass('h-carousel--dots-hide'),
							navText   : ideapark_nav_text,
							responsive: {
								0   : {
									nav: false,
								},
								1190: {
									nav: true,
								},
							}
						});
				}
			} else if (items > 1) {
				if ($this.hasClass('owl-carousel')) {
					$this
						.removeClass('owl-carousel')
						.trigger("destroy.owl.carousel");
				}
			}
			$this.parent().addClass('c-page-header__sub-cat--init');
		});
	};
	
	root.ideapark_init_venobox = function ($button) {
		if (root.VenoBox !== 'function') {
			var $play_button = $('.c-play', $button);
			var $button_loading = $play_button.length ? $play_button : $button;
			if ($button_loading.hasClass('js-loading')) {
				return;
			}
			$button_loading.ideapark_button('loading', 26);
			root.define = root.old_define;
			require([
				'venobox/venobox.min',
				'css!' + ideapark_wp_vars.themeUri + '/assets/css/venobox/venobox.min',
			], function (VenoBox) {
				root.define = null;
				$button_loading.ideapark_button('reset');
				root.VenoBox = VenoBox;
				new VenoBox({
					selector: ".js-video,.js-ip-video"
				});
				VenoBox().open($button[0]);
			});
		}
	};
	
	root.ideapark_init_ajax_add_to_cart = function () {
		if (ideapark_wp_vars.ajaxAddToCart) {
			$('form.cart:not(.init)').on('submit', function (e) {
				if ($(this).closest('.product-type-external').length) {
					return true;
				}
				e.preventDefault();
				var $form = $(this);
				var $button = $form.find('.single_add_to_cart_button:not(.disabled)');
				if (typeof $form.block === 'function') {
					$form.block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});
				}
				
				var formData = new FormData($form[0]);
				formData.append('add-to-cart', $form.find('[name=add-to-cart]').val());
				
				if ($button.length) {
					$button.ideapark_button('loading', 16);
				}
				
				// Ajax action.
				$.ajax({
					url        : wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'ip_add_to_cart'),
					data       : formData,
					type       : 'POST',
					processData: false,
					contentType: false,
					complete   : function (response) {
						$button.ideapark_button('reset');
						
						response = response.responseJSON;
						
						if (!response) {
							return;
						}
						
						if (response.error && response.product_url) {
							window.location = response.product_url;
							return;
						}
						
						// Redirect to cart option
						if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
							window.location = wc_add_to_cart_params.cart_url;
							return;
						}
						
						// Trigger event so themes can refresh other areas.
						$(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, null]);
						
						if (typeof $form.unblock === 'function') {
							$form.unblock();
						}
					}
				});
			}).addClass('init');
		}
	};
	
	root.ideapark_infinity_loader = function ($button, e) {
		if (typeof e !== 'undefined') {
			e.preventDefault();
		}
		var $grid = $button.parent().parent().find('.c-product-grid__list');
		var url = $button.attr('href');
		var is_a = true;
		if (!url) {
			url = $button.data('href');
			is_a = false;
		}
		if ($button.hasClass('js-loading')) {
			return;
		}
		$button.ideapark_button('loading', is_a ? 19 : 35);
		$.ajax({
			url    : url,
			type   : 'POST',
			data   : {
				'ideapark_infinity_loading': 1
			},
			success: function (results) {
				$button.ideapark_button('reset');
				if (results.products) {
					$grid.append(results.products);
					ideapark_sticky_sidebar();
				}
				if (results.paging) {
					$button.parent().replaceWith(results.paging);
				} else {
					$button.remove();
				}
				$ideapark_infinity_loader = $('.js-load-infinity');
			}
		});
	};
	
	root.ideapark_infinity_loading = function () {
		if ($ideapark_infinity_loader && $ideapark_infinity_loader.length && !$ideapark_infinity_loader.hasClass('js-loading')) {
			if ($ideapark_infinity_loader.offset().top - $(window).scrollTop() - $(window).height() <= 300) {
				ideapark_infinity_loader($ideapark_infinity_loader);
			}
		}
	};
	
	$.fn.extend({
		ideapark_button: function (option, size, ignore_size) {
			return this.each(function () {
				var $this = $(this);
				if (typeof size === 'undefined') {
					size = 16;
				}
				if (option === 'loading' && !$this.hasClass('js-loading')) {
					$this.data('button', $this.html());
					if (!ignore_size) {
						$this.data('css-width', $this.css('width'));
						$this.data('css-height', $this.css('height'));
					} else {
						$this.data('ignore-size', $this.css('width'));
					}
					$this.css('height', $this.outerHeight());
					$this.css('width', $this.outerWidth());
					var $loader = $('<i class="h-loading"></i>');
					$loader.css({
						width : size + 'px',
						height: size + 'px',
					});
					$this.html($loader);
					$this.addClass('h-after-before-hide js-loading');
				} else if (option === 'reset' && $this.hasClass('js-loading')) {
					var css_width = $this.data('css-width');
					var css_height = $this.data('css-height');
					var content = $this.data('button');
					ignore_size = ignore_size || $this.data('ignore-size');
					$this.data('button', '');
					$this.data('css-width', '');
					$this.data('css-height', '');
					$this.data('ignore-size', '');
					$this.html(content);
					$this.removeClass('h-after-before-hide js-loading');
					if (!ignore_size) {
						$this.css('width', css_width);
						$this.css('height', css_height);
					} else {
						$this.css('width', '');
						$this.css('height', '');
					}
				}
			});
		}
	});
	
	$.parseParams = function (query) {
		var re = /([^&=]+)=?([^&]*)/g;
		var decodeRE = /\+/g;
		var decode = function (str) {
			return decodeURIComponent(str.replace(decodeRE, " "));
		};
		var params = {}, e;
		while (e = re.exec(query)) {// jshint ignore:line
			var k = decode(e[1]), v = decode(e[2]);
			if (k.substring(k.length - 2) === '[]') {
				k = k.substring(0, k.length - 2);
				(params[k] || (params[k] = [])).push(v);
			} else params[k] = v;
		}
		return params;
	};
	
})(jQuery, window);

