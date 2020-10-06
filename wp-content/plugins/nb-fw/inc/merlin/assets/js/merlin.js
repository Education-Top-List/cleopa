
var Merlin = (function($){

  var t;

    // callbacks from form button clicks.
    var callbacks = {
      install_child: function(btn) {
        var installer = new ChildTheme();
        installer.init(btn);
      },
      activate_license: function(btn) {
        var license = new ActivateLicense();
        license.init(btn);
      },
      install_plugins: function(btn){
        var plugins = new PluginManager();
        plugins.init(btn);
      },
      install_content: function(btn){
        var content = new ContentManager();
        content.init(btn);
      }
    };

    function window_loaded(){
    	var
    	body 		= $('.merlin__body'),
    	body_loading 	= $('.merlin__body--loading'),
    	body_exiting 	= $('.merlin__body--exiting'),
    	drawer_trigger 	= $('#merlin__drawer-trigger'),
    	drawer_opening 	= 'merlin__drawer--opening';
    	drawer_opened 	= 'merlin__drawer--open';

    	// setTimeout(function(){
     //     body.addClass('loaded');
     // },100);
     body.addClass('loaded');

     drawer_trigger.on('click', function(){
       body.toggleClass( drawer_opened );
     });

     if($('.merlin__content--plugins').length>0) {
      $('.merlin__content--plugins').addClass('animated jello');
    }
    if($('.merlin__content--content').length>0) {
      $('.merlin__content--content').addClass('animated flipInY');
    }
    if($('.merlin__content--ready').length>0) {
        // $('.merlin__content--ready').addClass('animated fadeInUp');
        // setTimeout(function(){
        //     $.post(merlin_params.ajaxurl, {
        //       action: "merlin_after_import_finished",
        //       wpnonce: merlin_params.wpnonce
        //   }, function(result){
        //     console.log('OK');
        // });
        // },500);
      }

      $('.merlin__button--proceed:not(.merlin__button--closer)').click(function (e) {
        e.preventDefault();
        var goTo = this.getAttribute("href");

          // body.addClass('exiting');
          window.location = goTo;
          // setTimeout(function(){
          //     window.location = goTo;
          // },400);
        });

      $(".merlin__button--closer").on('click', function(e){
       body.removeClass( drawer_opened );

       e.preventDefault();
       var goTo = this.getAttribute("href");
       window.location = goTo;

          //   setTimeout(function(){
          //     body.addClass('exiting');
          // },600);

          //   setTimeout(function(){
          //     window.location = goTo;
          // },1100);
        });

      $(".button-next").on( "click", function(e) {
        e.preventDefault();
        $(".merlin__content--content .merlin__content--transition .step3, .button-next").css({
          'display': 'none'
        });
        $(".merlin__content--content .merlin__content--transition .step4").css({
          'display': 'block'
        });

        if($('.merlin__content--plugins').length>0) {
          $('.merlin__content--plugins #merlin__drawer-trigger').click();
        }

            // var loading_button = merlin_loading_button(this);
            // if ( ! loading_button ) {
            //     return false;
            // }
            var data_callback = $(this).data("callback");
            if( data_callback && typeof callbacks[data_callback] !== "undefined"){
                // We have to process a callback before continue with form submission.
                callbacks[data_callback](this);
                return false;
              } else {
                return true;
              }
            });

      $( document ).on( 'change', '.js-merlin-demo-import-select', function() {
       var selectedIndex  = $( this ).val(),
       $selectedOption  = $( this ).children( ':selected' ),
       optionImgSrc     = $selectedOption.data( 'img-src' ),
       optionNotice     = $selectedOption.data( 'notice' ),
       optionPreviewUrl = $selectedOption.data( 'preview-url' );

       $.post( merlin_params.ajaxurl, {
        action: 'merlin_update_selected_import_data_info',
        wpnonce: merlin_params.wpnonce,
        selected_index: selectedIndex,
      }, function( response ) {
        if ( response.success ) {
         $( '.js-merlin-drawer-import-content' ).html( response.data );
       }
       else {
         alert( merlin_params.texts.something_went_wrong );
       }
     } )
       .fail( function() { alert( merlin_params.texts.something_went_wrong ) } );
     } );

      $(".merlin__content--content .merlin__content--transition .step1 a.see_home_layout").on('click', function(e) {
        $(".merlin__content--content .merlin__content--transition .step1").css({
          'display': 'none'
        });
        $(".merlin__content--content .merlin__content--transition .step2").css({
          'display': 'block'
        });
        $(".merlin__content--content").css({
          'width': '1057px'
        });
        $(".merlin__content--content").removeClass('flipInY');
        $(".merlin__content--content").addClass('pulse');
      });

      $(".merlin__content--content .merlin__content--transition .step2 a.btn-close").on('click', function(e) {
        $(".merlin__content--content .merlin__content--transition .step1").css({
          'display': 'block'
        });
        $(".merlin__content--content .merlin__content--transition .step2, .merlin__button__2").css({
          'display': 'none'
        });
        $(".merlin__content--content").removeAttr('style');
        $('.merlin__button__2').addClass('button-disabled');
        $('.merlin__content--content .merlin__content--transition .step2 .themes-wrap .gallery-item .header-box').removeClass('active flash');
        $(".merlin__content--content").removeClass('pulse');
        $(".merlin__content--content").addClass('flipInY');
      });

      if($('.themes-wrap').length>0) {
        const ps = new PerfectScrollbar('.themes-wrap');
      }

      $(".themes-wrap .gallery-item").hover(function(){
        $(this).find('.block-btn').show(500);
      }, function(){
        $(this).find('.block-btn').hide(500);
      });

      $(".merlin__content--content .merlin__content--transition .step2 a.btn-select-theme").on('click', function(e) {
        $('.merlin__content--content .merlin__content--transition .step2 .themes-wrap .gallery-item .header-box').removeClass('active flash');
        $(this).parent('.block-btn').parent('.header-box').addClass('active animated flash');
        // $(this).parent('.block-btn').parent('.header-box').addClass('active');
        $('.merlin__button__2').removeClass('button-disabled');
        $('.merlin__button__2').css({
          'display': 'block'
        });
      });

      $(".merlin__button__2").on('click', function(e) {
        var index = $(".merlin__content--content .merlin__content--transition .step2 .themes-wrap .gallery-item .active .block-btn .btn-link2").data("theme");
        console.log(index);
        if(typeof index === 'undefined' || index==='') {
          console.log('theme not found');
        } else {
          $('.js-merlin-demo-import-select').val(index).change();
          $(".merlin__content--content .merlin__content--transition .step2").css({
            'display': 'none'
          });
          $(".merlin__content--content .merlin__content--transition .step3").css({
            'display': 'block'
          });
          $(".merlin__content--content").removeAttr('style');
          $(".merlin__button__2").css({
            'display': 'none'
          });
          $(".merlin__body--content .button-next").css({
            'display': 'block'
          });
          $(".merlin__content--content").removeClass('pulse');
          $(".merlin__content--content").addClass('jackInTheBox');
        }
      });
    }

    function ChildTheme() {
     var body 				= $('.merlin__body');
     var complete, notice 	= $("#child-theme-text");

     function ajax_callback(r) {

      if (typeof r.done !== "undefined") {
       setTimeout(function(){
         notice.addClass("lead");
       },0);
       setTimeout(function(){
         notice.addClass("success");
         notice.html(r.message);
       },600);


       complete();
     } else {
      notice.addClass("lead error");
      notice.html(r.error);
    }
  }

  function do_ajax() {
    jQuery.post(merlin_params.ajaxurl, {
      action: "merlin_child_theme",
      wpnonce: merlin_params.wpnonce,
    }, ajax_callback).fail(ajax_callback);
  }

  return {
    init: function(btn) {
      complete = function() {

       setTimeout(function(){
        $(".merlin__body").addClass('js--finished');
      },1500);

       body.removeClass( drawer_opened );

       setTimeout(function(){
        $('.merlin__body').addClass('exiting');
      },3500);

       setTimeout(function(){
        window.location.href=btn.href;
      },4000);

     };
     do_ajax();
   }
 }
}


function ActivateLicense() {
 var body 		= $('.merlin__body');
 var complete, notice 	= $("#child-theme-text");

 function ajax_callback(r) {

  if (typeof r.done !== "undefined") {
   setTimeout(function(){
     notice.addClass("lead");
   },0);
   setTimeout(function(){
     notice.addClass("success");
     notice.html(r.message);
   },600);


   complete();
 } else {
  notice.addClass("lead error");
  notice.html(r.error);
}
}

function do_ajax() {
 childThemeName = $("#theme_license_key").val();
 jQuery.post(merlin_params.ajaxurl, {
  action: "merlin_activate_license",
  wpnonce: merlin_params.wpnonce,
  cThemeName: childThemeName
}, ajax_callback).fail(ajax_callback);
}

return {
  init: function(btn) {
    complete = function() {


     setTimeout(function(){
      $(".merlin__body").addClass('js--finished');
    },1500);

     body.removeClass( drawer_opened );

     setTimeout(function(){
      $('.merlin__body').addClass('exiting');
    },3500);

     setTimeout(function(){
      window.location.href=btn.href;
    },4000);

   };
   do_ajax();
 }
}
}

function PluginManager(){

 var body 				= $('.merlin__body');
 var complete;
 var items_completed 	= 0;
 var current_item 		= "";
 var $current_node;
 var current_item_hash 	= "";

 function ajax_callback(response){
  if(typeof response === "object" && typeof response.message !== "undefined"){
    $current_node.find("span").text(response.message);
    if(typeof response.url != "undefined"){
                    // we have an ajax url action to perform.

                    if(response.hash == current_item_hash){
                      $current_node.find("span").text("failed");
                      find_next();
                    }else {
                      current_item_hash = response.hash;
                      jQuery.post(response.url, response, function(response2) {
                        process_current();
                      }).fail(ajax_callback);
                    }

                  }else if(typeof response.done != "undefined"){
                    // finished processing this plugin, move onto next
                    find_next();
                  }else{
                    // error processing this plugin
                    find_next();
                  }
                }else{
                // error - try again with next plugin
                $current_node.find("span").text("Success");
                find_next();
              }
            }
            function process_current(){
              if(current_item){
                // query our ajax handler to get the ajax to send to TGM
                // if we don"t get a reply we can assume everything worked and continue onto the next one.
                jQuery.post(merlin_params.ajaxurl, {
                  action: "merlin_plugins",
                  wpnonce: merlin_params.wpnonce,
                  slug: current_item
                }, ajax_callback).fail(ajax_callback);
              }
            }
            function find_next(){
              var do_next = false;
              if($current_node){
                if(!$current_node.data("done_item")){
                  items_completed++;
                  $current_node.data("done_item",1);
                }
                $current_node.find(".spinner").css("visibility","hidden");
              }
              var $li = $(".merlin__drawer--install-plugins li");
              $li.each(function(){
                if(current_item == "" || do_next){
                  current_item = $(this).data("slug");
                  $current_node = $(this);
                  process_current();
                  do_next = false;
                }else if($(this).data("slug") == current_item){
                  do_next = true;
                }
              });
              if(items_completed >= $li.length){
                // finished all plugins!
                complete();
              }
            }

            return {
              init: function(btn){
                $(".merlin__drawer--install-plugins").addClass("installing");
                complete = function() {
                  $('.merlin__content--plugins #merlin__drawer-trigger').click();
                  $(".merlin__content--plugins .merlin__content--transition img").attr({
                    'src': replace_img_success($(".merlin__content--plugins .merlin__content--transition img").attr('src'), 'success.gif')
                  });
                  window.location.href=btn.href;

                	// setTimeout(function(){
                 //        $(".merlin__body").addClass('js--finished');
                 //    },1000);

                	// body.removeClass( drawer_opened );

                	// setTimeout(function(){
                 //        $('.merlin__body').addClass('exiting');
                 //    },3000);

                 //    setTimeout(function(){
                 //        window.location.href=btn.href;
                 //    },3500);

               };
               find_next();
             }
           }
         }

         function ContentManager() {

           var body 				= $('.merlin__body');
           var complete;
           var items_completed 	= 0;
           var current_item 		= "";
           var $current_node;
           var current_item_hash 	= "";

           function ajax_callback(response) {
            var width_process = $('.merlin__content--content .merlin__content--transition .step4 .base-process .fill').width();
            $('.merlin__content--content .merlin__content--transition .step4 .base-process .fill').animate({
              'width': (width_process+(Math.floor((Math.random() * 20) + 1)))+'px'
            }, 500);
            var currentSpan = $current_node.find("label");
            if(typeof response == "object" && typeof response.message !== "undefined"){
              currentSpan.addClass(response.message.toLowerCase());
              if(typeof response.url !== "undefined"){
                    // we have an ajax url action to perform.
                    if(response.hash === current_item_hash){
                      currentSpan.addClass("status--failed");
                      find_next();
                    }else {
                      current_item_hash = response.hash;
                        jQuery.post(response.url, response, ajax_callback).fail(ajax_callback); // recursion
                      }
                    }else if(typeof response.done !== "undefined"){
                    // finished processing this plugin, move onto next
                    find_next();
                  }else{
                    console.log('Error 2: '+response);
                    // error processing this plugin
                    find_next();
                  }
                } else {
                  var width_process = $('.merlin__content--content .merlin__content--transition .step4 .base-process .fill').width();
                  $('.merlin__content--content .merlin__content--transition .step4 .base-process .fill').animate({
                    'width': (width_process-(Math.floor((Math.random() * 20) + 1)))+'px'
                  }, 500);
                  console.log('Error response:');
                // console.log(response);
                console.log(current_item);
                // error - try again with next plugin
                // currentSpan.addClass("status--error");
                if(width_process<0) {
                  alert('Error import '+current_item);
                  find_next();
                } else {
                  process_current();
                }
              }
            }

            function process_current(){
              if(current_item) {
                var $check = $current_node.find("input:checkbox");
                if($check.is(":checked")) {
                  jQuery.post(merlin_params.ajaxurl, {
                    action: "merlin_content",
                    wpnonce: merlin_params.wpnonce,
                    content: current_item,
                    selected_index: $( '.js-merlin-demo-import-select' ).val() || 0
                  }, ajax_callback).fail(ajax_callback);
                } else {
                  $current_node.addClass("skipping");
                  setTimeout(find_next,300);
                }
                $('.merlin__content--content .merlin__content--transition .step4 span.current-process').text('Importing '+current_item);
              }
            }

            function find_next(){
              var do_next = false;
              if($current_node){
                console.log('Here');
                if(!$current_node.data("done_item")) {
                  items_completed++;
                  $current_node.data("done_item",1);
                }
                $current_node.find(".spinner").css("visibility","hidden");
              }
              var $items = $(".merlin__drawer--import-content__list-item");
              var $enabled_items = $(".merlin__drawer--import-content__list-item input:checked");
              $items.each(function(){
                if (current_item == "" || do_next) {
                  current_item = $(this).data("content");
                  $current_node = $(this);
                  process_current();
                  do_next = false;
                } else if ($(this).data("content") == current_item) {
                  do_next = true;
                }
              });
            // var per = items_completed/$items.length*100;
            // $('.merlin__content--content .merlin__content--transition .step4 .base-process .fill').animate({
            //     'width': per+'%'
            // }, 500);
            console.log(items_completed+' in '+$items.length);
            if(items_completed >= $items.length) {
              $('.merlin__content--content .merlin__content--transition .step4 span.current-process').text('Setting the configuration');
              $('.merlin__content--content .merlin__content--transition .step4 h1').text('Setting the configuration');
              complete();
            }
          }

          return {
            init: function(btn){
              $(".merlin__drawer--import-content").find("input").prop("disabled", true);
              complete = function() {
                $('.merlin__content--content .merlin__content--transition .step4 .base-process .fill').animate({
                  'width': '100%'
                }, 500);

                $(".merlin__content--content .merlin__content--transition .step4 img").attr({
                  'src': replace_img_success($(".merlin__content--content .merlin__content--transition .step4 img").attr('src'), 'success.gif')
                });

                $.post(merlin_params.ajaxurl, {
                  action: "merlin_import_finished",
                  wpnonce: merlin_params.wpnonce,
                  selected_index: $( '.js-merlin-demo-import-select' ).val() || 0
                }).done(function( result ) {
                  console.log(result);

                  $('.merlin__content--content .merlin__content--transition .step4 span.current-process').text('Import Successfully');
                  $('.merlin__content--content .merlin__content--transition .step4 h1').text('Import Successfully');

                  setTimeout(function() {
                    window.location.href=btn.href;
                  },1000);
                });
              };
              find_next();
            }
          }
        }

        function replace_img_success(url, replace) {
          const regex = /http.*\//gm;
          const str = url;
          let m;
          var path = '';

          while ((m = regex.exec(str)) !== null) {
            if (m.index === regex.lastIndex) {
              regex.lastIndex++;
            }

            m.forEach((match, groupIndex) => {
              path = match;
            });
          }
          return path+replace;
        }

        function merlin_loading_button( btn ){

          var $button = jQuery(btn);

          if ( $button.data( "done-loading" ) == "yes" ) {
           return false;
         }

         var completed = false;

         var _modifier = $button.is("input") || $button.is("button") ? "val" : "text";

         $button.data("done-loading","yes");

         $button.addClass("merlin__button--loading");

         return {
          done: function(){
            completed = true;
            $button.attr("disabled",false);
          }
        }

      }

      return {
        init: function(){
          t = this;
          $(window_loaded);
        },
        callback: function(func){
          console.log(func);
          console.log(this);
        }
      }

    })(jQuery);

    Merlin.init();
