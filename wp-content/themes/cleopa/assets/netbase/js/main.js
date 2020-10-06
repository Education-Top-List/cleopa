/* Wc add to cart version 1.2.9 */
jQuery( function( $ ) {
	"use strict";
	
	// wc_add_to_cart_params is required to continue, ensure the object exists
	if ( typeof wc_add_to_cart_params === 'undefined' )
		return false;
	
	// Ajax add to cart
	$( document ).on( 'click', '.variations_form .single_add_to_cart_button', function(e) {
		
		e.preventDefault();
		
		var $variation_form = $( this ).closest( '.variations_form' );
		var var_id = $variation_form.find( 'input[name=variation_id]' ).val();
		
		var product_id = $variation_form.find( 'input[name=product_id]' ).val();
		var quantity = $variation_form.find( 'input[name=quantity]' ).val();
		
		//attributes = [];
		$( '.ajaxerrors' ).remove();
		var item = {},
			check = true;
			
			var variations = $variation_form.find( 'select[name^=attribute]' );
			
			/* Updated code to work with radio button - mantish - WC Variations Radio Buttons - 8manos */ 
			if ( !variations.length) {
				variations = $variation_form.find( '[name^=attribute]:checked' );
			}
			
			/* Backup Code for getting input variable */
			if ( !variations.length) {
    			variations = $variation_form.find( 'input[name^=attribute]' );
			}
		
		variations.each( function() {
		
			var $this = $( this ),
				attributeName = $this.attr( 'name' ),
				attributevalue = $this.val(),
				index,
				attributeTaxName;
		
			$this.removeClass( 'error' );
		
			if ( attributevalue.length === 0 ) {
				index = attributeName.lastIndexOf( '_' );
				attributeTaxName = attributeName.substring( index + 1 );
		
				$this
					//.css( 'border', '1px solid red' )
					.addClass( 'required error' )
					//.addClass( 'barizi-class' )
					.before( '<div class="ajaxerrors"><p>Please select ' + attributeTaxName + '</p></div>' )
		
				check = false;
			} else {
				item[attributeName] = attributevalue;
			}
		
			// Easy to add some specific code for select but doesn't seem to be needed
			// if ( $this.is( 'select' ) ) {
			// } else {
			// }
		
		} );
		
		if ( !check ) {
			return false;
		}
		
		//item = JSON.stringify(item);
		//alert(item);
		//return false;
		
		// AJAX add to cart request
		
		var $thisbutton = $( this );

		if ( $thisbutton.is( '.variations_form .single_add_to_cart_button' ) ) {

			$thisbutton.removeClass( 'added' );
			$thisbutton.addClass( 'loading' );

			var data = {
				action: 'woocommerce_add_to_cart_variable_rc',
				product_id: product_id,
				quantity: quantity,
				variation_id: var_id,
				variation: item
			};

			// Trigger event
			$( 'body' ).trigger( 'adding_to_cart', [ $thisbutton, data ] );

			// Ajax action
			$.post( wc_add_to_cart_params.ajax_url, data, function( response ) {

				if ( ! response )
					return;

				var this_page = window.location.toString();

				this_page = this_page.replace( 'add-to-cart', 'added-to-cart' );
				
				if ( response.error && response.product_url ) {
					window.location = response.product_url;
					return;
				}
				
				if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {

					window.location = wc_add_to_cart_params.cart_url;
					return;

				} else {

					$thisbutton.removeClass( 'loading' );

					var fragments = response.fragments;
					var cart_hash = response.cart_hash;

					// Block fragments class
					if ( fragments ) {
						$.each( fragments, function( key ) {
							$( key ).addClass( 'updating' );
						});
					}

					// Block widgets and fragments
					$( '.shop_table.cart, .updating, .cart_totals' ).fadeTo( '400', '0.6' ).block({
						message: null,
						overlayCSS: {
							opacity: 0.6
						}
					});

					// Changes button classes
					$thisbutton.addClass( 'added' );

					// View cart text
					if ( ! wc_add_to_cart_params.is_cart && $thisbutton.parent().find( '.added_to_cart' ).size() === 0 ) {
						$thisbutton.after( ' <a href="' + wc_add_to_cart_params.cart_url + '" class="added_to_cart wc-forward" title="' +
							wc_add_to_cart_params.i18n_view_cart + '">' + wc_add_to_cart_params.i18n_view_cart + '</a>' );
					}

					// Replace fragments
					if ( fragments ) {
						$.each( fragments, function( key, value ) {
							$( key ).replaceWith( value );
						});
					}

					// Unblock
					$( '.widget_shopping_cart, .updating' ).stop( true ).css( 'opacity', '1' ).unblock();

					// Cart page elements
					$( '.shop_table.cart' ).load( this_page + ' .shop_table.cart:eq(0) > *', function() {

						$( '.shop_table.cart' ).stop( true ).css( 'opacity', '1' ).unblock();

						$( document.body ).trigger( 'cart_page_refreshed' );
					});

					$( '.cart_totals' ).load( this_page + ' .cart_totals:eq(0) > *', function() {
						$( '.cart_totals' ).stop( true ).css( 'opacity', '1' ).unblock();
					});

					// Trigger event so themes can refresh other areas
					$( document.body ).trigger( 'added_to_cart', [ fragments, cart_hash, $thisbutton ] );
				}
			});

			return false;

		} else {
			return true;
		}

	});

});

/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
( function() {
	var container, button, menu, links, i, len;

	container = document.getElementById( 'site-navigation' );
	if ( ! container ) {
		return;
	}

	button = container.getElementsByTagName( 'button' )[0];
	if ( 'undefined' === typeof button ) {
		return;
	}

	menu = container.getElementsByTagName( 'ul' )[0];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	menu.setAttribute( 'aria-expanded', 'false' );
	if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
		menu.className += ' nav-menu';
	}

	button.onclick = function() {
		if ( -1 !== container.className.indexOf( 'toggled' ) ) {
			container.className = container.className.replace( ' toggled', '' );
			button.setAttribute( 'aria-expanded', 'false' );
			menu.setAttribute( 'aria-expanded', 'false' );
		} else {
			container.className += ' toggled';
			button.setAttribute( 'aria-expanded', 'true' );
			menu.setAttribute( 'aria-expanded', 'true' );
		}
	};

	// Get all the link elements within the menu.
	links    = menu.getElementsByTagName( 'a' );

	// Each time a menu link is focused or blurred, toggle focus.
	for ( i = 0, len = links.length; i < len; i++ ) {
		links[i].addEventListener( 'focus', toggleFocus, true );
		links[i].addEventListener( 'blur', toggleFocus, true );
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus() {
		var self = this;

		// Move up through the ancestors of the current link until we hit .nav-menu.
		while ( -1 === self.className.indexOf( 'nav-menu' ) ) {

			// On li elements toggle the class .focus.
			if ( 'li' === self.tagName.toLowerCase() ) {
				if ( -1 !== self.className.indexOf( 'focus' ) ) {
					self.className = self.className.replace( ' focus', '' );
				} else {
					self.className += ' focus';
				}
			}

			self = self.parentElement;
		}
	}

	/**
	 * Toggles `focus` class to allow submenu access on tablets.
	 */
	( function( container ) {
		var touchStartFn, i,
			parentLink = container.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

		if ( 'ontouchstart' in window ) {
			touchStartFn = function( e ) {
				var menuItem = this.parentNode, i;

				if ( ! menuItem.classList.contains( 'focus' ) ) {
					e.preventDefault();
					for ( i = 0; i < menuItem.parentNode.children.length; ++i ) {
						if ( menuItem === menuItem.parentNode.children[i] ) {
							continue;
						}
						menuItem.parentNode.children[i].classList.remove( 'focus' );
					}
					menuItem.classList.add( 'focus' );
				} else {
					menuItem.classList.remove( 'focus' );
				}
			};

			for ( i = 0; i < parentLink.length; ++i ) {
				parentLink[i].addEventListener( 'touchstart', touchStartFn, false );
			}
		}
	}( container ) );
} )();

/**
 * File skip-link-focus-fix.js.
 *
 * Helps with accessibility for keyboard only users.
 *
 * Learn more: https://git.io/vWdr2
 */
(function() {
	var isIe = /(trident|msie)/i.test( navigator.userAgent );

	if ( isIe && document.getElementById && window.addEventListener ) {
		window.addEventListener( 'hashchange', function() {
			var id = location.hash.substring( 1 ),
				element;

			if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
				return;
			}

			element = document.getElementById( id );

			if ( element ) {
				if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
					element.tabIndex = -1;
				}

				element.focus();
			}
		}, false );
	}
})();

(function ($) {
	"use strict";
	
    var screenHeight = $(window).height();
    var screenWidth = $(window).width();
    var $rtl = false;
    if (jQuery("html").attr("dir") === 'rtl') {
        $rtl = true;
    }

    var leftInlineEl = $('.site-header.left-inline.fixed .middle-section-wrap');
    if (leftInlineEl.length > 0) {
        var sticky = new Waypoint.Sticky({
            element: $('.site-header.left-inline.fixed .middle-section-wrap')[0]
        });
    }
	
    var midStackEl = $('.site-header.mid-stack.fixed .bot-section-wrap');
    if (midStackEl.length > 0) {
        var sticky = new Waypoint.Sticky({
            element: $('.site-header.mid-stack.fixed .bot-section-wrap')[0]
        });
    }

    var splitEl = $('.site-header.split.fixed .bot-section-wrap');
    if (splitEl.length > 0) {
        var sticky = new Waypoint.Sticky({
            element: $('.site-header.split.fixed .bot-section-wrap')[0]
        });
    }

    var splitEl = $('.site-header.modern.fixed .middle-section-wrap');
    if (splitEl.length > 0) {
        var sticky = new Waypoint.Sticky({
            element: $('.site-header.modern.fixed .middle-section-wrap')[0]
        });
    }

    var splitEl = $('.site-header.simple.fixed .middle-section-wrap');
    if (splitEl.length > 0) {
        var sticky = new Waypoint.Sticky({
            element: $('.site-header.simple.fixed .middle-section-wrap')[0]
        });
    }

    var leftStackEl = $('.site-header.left-stack.fixed .middle-section-wrap');
    if (leftStackEl.length > 0) {
        var sticky = new Waypoint.Sticky({
            element: $('.site-header.left-stack.fixed .middle-section-wrap')[0]
        });
    }

    var midInlineEl = $('.site-header.mid-inline.fixed .middle-section-wrap');
    if (midInlineEl.length > 0) {
        var sticky = new Waypoint.Sticky({
            element: $('.site-header.mid-inline.fixed .middle-section-wrap')[0]
        });
    }
	
    var creativeEl = $('.site-header.creative.fixed .sticky-wrap');
    if (creativeEl.length > 0) {
        var sticky = new Waypoint.Sticky({
            element: $('.site-header.creative.fixed .sticky-wrap')[0]
        });
    }
	
    var plainEl = $('.site-header.plain.fixed .sticky-wrap');
    if (plainEl.length > 0) {
        var sticky = new Waypoint.Sticky({
            element: $('.site-header.plain.fixed .sticky-wrap')[0]
        });
    }

    $('.widget_nav_menu .menu-item-has-children > a').on('click', function (e) {
        e.preventDefault();
        $(this).next('.sub-menu').first().slideToggle('fast');
    });

    if (typeof cleopa === 'undefined') {
        return;
    }

    function menuPosition() {
        if ($('.main-navigation ul.sub-menu').length) {
            $('.main-navigation ul.sub-menu').each(function () {
                $(this).removeAttr("style");
                var $containerWidth = $("body").outerWidth();
                var $menuwidth = $(this).outerWidth();
                var $parentleft = $(this).parent().offset().left;
                var $parentright = $(this).parent().offset().left + $(this).parent().outerWidth();
                if ($(this).parents('.sub-menu').length) {
                    var $menuleft = $parentleft - $(this).outerWidth();
                    var $menuright = $parentright + $(this).outerWidth();
                    if ($rtl) {
                        if ($menuleft < 0) {
                            if ($menuright > $containerWidth) {
                                if ($parentleft > ($containerWidth - $parentright)) {
                                    $(this).css({
                                        'width': $parentleft + 'px',
                                        'left': 'auto',
                                        'right': '100%'
                                    });
                                    $(this).removeClass('sub-menu-right');
                                    $(this).addClass('sub-menu-left');
                                } else {
                                    $(this).css({
                                        'width': ($containerWidth - $parentright) + 'px',
                                        'left': '100%',
                                        'right': 'auto'
                                    });
                                    $(this).removeClass('sub-menu-left');
                                    $(this).addClass('sub-menu-right');
                                }
                            } else {
                                $(this).css({
                                    'left': '100%',
                                    'right': 'auto'
                                });
                                $(this).removeClass('sub-menu-left');
                                $(this).addClass('sub-menu-right');
                            }
                        } else {
                            $(this).css({
                                'left': 'auto',
                                'right': '100%'
                            });
                            $(this).removeClass('sub-menu-right');
                            $(this).addClass('sub-menu-left');
                        }
                    } else {
                        if ($menuright > $containerWidth) {
                            if ($menuleft < 0) {
                                if ($parentleft > ($containerWidth - $parentright)) {
                                    $(this).css({
                                        'width': $parentleft + 'px',
                                        'left': 'auto',
                                        'right': '100%'
                                    });
                                    $(this).removeClass('sub-menu-right');
                                    $(this).addClass('sub-menu-left');
                                } else {
                                    $(this).css({
                                        'width': ($containerWidth - $parentright) + 'px',
                                        'left': '100%',
                                        'right': 'auto'
                                    });
                                    $(this).removeClass('sub-menu-left');
                                    $(this).addClass('sub-menu-right');
                                }
                            } else {
                                $(this).css({
                                    'left': 'auto',
                                    'right': '100%'
                                });
                                $(this).removeClass('sub-menu-right');
                                $(this).addClass('sub-menu-left');
                            }
                        } else {
                            $(this).css({
                                'left': '100%'
                            });
                            $(this).removeClass('sub-menu-left');
                            $(this).addClass('sub-menu-right');
                        }
                    }
                } else {
                    var $menuleft = $parentright - $(this).outerWidth();
                    var $menuright = $parentleft + $(this).outerWidth();
                    if ($rtl) {
                        if ($menuleft < 0) {
                            if ($menuright > $containerWidth) {
                                $(this).offset({
                                    'left': ($containerWidth - $menuwidth) / 2
                                });
                            } else {
                                $(this).offset({
                                    'left': $parentleft
                                });
                            }
                        } else {
                            $(this).offset({
                                'left': $menuleft
                            });
                        }
                    } else {
                        if ($menuright > $containerWidth) {
                            if ($menuleft < 0) {
                                $(this).offset({
                                    'left': ($containerWidth - $menuwidth) / 2
                                });
                            } else {
                                $(this).offset({
                                    'left': $menuleft
                                });
                            }
                        } else {
                            $(this).offset({
                                'left': $parentleft
                            });
                        }
                    }
                }
            });
        }
    }
    function menuShow() {
        $('.main-navigation .menu-main-menu-wrap').addClass('active');
    }
    function menuHide() {
        $('.main-navigation .menu-main-menu-wrap').removeClass('active');
        $('.main-navigation .menu-item-has-children').removeClass('open');
    }
    function menuResponsive() {
        var screenHeight = jQuery(window).height();
        var screenWidth = jQuery(window).width();
        if ($('.navigation_right .menu-sub-menu-container').length) {
            if (screenWidth < cleopa.menu_resp) {
                $('.navigation_right #menu-sub-menu').appendTo('.navigation_left .menu-main-menu-container');
                $('.main-navigation').appendTo('.navigation_right');
            } else {
                $('.main-navigation').appendTo('.navigation_left');
                $('.navigation_left #menu-sub-menu').appendTo('.navigation_right .menu-sub-menu-container');
            }
        } else {
            if ($('.main-menu-section .nb-header-sub-menu').length) {
                $('.main-menu-section .nb-header-sub-menu > li').appendTo('.nb-navbar');
                $('.main-menu-section .sub-navigation').remove();
            }
        }
        if (screenWidth < cleopa.menu_resp || cleopa.menu_resp == 0) {
            $('.site-header').removeClass('header-desktop');
            $('.site-header').addClass('header-mobile');
            $('.main-navigation').removeClass('main-desktop-navigation');
            $('.main-navigation').addClass('main-mobile-navigation');
			if ($('.admin-bar').length > 0){
				if (screenWidth > 782) {
					$('.main-navigation .menu-main-menu-wrap').css({'height': (screenHeight - 32) + 'px',})
				} else if (screenWidth > 600) {
					$('.main-navigation .menu-main-menu-wrap').css({'height': (screenHeight - 46) + 'px',})
				} else {
					$('.main-navigation .menu-main-menu-wrap').css({'height': screenHeight + 'px',})
				}
			} else {
				$('.main-navigation .menu-main-menu-wrap').css({'height': screenHeight + 'px',})
			}
        } else {
			$('.main-navigation .menu-main-menu-wrap').removeAttr('style');
            $('.site-header').removeClass('header-mobile');
            $('.site-header').addClass('header-desktop');
            $('.main-navigation').removeClass('main-mobile-navigation');
			$('.main-navigation').addClass('main-desktop-navigation');
            $('.main-navigation .menu-main-menu-wrap').removeAttr('style');
            $('.main-navigation .menu-item-has-children').removeClass('open');
            menuPosition();
        }
    }
    menuResponsive();
    $('.main-navigation .mobile-toggle-button').on('click', function () {
		if ($('.main-navigation .menu-main-menu-wrap.active').length > 0) {
			menuHide();
		} else {
			menuShow();
		}
    });
    $('.main-navigation .icon-cancel-circle').on('click', function () {
		menuHide();
    });
    $('.main-navigation .menu-item-has-children').on('click', function () {
		$(this).toggleClass('open');
    });
    $('.main-navigation .menu-item-has-children > *').on('click', function (e) {
		e.stopPropagation();
    });
	if ($('.site-header.creative.header-mobile').length > 0 || $('.site-header.plain.header-mobile').length > 0){
		$('.site-header .bot-section .main-menu-section').offset({'top': $('.site-header.header-mobile .icon-header-wrap').offset().top});
	} else {
		$('.site-header .bot-section .main-menu-section').removeAttr('style');
	}
    $(window).on('resize', function () {
        menuResponsive();
		if ($('.site-header.creative.header-mobile').length > 0 || $('.site-header.plain.header-mobile').length > 0){
			$('.site-header .bot-section .main-menu-section').offset({'top': $('.site-header.header-mobile .icon-header-wrap').offset().top});
		} else {
			$('.site-header .bot-section .main-menu-section').removeAttr('style');
		}
    });

    $('.blog .masonry').isotope({
        itemSelector: '.post'
    });

    var d = 0;
    var $numbertype = null;

    var quantityButton = function () {
        $(".quantity-plus, .quantity-minus").mousedown(function () {
            var $el = $(this).closest('.nb-quantity').find('.qty');
            $numbertype = parseInt($el.val());
            d = $(this).is(".quantity-minus") ? -1 : 1;
            $numbertype = $numbertype + d;
            if ($numbertype > 0) {
                $el.val($numbertype);
            }

            $( '.woocommerce-cart-form :input[name="update_cart"]' ).prop( 'disabled', false );

        });
    };
    quantityButton();

    jQuery(document.body).on('removed_from_cart updated_cart_totals', function () {
        quantityButton();
    });

    if (jQuery().magnificPopup) {
        $('.featured-gallery').magnificPopup({
            delegate: 'img',
            type: 'image',
            gallery: {
                enabled: true
            },
            callbacks: {
                elementParse: function (item) {
                    item.src = item.el.attr('src');
                }
            }
        });
        $('.popup-search').magnificPopup({
            type: 'inline',
            focus: '.search-field',
            // modal: true,
            // midClick: true
            mainClass: 'mfp-search',
            callbacks: {
                beforeOpen: function () {
                    if ($(window).width() < 700) {
                        this.st.focus = false;
                    } else {
                        this.st.focus = '.search-field';
                    }
                }
            }
        });
        $(document).on('click', '.popup-modal-dismiss', function (e) {
            e.preventDefault();
            $.magnificPopup.close();
        });
    }

    var $upsells = $('.upsells .products');
    var $upsellsCells = $upsells.find('.product');

    if ($upsellsCells.length <= cleopa.upsells_columns) {
        $upsells.addClass('hiding-nav-ui');
    }

    var $related = $('.related .products');
    var $relatedCells = $related.find('.product');

    if ($relatedCells.length <= cleopa.related_columns) {
        $related.addClass('hiding-nav-ui');
    }

    var $crossSells = $('.cross-sells .products');
    var $crossSellsCells = $crossSells.find('.product');

    if ($crossSellsCells.length <= cleopa.cross_sells_columns) {
        $crossSells.addClass('hiding-nav-ui');
    }

    if (jQuery().accordion) {
        $('.shop-main.accordion-tabs .wc-tabs').accordion({
            header: ".accordion-title-wrap",
            heightStyle: "content"
        });
    }

    $('.header-cart-wrap').on({
        mouseenter: function () {
            $(this).find('.mini-cart-section').stop().fadeIn('fast');
        },
        mouseleave: function () {
            $(this).find('.mini-cart-section').stop().fadeOut('fast');
        }
    });

    $('.header-account-wrap').on({
        mouseenter: function () {
            $(this).find('.nb-account-dropdown').stop().fadeIn('fast');
        },
        mouseleave: function () {
            $(this).find('.nb-account-dropdown').stop().fadeOut('fast');
        }
    });

    $(document.body).on('added_to_cart', function () {
        $(".cart-notice-wrap").addClass("active").delay(5000).queue(function (next) {
            $(this).removeClass("active");
            next();
        });
    });

    $('.cart-notice-wrap span').on('click', function () {
        $(this).closest('.cart-notice-wrap').removeClass('active');
    });

    var $sticky = $('.sticky-wrapper.sticky-sidebar');

    if ($sticky.length > 0) {
        $($sticky).stick_in_parent({
            offset_top: 45
        });

        $(window).on('resize', function () {
            $($sticky).trigger('sticky_kit:detach');
        });
    }

    if ($('#back-to-top-button').length) {
        var scrollTrigger = 500; // px
        var backToTop = function () {
            var scrollTop = $(window).scrollTop();
            if (scrollTop > scrollTrigger) {
                $('#back-to-top-button').addClass('show');
            } else {
                $('#back-to-top-button').removeClass('show');
            }
        };
        backToTop();
        $(window).on('scroll', function () {
            backToTop();
        });
        $('#back-to-top-button').on('click', function (e) {
            e.preventDefault();
            $('html,body').animate({
                scrollTop: 0
            }, 700);
        });
    }
    if ($('.related .swiper-container').length) {
        var slidesm = 2;
        var slidemd = 3;
        if (cleopa.related_columns == 2) {
            slidesm = 1;
            slidemd = 1;
        }
        var related = new Swiper('.related .swiper-container', {
            slidesPerView: cleopa.related_columns,
            pagination: '.swiper-pagination',
            paginationClickable: true,
            breakpoints: {
                991: {
                    slidesPerView: slidemd
                },
                767: {
                    slidesPerView: slidesm
                },
                575: {
                    slidesPerView: 1
                }
            }
        });
    }
    if ($('.upsells .swiper-container').length) {
        var slidesm = 2;
        var slidemd = 3;
        if (cleopa.upsells_columns == 2) {
            slidesm = 1;
            slidemd = 1;
        }
        var upsells = new Swiper('.upsells .swiper-container', {
            slidesPerView: cleopa.upsells_columns,
            pagination: '.swiper-pagination',
            paginationClickable: true,
            breakpoints: {
                991: {
                    slidesPerView: slidemd
                },
                767: {
                    slidesPerView: slidesm
                },
                575: {
                    slidesPerView: 1
                }
            }
        });
    }
    if ($('.cross-sells .swiper-container').length) {
        var slidemd = 3;
        var slidelg = 4;
        if (cleopa.cross_sells_columns == 3) {
            slidemd = 2;
            slidelg = 3;
        }
        var crossSells = new Swiper('.cross-sells .swiper-container', {
            slidesPerView: cleopa.cross_sells_columns,
            pagination: '.swiper-pagination',
            paginationClickable: true,
            breakpoints: {
                1199: {
                    slidesPerView: slidelg
                },
                991: {
                    slidesPerView: slidemd
                },
                767: {
                    slidesPerView: 2
                },
                575: {
                    slidesPerView: 1
                }
            }
        });
    }
    var swiperInit = function () {
        if ($('.featured-gallery').length && $('.thumb-gallery').length) {
            var featuredObj = {};

            if (cleopa.thumb_pos !== 'right-dots') {
                featuredObj.nextButton = '.swiper-button-next';
                featuredObj.prevButton = '.swiper-button-prev';

                var galleryTop = new Swiper('.featured-gallery', featuredObj);

                var thumbObj = {
                    spaceBetween: 10,
                    centeredSlides: true,
                    slidesPerView: 4,
                    touchRatio: 0.2,
                    slideToClickedSlide: true
                };

                if (cleopa.thumb_pos === 'left-thumb' || cleopa.thumb_pos === 'inside-thumb') {
                    thumbObj.direction = 'vertical';
                }

                var galleryThumbs = new Swiper('.thumb-gallery', thumbObj);
                galleryTop.params.control = galleryThumbs;
                galleryThumbs.params.control = galleryTop;
            } else {
                featuredObj.pagination = '.featured-gallery .swiper-pagination';
                featuredObj.paginationClickable = true;

                var galleryTop = new Swiper('.featured-gallery', featuredObj);
            }
        }
    };
    swiperInit();

    var isMobile = false;
    var $variation_form = $('.variations_form');
    var $product_variations = $variation_form.data('product_variations');
    $('body').on('click touchstart', 'li.swatch-item', function () {
        var current = $(this);
        var value = current.attr('data-optionvalue');
        var selector_name = current.closest('ul').attr('data-id');
        if ($("select#" + selector_name).find('option[value="' + value + '"]').length > 0)
        {
            $(this).closest('ul').children('li').each(function () {
                $(this).removeClass('selected');
                $(this).removeClass('disable');
            });
            if (!$(this).hasClass('selected'))
            {
                current.addClass('selected');
                $("select#" + selector_name).val(value).change();
                $("select#" + selector_name).trigger('change');
                $variation_form.trigger('wc_variation_form');
                $variation_form
                        .trigger('woocommerce_variation_select_change')
                        .trigger('check_variations', ['', false]);
            }
        } else {
            current.addClass('disable');
        }
    });

    $variation_form.on('wc_variation_form', function () {
        $(this).on('click', '.reset_variations', function (event) {
            $(this).parents('.variations').eq(0).find('ul.swatch li').removeClass('selected');
        });
    });
    var $single_variation_wrap = $variation_form.find('.single_variation_wrap');
    $single_variation_wrap.on('show_variation', function (event, variation) {
        var $product = $variation_form.closest('.product');
        if (variation.image_link)
        {
            var variation_image = variation.image_link;
            $product.find('.main-image a').attr('href', variation_image);
            $product.find('.main-image a img').attr('src', variation.image_src);
            $product.find('.main-image a img').attr('srcset', variation.image_srcset);
            $product.find('.main-image a img').attr('alt', variation.image_alt);
            $product.find('.main-image a img').attr('title', variation.image_title);
            $product.find('.main-image a img').attr('sizes', variation.image_sizes);
            $product.find('.main-image img').attr('data-large', variation_image);
        }
    });

    var qv_modal = $(document).find('#yith-quick-view-modal'),
            qv_overlay = qv_modal.find('.yith-quick-view-overlay'),
            qv_content = qv_modal.find('#yith-quick-view-content'),
            qv_close = qv_modal.find('#yith-quick-view-close'),
            qv_wrapper = qv_modal.find('.yith-wcqv-wrapper'),
            qv_wrapper_w = qv_wrapper.width(),
            qv_wrapper_h = qv_wrapper.height(),
            center_modal = function () {

                var window_w = $(window).width(),
                        window_h = $(window).height(),
                        width = ((window_w - 60) > qv_wrapper_w) ? qv_wrapper_w : (window_w - 60),
                        height = ((window_h - 120) > qv_wrapper_h) ? qv_wrapper_h : (window_h - 120);

                qv_wrapper.css({
                    'left': ((window_w / 2) - (width / 2)),
                    'top': ((window_h / 2) - (height / 2)),
                    'width': width + 'px',
                    'height': height + 'px'
                });
            };

    /*==================
     *MAIN BUTTON OPEN
     ==================*/

    $.fn.yith_quick_view = function () {

        $(document).off('click', '.yith-wcqv-button').on('click', '.yith-wcqv-button', function (e) {
            e.preventDefault();

            var t = $(this),
                    product_id = t.data('product_id');

            t.block({
                message: null,
                overlayCSS: {
                    background: '#fff url(' + cleopa.loader + ') no-repeat center',
                    opacity: 0.5,
                    cursor: 'none'
                }
            });

            t.addClass('loading');

            setTimeout(function () {
                t.removeClass('loading');
            }, 3000);

            if (!qv_modal.hasClass('loading')) {
                qv_modal.addClass('loading');
            }

            // stop loader
            $(document).trigger('qv_loading');
            ajax_call(t, product_id, true);
        });
    };

    /*================
     * MAIN AJAX CALL
     ================*/

    var ajax_call = function (t, product_id, is_blocked) {

        $.ajax({
            url: cleopa.ajaxurl,
            data: {
                action: 'yith_load_product_quick_view',
                product_id: product_id
            },
            dataType: 'html',
            type: 'POST',
            success: function (data) {

                qv_content.html(data);

                // quantity fields for WC 2.2
                if (cleopa.is2_2) {
                    qv_content.find('div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)').addClass('buttons_added').append('<input type="button" value="+" class="plus" />').prepend('<input type="button" value="-" class="minus" />');
                }

                // Variation Form
                var form_variation = qv_content.find('.variations_form');

                form_variation.wc_variation_form();
                form_variation.trigger('check_variations');

                if (typeof $.fn.yith_wccl !== 'undefined') {
                    form_variation.yith_wccl();
                }

                // Init prettyPhoto
                if (typeof $.fn.prettyPhoto !== 'undefined') {
                    qv_content.find("a[data-rel^='prettyPhoto'], a.zoom").prettyPhoto({
                        hook: 'data-rel',
                        social_tools: false,
                        theme: 'pp_woocommerce',
                        horizontal_padding: 20,
                        opacity: 0.8,
                        deeplinking: false
                    });
                }

                if (!qv_modal.hasClass('open')) {
                    qv_modal.removeClass('loading').addClass('open');
                    if (is_blocked)
                        t.unblock();
                }

                // stop loader
                $(document).trigger('qv_loader_stop');
                swiperInit();
                quantityButton();
            }
        });
    };

    /*===================
     * CLOSE QUICK VIEW
     ===================*/

    var close_modal_qv = function () {

        // Close box by click overlay
        qv_overlay.on('click', function (e) {
            close_qv();
        });
        // Close box with esc key
        $(document).keyup(function (e) {
            if (e.keyCode === 27)
                close_qv();
        });
        // Close box by click close button
        qv_close.on('click', function (e) {
            e.preventDefault();
            close_qv();
        });

        var close_qv = function () {
            qv_modal.removeClass('open').removeClass('loading');

            setTimeout(function () {
                qv_content.html('');
            }, 1000);
        };
    };

    close_modal_qv();


    center_modal();
    $(window).on('resize', center_modal);

    // START
    $.fn.yith_quick_view();

    $(document).on('yith_infs_adding_elem yith-wcan-ajax-filtered', function () {
        // RESTART
        $.fn.yith_quick_view();
    });

    $('.add_to_wishlist').on('click', function() {
        $(this).find('.tooltip').hide();
    });

    if ("function" !== typeof window.vc_prettyPhoto) {
        var vc_prettyPhoto = function () {
            try {
                $ && $.fn && $.fn.prettyPhoto && $('a.prettyphoto').prettyPhoto({
                    animationSpeed: "normal",
                    hook: "data-rel",
                    padding: 15,
                    opacity: .7,
                    default_width: 300,
                    showTitle: !0,
                    allowresize: !1,
                    counter_separator_label: "/",
                    hideflash: !1,
                    deeplinking: !1,
                    modal: !1,
                    callback: function () {
                        location.href.indexOf("#!prettyPhoto") > -1 && (location.hash = "");
                    },
                    social_tools: ""
                });
            } catch (err) {
                window.console && window.console.log && console.log(err);
            }
        };
        vc_prettyPhoto();
    }
    jQuery(window).load(function() {
        
        jQuery(".loading").fadeOut();
        
        var $window = jQuery(window).innerHeight();
        var $html = jQuery('html').innerHeight();
        if($html < $window){
            $('#colophon').css({position:'fixed',width:'100%',bottom:'0',left:'0'})
        }
    })

    // allow press enter on coupon button
    $(document).on('keyup', '#coupon_code', function (e) {
		if (e.keyCode == 13 || e.which == 13) {
            e.preventDefault();
            $('input[name="apply_coupon"]').click();
        }
    });

})(jQuery);