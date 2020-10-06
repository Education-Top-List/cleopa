<?php
function nbcore_share_social()
{
  $src = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
  $style = get_theme_mod('share_buttons_style');
  $position = get_theme_mod('share_buttons_position');
  ?>
  <div class="nb-social-icons <?php echo $style . ' ' . $position; ?>">
    <a href="//www.facebook.com/sharer.php?u=<?php esc_url(the_permalink()); ?>" data-label="Facebook"
     onclick="window.open(this.href,this.title,'width=500,height=500,top=300px,left=300px');  return false;"
     rel="nofollow" target="_blank" class="facebook" title="<?php esc_attr_e('Share on Facebook', 'core-wp'); ?>"><i
     class="nb-fw-icon-facebook"></i></a>

     <a href="//twitter.com/share?url=<?php esc_url(the_permalink()); ?>"
       onclick="window.open(this.href,this.title,'width=500,height=500,top=300px,left=300px');  return false;"
       rel="nofollow" target="_blank" class="twitter" title="<?php esc_attr_e('Share on Twitter', 'core-wp'); ?>"><i
       class="nb-fw-icon-twitter"></i></a>

       <a href="//pinterest.com/pin/create/button/?url=<?php esc_url(the_permalink()); ?>&amp;media=<?php echo esc_attr($src[0]); ?>&amp;description=<?php the_title(); ?>"
         onclick="window.open(this.href,this.title,'width=500,height=500,top=300px,left=300px');  return false;"
         rel="nofollow" target="_blank" class="pinterest" title="<?php esc_attr_e('Pin on Pinterest', 'core-wp'); ?>"><i
         class="nb-fw-icon-pinterest"></i></a>

         <a href="//plus.google.com/share?url=<?php esc_url(the_permalink()); ?>" target="_blank" class="google-plus"
           onclick="window.open(this.href,this.title,'width=500,height=500,top=300px,left=300px');  return false;"
           rel="nofollow" title="<?php esc_attr_e('Share on Google+', 'core-wp'); ?>"><i class="nb-fw-icon-gplus"></i></a>

           <a href="//www.linkedin.com/shareArticle?mini=true&url=<?php esc_url(the_permalink()); ?>&title=<?php the_title(); ?>"
             onclick="window.open(this.href,this.title,'width=500,height=500,top=300px,left=300px');  return false;"
             rel="nofollow" target="_blank" class="linkedin" title="<?php esc_attr_e('Share on LinkedIn', 'core-wp'); ?>"><i class="nb-fw-icon-linkedin"></i></a>
           </div>
           <?php
         }