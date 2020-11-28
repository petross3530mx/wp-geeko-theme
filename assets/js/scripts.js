(function ($) {
	// Initialize Gecko customizer preview if needed.
	$(function () {
		if (window.location !== window.parent.location) {
			if ('gecko-customizer-preview' === window.name) {
				initCustomizerPreview();
			}
		}
	});

	// Initialize sticky sidebar.
	$(function () {
		var $adminbar = $('#wpadminbar');
		var $header = $('.gc-header').not('.gc-header--static');
		var $footer = $('.gc-footer');
		var offsetTop = ($adminbar.height() || 0) + ($header.height() || 0) + 29;
		var offsetBottom = ($footer.height() || 0) + 29;

		$('.sidebar--sticky .sidebar__inner').gc_stick_in_parent({
			offset_top: offsetTop,
			offset_bottom: offsetBottom
		});
	});

	// Header Search Toggle
	$('.gc-header__search-toggle').click(function () {
		var header = $('.gc-header');
		var header_class = 'gc-header--search';
		var search = $('.gc-header__search');
		var search_box = $('.gc-header__search-box');
		var open = 'gc-header__search--open';

		$(header).toggleClass(header_class);
		$(search).toggleClass(open);
		$(search_box).fadeToggle(200);
	});

	// Fixed header after scroll
	$(window).scroll(function () {
		var scroll = $(window).scrollTop();

		if (scroll >= 30) {
			$('.gc-header').addClass('gc-header--scroll');
		} else {
			$('.gc-header').removeClass('gc-header--scroll');
		}
	});

	// Align footer to the bottom of the window
	$(window).on('load', function () {
		$(window).on('resize', function (e) {
			checkScreenSize();
		});

		checkScreenSize();

		function checkScreenSize() {
			var footer = $('.gc-footer').height();
			var newWindowWidth = $(window).width();

			if (newWindowWidth >= 980) {
				var footer = $('.gc-footer').height();
				$('body').css('padding-bottom', footer);
			} else {
				$('body').css('padding-bottom', 0);
			}
		}
	});

	// Modal
	$('.gc-js-header-menu-open').on('click', function (e) {
		e.preventDefault();
		e.stopPropagation();

		$('html').addClass('gc-modal--open');
	});

	$('.gc-js-header-menu-close').on('click', function (e) {
		e.preventDefault();
		e.stopPropagation();

		$('html').removeClass('gc-modal--open');
	});

	function check_cart() {
		var woocart_toggle = $('.js-header-cart-toggle');
		var woocart = $('.widget_shopping_cart_content p');

		if (woocart.hasClass('woocommerce-mini-cart__empty-message')) {
			$(woocart_toggle).addClass('empty');
		} else {
			$(woocart_toggle).removeClass('empty');
		}
	}
	setInterval(check_cart, 1000);

	// HEADER CART TOGGLE >>>
	$('.js-header-cart-toggle').click(function (e) {
		var $toggle = $(this);
		var $document = $(document.body);
		var $widget = $toggle.parent().find('.gc-header__cart');
		var is_opened = $toggle.hasClass('open');
		var event_name = 'click.gc-header-cart';

		// Hide widget.
		if (is_opened) {
			$toggle.removeClass('open');
			$widget.stop().fadeOut();
			$document.off(event_name);
		}

		// Show widget.
		else {
			$toggle.addClass('open');
			$widget.stop().fadeIn();

			// Setup autohide trigger.
			setTimeout(function () {
				$document.off(event_name);
				$document.on(event_name, function (e) {
					// Skip if click event occurs inside the widget.
					if ($(e.target).closest($widget).length) {
						return;
					}

					// Hide widget.
					$toggle.removeClass('open');
					$widget.stop().fadeOut();
					$document.off(event_name);
				});
			}, 1);
		}
	});
	// <<< END

	$('.gc-modal__menu .menu-item-has-children').on('click', function (e) {
		var xPosition = e.pageX;
		var width = $(this).width();
		var area = width - 40;

		$(this).toggleClass('open');

		if (xPosition > width) {
			$(this).find('.sub-menu').toggle();
		}
	});

	$(window).scroll(function () {
		var scroll = $(window).scrollTop();

		if (scroll >= 30) {
			$('body').addClass('scroll');
			$('.header').addClass('header--scroll');
		} else {
			$('body').removeClass('scroll');
			$('.header').removeClass('header--scroll');
		}
	});

	$(window).on('load', function () {
		$(window).on('resize', function (e) {
			checkScreenSize();
		});

		checkScreenSize();

		function checkScreenSize() {
			var footer = $('.footer').height();
			var newWindowWidth = $(window).width();

			if (newWindowWidth >= 980) {
				var footer = $('.footer').height();
				$('body').css('padding-bottom', footer);
			} else {
				$('body').css('padding-bottom', 0);
			}
		}
	});

	// Scroll to top
	$(window).scroll(function () {
		var scroll = $(window).scrollTop();
		var button = '.js-scroll-top';

		if (scroll >= 100) {
			$(button).fadeIn();
		} else {
			$(button).fadeOut();
		}
	});

	// Toggle header widget
	$('.js-header-widget-toggle').click(function () {
		var toggle = $(this);
		var logo = '.gc-header__logo';
		var search = '.gc-header__search';
		var menu = '.gc-header__menu-toggle';
		var openClass = 'open';
		var headerWidget = '.gc-header__widget-inner';

		$(toggle).toggleClass(openClass).parent().find(headerWidget).toggle();
		$(toggle).toggleClass(openClass).parent().parent().toggleClass(openClass);
		$(logo).toggle();
		$(menu).toggle();
		$(search).toggle();
	});

	// Toggle header widget
	$('.js-header-search-toggle').click(function () {
		var toggle = $(this);
		var headerWidget = '.header__search-bar';

		$(toggle).parent().find(headerWidget).toggle();
	});

	// Gecko grid arrangement using Macy.js.
	$(function () {
		function initMasonry($container) {
			var instance = Macy({
				container: $container,
				margin: { x: 15, y: 15 },
				columns: 2
			});

			function _rearrange() {
				var winWidth = $(window).width();
				if (winWidth < 980) {
					instance.remove();
				} else {
					instance.reInit();
				}
			}

			// Debounce grid rearrangement.
			var timer = null;
			function rearrange() {
				clearTimeout(timer);
				timer = setTimeout(_rearrange, 500);
			}

			$(window).on('load resize scroll', rearrange);
		}

		// Post grid.
		var $gridPosts = document.querySelector('#gecko-blog .content__posts');
		if ($gridPosts) {
			initMasonry($gridPosts);
		}

		// Archive grid.
		var $gridCategory = document.querySelector('.archive.date .content--grid');
		if ($gridCategory) {
			initMasonry($gridCategory);
		}

		// Category grid.
		var $gridCategory = document.querySelector('.category .content--grid');
		if ($gridCategory) {
			initMasonry($gridCategory);
		}
	});

	// Gecko user theme selector.
	$(function () {
		$('select[name=peepso_gecko_user_theme]').on('change', function () {
			var $select = $(this),
				$loading = $select.closest('.ps-js-profile-preferences-option').find('.ps-js-loading img'),
				counter = 0,
				timer;

			if (!$loading.length) {
				return;
			}

			// Reload when loading indicator is finally hidden, with max delay 30s.
			timer = setInterval(function () {
				if ($loading.is(':hidden') || ++counter >= 60) {
					clearInterval(timer);
					window.location.reload();
				}
			}, 500);
		});
	});

	/**
	 * Initialize Gecko customizer preview.
	 */
	function initCustomizerPreview() {
		// Remove adminbar on the customizer preview.
		$('#wpadminbar').remove();
		$('body').removeClass('admin-bar');

		// Function to handle received config update.
		function receiveCustomizerUpdate(e) {
			let root = document.body;
			var data = e.data || {};
			var type = data.type;
			var key = data.key;
			var value = data.value;

			if ('css' === type) {
				root.style.setProperty(key, value || false);
			}
		}

		window.addEventListener('message', receiveCustomizerUpdate, false);
	}
})(jQuery);
