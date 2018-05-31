                </div>
			</section>
            <?php global $awp_options; ?>
            <div class="clearfix"></div>

            <?php if(isset($awp_options['toolbar_login_show']) && $awp_options['toolbar_login_show'] == 1){ ?>
            <div class="modal fade" id="login_modal" data-backdrop="static" data-keyboard="true" tabindex="-1">
                <div class="vertical-alignment-helper">
                    <div class="modal-dialog vertical-align-center">
                        <div class="modal-content">
                            <div class="modal-body">
	                            <form method="POST" id="automotive_login_form">
	                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php _e("Close", "automotive"); ?></span></button>

	                                <h4><?php _e("Login to access different features", "automotive"); ?></h4>

	                                <input type="text" placeholder="<?php _e("Username", "automotive"); ?>" class="username_input margin-right-10 margin-vertical-10">
	                                <input type="password" placeholder="<?php _e("Password", "automotive"); ?>" class="password_input margin-right-10 margin-vertical-10"> <i class="fa fa-refresh fa-spin login_loading"></i>

	                                <div class="clearfix"></div>

	                                <input type="checkbox" name="remember_me" value="yes" id="remember_me"> <label for="remember_me" class="margin-bottom-10"><?php _e("Remember Me", "automotive"); ?></label><br>

	                                <input type="submit" class="ajax_login md-button" data-nonce="<?php echo wp_create_nonce("ajax_login_none"); ?>" value="<?php _e("Login", "automotive"); ?>">
								</form>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
            </div><!-- /.modal -->
            <?php } ?>
            
			<!--Footer Start-->
            <?php 
            wp_reset_postdata();

            global $post;

            $footer_area = (is_singular() && isset($post->ID) ? get_post_meta( $post->ID, "footer_area", true ) : "");
            $footer_area = (isset($footer_area) && !empty($footer_area) ? $footer_area : "default-footer");
if(WPGlobus::Config()->language == 'mx')
{ $ct1= "Men&uacute;"; 
$ct1a="Home";
$ct1b="C&oacute;mo Funci&oacute;na";
$ct1c="Inventario";
$ct1d="An&uacute;nciate";
$ct1e="Contacto";
  $ct2= "Industrias"; 
$ct2a="Construcci&oacute;n";
$ct2b="Agr&iacute;cola";
$ct2c="Canteras y agregados";
$ct2d="Transporte";
$ct2e="Comercios";
$ct2f="Indutrial";
$ct2g="Mineria";
$ct2h="Petroleo";
$ct2i="Marino";
  $ct3="Soporte"; 
$ct3a="C&oacute;ntactanos";
$ct3b="Polit&iacute;cas de uso";
$ct3c="Aviso de privacidad";
$ct3d="Preguntas frecuentes";
  $ct4="S&iacute;guenos"; }
else{ $ct1= "Menu";
$ct1a="Home";
$ct1b="How it works";
$ct1c="Catalog";
$ct1d="Advertise";
$ct1e="Contact";
      $ct2= "Industries"; 
$ct2a="Construcction";
$ct2b="Aggriculture";
$ct2c="Aggregates";
$ct2d="Transportation";
$ct2e="Commerce";
$ct2f="Industrial";
$ct2g="Minery";
$ct2h="Petroleum";
$ct2i="Marine";
      $ct3="Support"; 
$ct3a="Contact";
$ct3b="Use policy";
$ct3c="Privacy policy";
$ct3d="FAQ";
      $ct4="Follow us"; }
            // footer text
            if(isset($awp_options['footer_text']) && !empty($awp_options['footer_text'])){ 
                $wp_link       = "<a href='http://www.wordpress.org'>WordPress</a>";
                $theme_link    = "<a href='http://www.themesuite.com'>Automotive</a>";
                $loginout_link = wp_loginout("", false);
                $blog_title    = get_bloginfo('name');
                $blog_link     = site_url();
                $the_year      = date("Y");
                                
                $search  = array("{wp-link}", "{theme-link}", "{loginout-link}", "{blog-title}", "{blog-link}", "{the-year}");
                $replace = array($wp_link, $theme_link, $loginout_link, $blog_title, $blog_link, $the_year);
                
                $footer_text = str_replace($search, $replace, $awp_options['footer_text']);
            } 

            if($footer_area != "no-footer" && $awp_options['footer_widgets']){

 ?>
                <footer itemscope="itemscope" itemtype="https://schema.org/WPFooter" >
                    <div class="container">
                        <div class="row">



<div class="row">
                            <div class="col-lg-4  padding-left-none md-padding-left-none sm-padding-left-15 xs-padding-left-15 list col-xs-12">			<div class="textwidget"><h3 style="color:#fff; line-height: 35px;"><?php echo $ct1; ?></h3>
<div   class="pxl" style="border-bottom: solid 1px #ff0000;width:10%;"></div>
<ul style="list-style: none;
    padding-left: 0px;margin-top:5px;">
<li>
<a href="#"><?php echo $ct1a; ?></a>
</li>
<li>
<a href="#"><?php echo $ct1b; ?></a>
</li>
<li>
<a href="#"><?php echo $ct1c; ?></a>
</li>
<li>
<a href="#"><?php echo $ct1d; ?></a>
</li>
<li>
<a href="#"><?php echo $ct1e; ?></a>
</li>
</ul></div>
		</div><div class="col-lg-4  list col-xs-12">			<div class="textwidget"><h3 style="color:#fff; line-height: 35px;"><?php echo $ct3; ?></h3>
<div class="pxl" style="border-bottom: solid 1px #ff0000;width:10%;"></div>
<ul style="list-style: none;
    padding-left: 0px;margin-top:5px;">
<li>
<a href="#"><?php echo $ct3a; ?></a>
</li>
<li>
<a href="#"><?php echo $ct3b; ?></a>
</li>
<li>
<a href="#"><?php echo $ct3c; ?></a>
</li>
<li>
<a href="#"><?php echo $ct3d; ?></a>
</li>

</ul></div>
		</div><div class="col-lg-4   padding-right-none md-padding-right-none sm-padding-right-15 xs-padding-right-15 list col-xs-12">			<div class="textwidget"><h3 style="color:#fff; line-height: 35px;"><?php echo $ct4; ?></h3>
<div  class="pxl" style="border-bottom: solid 1px #ff0000;width:10%;"></div>
<ul style="list-style: none;
    padding-left: 0px;display:inline-block;margin-top:5px;">
<li style="width:50%;float:left;">
<a href="#"><img src="http://anthrobotic.com/wp-content/uploads/2015/01/ANTHROBOTIC-VIDEO-ICON-e1420760465461.png" width="60px"></a>
</li>
<li style="width:50%;float:right;">
<a href="#"><img src="http://www.craigchurch.org/hp_wordpress/wp-content/uploads/2016/06/facebook-2-1.png" width="36px"></a>
</li>

</ul></div>
		</div>                        </div>
                           
                        </div>
                    </div>
                </footer>
            <?php } ?>
            
            <div class="clearfix"></div>
            <section class="copyright-wrap <?php echo (isset($footer_area) && $footer_area == "no-footer" ? "no_footer" : "footer_area"); ?>">
                <div class="container">
                    <div class="row">
                        <?php if(isset($footer_area) && $footer_area == "no-footer"){ ?>
                        <div class="col-lg-12">
                            <div class="logo-footer margin-bottom-15 md-margin-bottom-15 sm-margin-bottom-10 xs-margin-bottom-15">        
                                <?php if($awp_options['footer_logo']){ ?>
                                    <?php if(isset($awp_options['footer_logo_image']['url']) && !empty($awp_options['footer_logo_image']['url'])){
                                        echo "<img src='" . $awp_options['footer_logo_image']['url'] . "' alt='logo'>";
                                    } else { ?>
                                        <?php if(isset($awp_options['logo_image']['url']) && !empty($awp_options['logo_image']['url'])){ ?>
                                        <img src='<?php echo $awp_options['logo_image']['url']; ?>' alt='logo'>
                                        <?php } else { ?>
                                        <div class="logo-footer"><a href="<?php echo home_url(); ?>">
                                            <h2><?php echo (isset($awp_options['logo_text']) && !empty($awp_options['logo_text']) ? $awp_options['logo_text'] : ""); ?></h2>
                                            <span><?php echo (isset($awp_options['logo_text_secondary']) && !empty($awp_options['logo_text_secondary']) ? $awp_options['logo_text_secondary'] : ""); ?></span></a>
                                        </div>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>

                                <?php if($awp_options['footer_copyright']){ ?>
                                    <div class="footer_copyright_text"><?php echo wpautop(do_shortcode((isset($footer_text) && !empty($footer_text) ? $footer_text : ""))); ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } else { ?>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <?php if($awp_options['footer_logo']){ ?>
                                <?php
	                            if(isset($awp_options['footer_logo_image']['url']) && !empty($awp_options['footer_logo_image']['url'])){
		                            echo "<div itemscope itemtype=\"http://schema.org/Organization\">";
                                        echo "<a itemprop=\"url\" href=\"" . site_url() . "\"><img itemprop=\"logo\" src='" . $awp_options['footer_logo_image']['url'] . "' alt='logo'></a>";
		                            echo "</div>";
                                    } else { ?>
                                        <?php if(isset($awp_options['logo_image']['url']) && !empty($awp_options['logo_image']['url'])){ ?>
                                        <img src='<?php echo $awp_options['logo_image']['url']; ?>' alt='logo'>
                                        <?php } else { ?>
                                        <div class="logo-footer"><a href="<?php echo home_url(); ?>">
                                            <h2><?php echo (isset($awp_options['logo_text']) && !empty($awp_options['logo_text']) ? $awp_options['logo_text'] : ""); ?></h2>
                                            <span><?php echo (isset($awp_options['logo_text_secondary']) && !empty($awp_options['logo_text_secondary']) ? $awp_options['logo_text_secondary'] : ""); ?></span></a>
                                        </div>
                                        <?php } ?>
                                <?php } ?>
                            <?php } ?>

                            <?php if($awp_options['footer_copyright']){ ?>
                                <div><?php echo wpautop(do_shortcode((isset($footer_text) && !empty($footer_text) ? $footer_text : ""))); ?></div>
                            <?php } ?>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <?php if($awp_options['footer_icons']) { ?>
	                            <div itemscope itemtype="http://schema.org/Organization">
		                            <link itemprop="url" href="<?php home_url(); ?>">
	                                <ul class="social clearfix">
	                                    <?php
	                                    if(!empty($awp_options['social_network_links']['enabled'])){
	                                        unset($awp_options['social_network_links']['enabled']['placebo']);

	                                        foreach($awp_options['social_network_links']['enabled'] as $index => $social){
	                                            $link = (isset($awp_options[ strtolower($social) . '_url']) && !empty($awp_options[ strtolower($social) . '_url']) ? $awp_options[ strtolower($social) . '_url'] : "");
	                                            echo '<li><a itemprop="sameAs" class="' . strtolower($social) . '" href="' . $link . '" target="_blank"></a></li>';
	                                        }
	                                    } ?>
	                                </ul>
	                            </div>
                            <?php }

                            if($awp_options['footer_menu']) { ?>
                                <?php
	                            $footer_menu_location = (!is_user_logged_in() || (is_user_logged_in() && !has_nav_menu( "logged-in-footer-menu" )) ? "footer-menu" : "logged-in-footer-menu");

	                            wp_nav_menu(
		                            array(
			                            'theme_location'  => $footer_menu_location,
			                            'menu_class'      => 'f-nav',
			                            'container_class' => 'col-lg-12'
		                            )
	                            );
                            } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
            
            <div class="back_to_top">
                <img src="<?php echo get_template_directory_uri(); ?>/images/arrow-up.png" alt="<?php _e('Back to top', 'automotive'); ?>" />
            </div>
			<?php
            if(isset($awp_options['body_layout']) && !empty($awp_options['body_layout']) && $awp_options['body_layout'] != 1){
                echo "</div>";
            } 

            if(isset($awp_options['custom_js']) && !empty($awp_options['custom_js'])){ ?>
            <script type="text/javascript">
                (function($) {
                    "use strict";
                    jQuery(document).ready( function($){
                        <?php echo $awp_options['custom_js']; ?>
                    

});
                })(jQuery);
            </script>

            <?php } 
            
            automotive_google_analytics_code("body");
            wp_footer(); ?>
	</body>
<!--[if IE]>
<style>
#yearDiv{display:none !important;}
#priceDiv{display:none !important;}
.year-dropdown{display:block !important;}


.price-dropdown{display:block !important;}
</style>
<![endif]-->
</html>