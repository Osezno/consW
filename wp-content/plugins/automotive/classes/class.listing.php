<?php

if ( ! class_exists( "Listing" ) ) {

	/**
	 * Listing Class
	 */
	class Listing {
		public $listing_categories  = array();
		public $lwp_options         = array();
		public $current_categories  = array();

		public $current_query_info  = array();

		/**
		 * Generates the listing categories option name
		 *
		 * @return string
		 */
		public function get_listing_categories_option_name( $lang_code = false ) {
			$option = "listing_categories";

			if ( $lang_code ) {
				$option .= "_" . $lang_code;
			} elseif ( defined( "ICL_LANGUAGE_CODE" ) ) {
				$option .= "_" . ICL_LANGUAGE_CODE;
			} elseif ( is_admin() && isset( $_GET['lang'] ) && ! empty( $_GET['lang'] ) ) {
				$option .= "_" . sanitize_text_field( $_GET['lang'] );
			}

			return $option;
		}

		/**
		 * Gets the stored listing categories
		 *
		 * @return array|mixed
		 */
		public function get_listing_categories_option( $lang_code = false ) {
			$option = get_option( $this->get_listing_categories_option_name( $lang_code ) );
			$option = ( empty( $option ) ? array() : $option );

			return $option;
		}

		public function refresh_listing_categories() {
			$this->listing_categories = $this->get_listing_categories_option();

			if ( defined( "ICL_LANGUAGE_CODE" ) && ! get_option( "listing_categories_en" ) ) {
				update_option( "listing_categories_en", get_option( "listing_categories" ) );
			}
		}

		public function get_listing_wp() {
			$this->lwp_options = get_option( "listing_wp" );
		}

		public function __construct() {
			$this->listing_categories = $this->get_listing_categories_option();

			// actions
			add_action( 'plugins_loaded', array( $this, 'import_start_session' ) );
			add_action( 'plugins_loaded', array( $this, 'load_redux' ) );
			add_action( 'init', array( $this, 'register_listing_post_type' ), 0 );
			add_action( 'init', array( $this, 'current_listing_categories' ) );
			add_action( 'pre_get_posts', array( $this, 'custom_listings_orderby' ) );
			add_action( 'manage_listings_posts_custom_column', array( $this, 'manage_listings_columns' ), 10, 2 );

			add_action( "wp_trash_post", array( $this, "listing_sent_trash" ) );
			add_action( "untrash_post", array( $this, "listing_restore_trash" ) );

			// in case WPML is active, re-load the listing categories
			add_action( "init", array( $this, "refresh_listing_categories" ) );
			add_action( "init", array( $this, "get_listing_wp" ) );

			// ajax actions
			add_action( "wp_ajax_search_box_shortcode_update_options", array(
				$this,
				"search_box_shortcode_update_options"
			) );
			add_action( "wp_ajax_nopriv_search_box_shortcode_update_options", array(
				$this,
				"search_box_shortcode_update_options"
			) );
			add_action( "wp_ajax_hide_automotive_message", array( $this, "hide_automotive_message" ) );
			add_action( "wp_ajax_edit_listing_category_value", array( $this, "edit_listing_category_value" ) );
			add_action( "wp_ajax_nopriv_edit_listing_category_value", array( $this, "deny_access_to_guest" ) );
			add_action( "wp_ajax_regenerate_listing_category_terms", array(
				$this,
				"regenerate_listing_category_terms"
			) );
			add_action( "wp_ajax_nopriv_regenerate_listing_category_terms", array( $this, "deny_access_to_guest" ) );

			add_action( 'wp_ajax_toggle_listing_features', array( $this, 'toggle_listing_features' ) );
			add_action( 'wp_ajax_nopriv_toggle_listing_features', array( $this, 'deny_access_to_guest' ) );

			add_action( 'wp_ajax_add_new_listing_badge', array($this, 'add_new_listing_badge') );
			add_action( 'wp_ajax_nopriv_add_new_listing_badge', array( $this, 'deny_access_to_guest' ) );

			// sold view on listing post type
			add_action( "views_edit-listings",  array( $this, "add_sold_view" ) );
			add_action( "views_edit-listings", array( $this, "add_for_sale_view" ) );
			add_action( "pre_get_posts", array( $this, "add_sold_view_query" ) );

			// remove cache loading on comparison page
			add_action( "template_redirect", array( $this, "compare_no_cache"), 1000 );

			add_action( "wp_ajax_add_name", array($this, "ajax_add_listing_category_term") );
			add_action( "wp_ajax_nopriv_add_name", array($this, "deny_access_to_guest") );

			add_action( "wp_ajax_delete_name", array($this, "ajax_delete_listing_category_term") );
			add_action( "wp_ajax_nopriv_delete_name", array($this, "deny_access_to_guest") );

			// filters
			add_filter( 'manage_edit-listings_columns', array( $this, 'add_new_listings_columns' ) );
			add_filter( 'manage_edit-listings_sortable_columns', array( $this, 'order_column_register_sortable' ) );

			add_filter( "wp_mail_from", array( $this, "auto_filter_email_address" ) );
			add_filter( "wp_mail_from_name", array( $this, "auto_filter_email_name" ) );

			add_filter( 'image_size_names_choose', array($this, 'automotive_image_size_select') );
		}

		/**
		 * Generic function to deny access to guests (or hackers)
		 */
		public function deny_access_to_guest() {
			die( "Access Denied" );
		}

		/**
		 * Used to grab listing categories from the public var
		 *
		 * @param bool|false $multi_options
		 *
		 * @return array|mixed
		 */
		public function get_listing_categories( $multi_options = false, $lang_code = false ) {
			$option = $this->listing_categories;

			// if WPML lang code is set load different options
			if ( $lang_code ) {
				$option = $this->get_listing_categories_option( $lang_code );
			}

			if ( $multi_options == false && isset( $option['options'] ) && ! is_string( $option['options'] ) ) {
				unset( $option['options'] );
			}

			return $option;
		}

		/**
		 * Grabs a single listing category
		 *
		 * @param $category
		 *
		 * @return array
		 */
		public function get_single_listing_category( $category ) {
			$current_categories = $this->get_listing_categories( true );

			if ( ! isset( $current_categories[ $category ] ) && empty( $current_categories[ $category ] ) ) {
				$return = array();
			} else {
				$return = $current_categories[ $category ];
			}

			return $return;
		}

		public function set_single_listing_category( $category, $is_options = false ) {
			$current_categories = $this->get_listing_categories( true );

			$current_categories[ ( $is_options == true ? "options" : $category['slug'] ) ] = $category;

			$this->update_listing_categories($current_categories);
		}

		/**
		 * Gets only the filterable listing categories
		 *
		 * @return array
		 */
		public function get_filterable_listing_categories() {
			$current_categories    = $this->get_listing_categories();
			$filterable_categories = array();

			if ( $current_categories != false ) {
				if ( is_array( $current_categories ) && ! empty( $current_categories ) ) {
					foreach ( $current_categories as $key => $category ) {
						if ( isset( $category['filterable'] ) && $category['filterable'] == 1 ) {
							$filterable_categories[ $key ] = $category;
						}
					}
				}
			}

			return $filterable_categories;
		}

		/**
		 * Get location specific categories
		 *
		 * @return string
		 */
		public function get_location_email_category() {
			$current_categories = $this->get_listing_categories();
			$return             = "";

			if ( is_array( $current_categories ) && ! empty( $current_categories ) ) {
				foreach ( $current_categories as $category ) {
					if ( isset( $category['location_email'] ) && $category['location_email'] == 1 ) {
						$return = $category['slug'];
					}
				}
			}

			return $return;
		}

		/**
		 * Gets categories that are used as columns
		 *
		 * @return array|string
		 */
		public function get_column_categories() {
			$current_categories = $this->get_listing_categories();
			$return             = "";

			if ( is_array( $current_categories ) && ! empty( $current_categories ) ) {
				foreach ( $current_categories as $category ) {
					if ( isset( $category['column'] ) && $category['column'] == 1 ) {
						$return[] = $category;
					}
				}
			}

			return $return;
		}

		/**
		 * Gets the categories to be used in the listing preview area
		 *
		 * @return array
		 */
		public function get_use_on_listing_categories() {
			$use_on_categories  = array();
			$current_categories = $this->get_listing_categories();

			if ( $current_categories != false ) {
				foreach ( $current_categories as $category ) {
					if ( isset( $category['use_on_listing'] ) && $category['use_on_listing'] == 1 ) {
						$use_on_categories[ $category['singular'] ] = $category;
					}
				}
			}

			return $use_on_categories;
		}

		public function get_listing_categories_to_redux_select($lang_code = false){
			$return             = array();
			$listing_categories = $this->get_listing_categories(false, $lang_code );

			if(!empty($listing_categories)){
				foreach($listing_categories as $key => $category){
					if(is_array($category)){
						$return[$key] = $category['singular'];
					}
				}
			}

			return $return;
		}

		public function current_listing_categories( $get = "" ) {
			if ( empty( $get ) ) {
				$get = $_GET;
			}

			$filterable_categories = $this->get_filterable_listing_categories();

			if ( ! empty( $filterable_categories ) ) {
				foreach ( $filterable_categories as $key => $category ) {
					$slug  = ( $category['slug'] == "year" ? "yr" : $category['slug'] );
					$terms = ( isset( $category['terms'] ) && ! empty( $category['terms'] ) ? $category['terms'] : "" );

					if ( isset( $get[ $slug ] ) && ! empty( $get[ $slug ] ) ) {
						if ( is_array( $get[ $slug ] ) && ! empty( $get[ $slug ][0] ) && ! empty( $get[ $slug ][1] ) && isset( $terms[ $get[ $slug ][0] ] ) && isset( $terms[ $get[ $slug ][1] ] ) ) {
							$this->current_categories[ $slug ] = $get[ $slug ];

						} elseif ( is_array( $get[ $slug ] ) && ( ! empty( $get[ $slug ][0] ) && empty( $get[ $slug ][1] ) ) && isset( $terms[ $get[ $slug ][0] ] ) ) {
							$this->current_categories[ $slug ] = $get[ $slug ][0];

						} elseif ( is_array( $get[ $slug ] ) && ( ! empty( $get[ $slug ][1] ) && empty( $get[ $slug ][0] ) ) && isset( $terms[ $get[ $slug ][1] ] ) ) {
							$this->current_categories[ $slug ] = $get[ $slug ][1];

						} elseif ( is_array( $terms ) &&
						           isset($get[ $slug ]) &&
						           !empty($get[ $slug ]) &&
						           !is_array($get[ $slug ]) &&
						           isset( $terms[ $get[ $slug ] ] ) &&
						           !empty($terms[ $get[ $slug ] ]) ) {
							$this->current_categories[ $slug ] = $get[ $slug ];

						}
					}
				}
			}
		}

		public function get_listing_meta( $post_id ) {
			$all_post_meta = get_post_meta_all( $post_id );

			if ( isset( $all_post_meta['listing_options'] ) && ! empty( $all_post_meta['listing_options'] ) ) {
				$all_post_meta['listing_options'] = @unserialize( unserialize( $all_post_meta['listing_options'] ) );
			}

			if ( isset( $all_post_meta['location_map'] ) && ! empty( $all_post_meta['location_map'] ) ) {
				$all_post_meta['location_map'] = @unserialize( $all_post_meta['location_map'] );
			}

			if ( isset( $all_post_meta['gallery_images'] ) && ! empty( $all_post_meta['gallery_images'] ) ) {
				$all_post_meta['gallery_images'] = @unserialize( $all_post_meta['gallery_images'] );
			}

			if ( isset( $all_post_meta['multi_options'] ) && ! empty( $all_post_meta['multi_options'] ) ) {
				$all_post_meta['multi_options'] = @unserialize( $all_post_meta['multi_options'] );
			}

			return $all_post_meta;
		}

		/**
		 * Starts the session for import pages
		 */
		function import_start_session() {
			$current_page = ( isset( $_GET['page'] ) && ! empty( $_GET['page'] ) ? $_GET['page'] : "" );

			if ( ! empty( $current_page ) && ( $current_page == "file-import" || $current_page == "vin-import" ) && ! session_id() ) {
				session_start();
			}
		}

		/**
		 * Generates a URL safe version of any string
		 *
		 * @param $text
		 *
		 * @return mixed|string
		 */
		static public function slugify( $text ) {
			if ( ! empty( $text ) && is_string( $text ) ) {
				$char_map = array(
					// Latin
					'À'  => 'A',
					'Á'  => 'A',
					'Â'  => 'A',
					'Ã'  => 'A',
					'Ä'  => 'A',
					'Å'  => 'A',
					'Æ'  => 'AE',
					'Ç'  => 'C',
					'È'  => 'E',
					'É'  => 'E',
					'Ê'  => 'E',
					'Ë'  => 'E',
					'Ì'  => 'I',
					'Í'  => 'I',
					'Î'  => 'I',
					'Ï'  => 'I',
					'Ð'  => 'D',
					'Ñ'  => 'N',
					'Ò'  => 'O',
					'Ó'  => 'O',
					'Ô'  => 'O',
					'Õ'  => 'O',
					'Ö'  => 'O',
					'Ő'  => 'O',
					'Ø'  => 'O',
					'Ù'  => 'U',
					'Ú'  => 'U',
					'Û'  => 'U',
					'Ü'  => 'U',
					'Ű'  => 'U',
					'Ý'  => 'Y',
					'Þ'  => 'TH',
					'ß'  => 'ss',
					'à'  => 'a',
					'á'  => 'a',
					'â'  => 'a',
					'ã'  => 'a',
					'ä'  => 'a',
					'å'  => 'a',
					'æ'  => 'ae',
					'ç'  => 'c',
					'è'  => 'e',
					'é'  => 'e',
					'ê'  => 'e',
					'ë'  => 'e',
					'ì'  => 'i',
					'í'  => 'i',
					'î'  => 'i',
					'ï'  => 'i',
					'ð'  => 'd',
					'ñ'  => 'n',
					'ò'  => 'o',
					'ó'  => 'o',
					'ô'  => 'o',
					'õ'  => 'o',
					'ö'  => 'o',
					'ő'  => 'o',
					'ø'  => 'o',
					'ù'  => 'u',
					'ú'  => 'u',
					'û'  => 'u',
					'ü'  => 'u',
					'ű'  => 'u',
					'ý'  => 'y',
					'þ'  => 'th',
					'ÿ'  => 'y',

					// Greek
					'Α'  => 'A',
					'Β'  => 'B',
					'Γ'  => 'G',
					'Δ'  => 'D',
					'Ε'  => 'E',
					'Ζ'  => 'Z',
					'Η'  => 'H',
					'Θ'  => '8',
					'Ι'  => 'I',
					'Κ'  => 'K',
					'Λ'  => 'L',
					'Μ'  => 'M',
					'Ν'  => 'N',
					'Ξ'  => '3',
					'Ο'  => 'O',
					'Π'  => 'P',
					'Ρ'  => 'R',
					'Σ'  => 'S',
					'Τ'  => 'T',
					'Υ'  => 'Y',
					'Φ'  => 'F',
					'Χ'  => 'X',
					'Ψ'  => 'PS',
					'Ω'  => 'W',
					'Ά'  => 'A',
					'Έ'  => 'E',
					'Ί'  => 'I',
					'Ό'  => 'O',
					'Ύ'  => 'Y',
					'Ή'  => 'H',
					'Ώ'  => 'W',
					'Ϊ'  => 'I',
					'Ϋ'  => 'Y',
					'α'  => 'a',
					'β'  => 'b',
					'γ'  => 'g',
					'δ'  => 'd',
					'ε'  => 'e',
					'ζ'  => 'z',
					'η'  => 'h',
					'θ'  => '8',
					'ι'  => 'i',
					'κ'  => 'k',
					'λ'  => 'l',
					'μ'  => 'm',
					'ν'  => 'n',
					'ξ'  => '3',
					'ο'  => 'o',
					'π'  => 'p',
					'ρ'  => 'r',
					'σ'  => 's',
					'τ'  => 't',
					'υ'  => 'y',
					'φ'  => 'f',
					'χ'  => 'x',
					'ψ'  => 'ps',
					'ω'  => 'w',
					'ά'  => 'a',
					'έ'  => 'e',
					'ί'  => 'i',
					'ό'  => 'o',
					'ύ'  => 'y',
					'ή'  => 'h',
					'ώ'  => 'w',
					'ς'  => 's',
					'ϊ'  => 'i',
					'ΰ'  => 'y',
					'ϋ'  => 'y',
					'ΐ'  => 'i',

					// Turkish
					'Ş'  => 'S',
					'İ'  => 'I',
					'Ç'  => 'C',
					'Ü'  => 'U',
					'Ö'  => 'O',
					'Ğ'  => 'G',
					'ş'  => 's',
					'ı'  => 'i',
					'ç'  => 'c',
					'ü'  => 'u',
					'ö'  => 'o',
					'ğ'  => 'g',

					// Russian
					'А'  => 'A',
					'Б'  => 'B',
					'В'  => 'V',
					'Г'  => 'G',
					'Д'  => 'D',
					'Е'  => 'E',
					'Ё'  => 'Yo',
					'Ж'  => 'Zh',
					'З'  => 'Z',
					'И'  => 'I',
					'Й'  => 'J',
					'К'  => 'K',
					'Л'  => 'L',
					'М'  => 'M',
					'Н'  => 'N',
					'О'  => 'O',
					'П'  => 'P',
					'Р'  => 'R',
					'С'  => 'S',
					'Т'  => 'T',
					'У'  => 'U',
					'Ф'  => 'F',
					'Х'  => 'H',
					'Ц'  => 'C',
					'Ч'  => 'Ch',
					'Ш'  => 'Sh',
					'Щ'  => 'Sh',
					'Ъ'  => '',
					'Ы'  => 'Y',
					'Ь'  => '',
					'Э'  => 'E',
					'Ю'  => 'Yu',
					'Я'  => 'Ya',
					'а'  => 'a',
					'б'  => 'b',
					'в'  => 'v',
					'г'  => 'g',
					'д'  => 'd',
					'е'  => 'e',
					'ё'  => 'yo',
					'ж'  => 'zh',
					'з'  => 'z',
					'и'  => 'i',
					'й'  => 'j',
					'к'  => 'k',
					'л'  => 'l',
					'м'  => 'm',
					'н'  => 'n',
					'о'  => 'o',
					'п'  => 'p',
					'р'  => 'r',
					'с'  => 's',
					'т'  => 't',
					'у'  => 'u',
					'ф'  => 'f',
					'х'  => 'h',
					'ц'  => 'c',
					'ч'  => 'ch',
					'ш'  => 'sh',
					'щ'  => 'sh',
					'ъ'  => '',
					'ы'  => 'y',
					'ь'  => '',
					'э'  => 'e',
					'ю'  => 'yu',
					'я'  => 'ya',

					// Ukrainian
					'Є'  => 'Ye',
					'І'  => 'I',
					'Ї'  => 'Yi',
					'Ґ'  => 'G',
					'є'  => 'ye',
					'і'  => 'i',
					'ї'  => 'yi',
					'ґ'  => 'g',

					// Czech
					'Č'  => 'C',
					'Ď'  => 'D',
					'Ě'  => 'E',
					'Ň'  => 'N',
					'Ř'  => 'R',
					'Š'  => 'S',
					'Ť'  => 'T',
					'Ů'  => 'U',
					'Ž'  => 'Z',
					'č'  => 'c',
					'ď'  => 'd',
					'ě'  => 'e',
					'ň'  => 'n',
					'ř'  => 'r',
					'š'  => 's',
					'ť'  => 't',
					'ů'  => 'u',
					'ž'  => 'z',

					// Polish
					'Ą'  => 'A',
					'Ć'  => 'C',
					'Ę'  => 'e',
					'Ł'  => 'L',
					'Ń'  => 'N',
					'Ó'  => 'o',
					'Ś'  => 'S',
					'Ź'  => 'Z',
					'Ż'  => 'Z',
					'ą'  => 'a',
					'ć'  => 'c',
					'ę'  => 'e',
					'ł'  => 'l',
					'ń'  => 'n',
					'ó'  => 'o',
					'ś'  => 's',
					'ź'  => 'z',
					'ż'  => 'z',

					// Latvian
					'Ā'  => 'A',
					'Č'  => 'C',
					'Ē'  => 'E',
					'Ģ'  => 'G',
					'Ī'  => 'i',
					'Ķ'  => 'k',
					'Ļ'  => 'L',
					'Ņ'  => 'N',
					'Š'  => 'S',
					'Ū'  => 'u',
					'Ž'  => 'Z',
					'ā'  => 'a',
					'č'  => 'c',
					'ē'  => 'e',
					'ģ'  => 'g',
					'ī'  => 'i',
					'ķ'  => 'k',
					'ļ'  => 'l',
					'ņ'  => 'n',
					'š'  => 's',
					'ū'  => 'u',
					'ž'  => 'z',

					// Vietnamese
					'ớ'  => 'o',
					'ặ'  => 'a',
					'ư'  => 'u',
					'ẹ'  => 'e',
					'ắ'  => 'a',
					'đ'  => 'd',
					'ử'  => 'u',
					'ả'  => 'a',
					'đ'  => 'd',
					'ồ'  => 'o',
					'ổ'  => 'o',

					// Hebrew
					'ב'=>'b',
					'ג'=>'g',
					'ד'=>'d',
					'ה'=>'h',
					'ו'=>'v',
					'ז'=>'z',
					'ח'=>'h',
					'ט'=>'t',
					'י'=>'y',
					'כ'=>'k',
					'כּ'=>'k',
					'ך'=>'kh',
					'ל'=>'l',
					'מ'=>'m',
					'ם'=>'m',
					'נ'=>'n',
					'ן'=>'n',
					'ס'=>'s',
					'פ'=>'ph',
					'ף'=>'p',
					'פּ'=>'p',
					'צ'=>'ts',
					'ץ'=>'ts',
					'ק'=>'q',
					'ר'=>'r',
					'ש'=>'sh',
					'שׂ'=>'sh',
					'שׁ'=>'sh',
					'ת'=>'t',
					'תּ'=>'t',
					'א'=>'x',
					'ה' =>'n',
					'ח'=>'n',
					'פ'=>'g',
					'מ'=>'a',

					// arabic
					'ـا'=>'a',
					'ـب'=>'b',
					'ـبـ'=>'b',
					'بـ'=>'b',
					'ب'=>'b',
					'ـت'=>'t',
					'ـتـ' =>'t',
					'تـ'=>'t',
					'ت'=>'t',
					'ـث'=>'th',
					'ـثـ'=>'th',
					'ثـ'=>'th',
					'ث'=>'th',
					'ـج'=>'g',
					'ـجـ'=>'g',
					'جـ'=>'g',
					'ج'=>'g',
					'ـح	'=>'h',
					'ـحـ'=>'h',
					'حـ'=>'h',
					'ح'=>'h',
					'ـخ'=>'x',
					'ـخـ'=>'x',
					'	خـ'=>'x',
					'خ'=>'x',
					'ـد'=>'d',
					'د'=>'d',
					'ـذ'=>'d',
					'ذ'=>'d',
					'ـر'=>'r',
					'ر'=>'r',
					'ـز'=>'z',
					'ز'=>'z',
					'ـس'=>'s',
					'ـسـ'=>'s',
					'سـ'=>'s',
					'س'=>'s',
					'ـش'=>'sh',
					'ـشـ'=>'sh',
					'شـ'=>'sh',
					'ش'=>'sh',
					'ـص'=>'s',
					'ـصـ'=>'s',
					'صـ'=>'s',
					'ص'=>'s',
					'ـض'=>'d',
					'ـضـ'=>'d',
					'ضـ'=>'d',
					'ض'=>'d',
					'ـط'=>'t',
					'ـطـ'=>'t',
					'طـ'=>'t',
					'ط'=>'t',
					'ـظ'=>'z',
					'ـظـ'=>'z',
					'ظـ'=>'z',
					'ظ'=>'z',
					'ـع'=>'a',
					'ـعـ'=>'a',
					'عـ'=>'a',
					'ع'=>'a',
					'	ـغ'=>'g',
					'ـغـ'=>'g',
					'غـ'=>'g',
					'غ'=>'g',
					'ـف'=>'f',
					'ـفـ'=>'f',
					'فـ'=>'f',
					'ف'=>'f',
					'ـق'=>'q',
					'ـقـ'=>'q',
					'قـ'=>'q',
					'ق'=>'q',
					'ـك'=>'k',
					'ـكـ'=>'k',
					'كـ'=>'k',
					'ك'=>'k',
					'ـل'=>'l',
					'ـلـ'=>'l',
					'لـ'=>'l',
					'ل'=>'l',
					'ـم'=>'m',
					'ـمـ'=>'m',
					'مـ'=>'m',
					'م'=>'m',
					'ـن'=>'n',
					'ـنـ'=>'n',
					'نـ'=>'n',
					'ن'=>'n',
					'ـه'=>'h',
					'ـهـ'=>'h',
					'هـ'=>'h',
					'ه'=>'h',
					'ـو'=>'w',
					'و'=>'w',
					'ـي'=>'y',
					'ـيـ'=>'y',
					'يـ'=>'y',
					'ي'=>'y',

					// Symbols
					'©'  => 'c',
					'®'  => 'r',
				);

				$text = str_replace( array_keys( $char_map ), $char_map, $text );

				// replace non letter or digits by -
				$text = preg_replace( '~[^\\pL\d]+~u', '-', $text );

				// trim
				$text = trim( $text, '-' );

				// transliterate
				$text = iconv( 'UTF-8', 'ASCII//TRANSLIT', utf8_encode( $text ) );

				// lowercase
				$text = strtolower( $text );

				// remove unwanted characters
				$text = preg_replace( '~[^-\w]+~', '', $text );

				if ( empty( $text ) ) {
					return 'n-a';
				}
			}

			return $text;
		}

		/**
		 * Used for changing the option name when the plugin is used with WPML
		 *
		 * @param $option
		 *
		 * @return string
		 */
		public function option_name_suffix( $option ) {
			if ( defined( "ICL_LANGUAGE_CODE" ) && ICL_LANGUAGE_CODE != "en" ) {
				$option .= "_" . ICL_LANGUAGE_CODE;
			}

			return $option;
		}

		/**
		 * Add image sizes for plugin
		 */
		public function automotive_image_sizes() {
			global $slider_thumbnails;

			add_image_size( "related_portfolio", 270, 140, true );
			add_image_size( "auto_thumb", $slider_thumbnails['width'], $slider_thumbnails['height'], true );
			add_image_size( "auto_slider", $slider_thumbnails['slider']['width'], $slider_thumbnails['slider']['height'], true );
			add_image_size( "auto_listing", $slider_thumbnails['listing']['width'], $slider_thumbnails['listing']['height'], true );
			add_image_size( "auto_portfolio", 770, 450, true );
		}


		/**
		 * Add the custom image sizes into the media picker dropdown
		 *
		 * @param $sizes
		 *
		 * @return array
		 */
		function automotive_image_size_select( $sizes ) {
			return array_merge( $sizes, array(
				'auto_thumb'        => __('Automotive Thumb', 'listings'),
				'auto_slider'       => __('Automotive Slider', 'listings'),
				'auto_listing'      => __('Automotive Listing', 'listings'),
				'auto_portfolio'    => __('Automotive Portfolio', 'listings'),
			) );
		}

		/**
		 * Load the Redux Framework
		 */
		public function load_redux() {
			$listing_features = get_option( "listing_features" );

			if ( isset( $listing_features ) && $listing_features != "disabled" ) {
				include( LISTING_HOME . "ReduxFramework/loader.php" );

				// Redux Admin Panel
				if ( ! class_exists( 'ReduxFramework' ) && file_exists( LISTING_HOME . 'ReduxFramework/ReduxCore/framework.php' ) ) {
					require_once( LISTING_HOME . 'ReduxFramework/ReduxCore/framework.php' );
				}
				if ( file_exists( LISTING_HOME . 'ReduxFramework/options/options.php' ) ) {
					require_once( LISTING_HOME . 'ReduxFramework/options/options.php' );
				}
			}
		}

		/**
		 * Verify ThemeForest credentials
		 *
		 * @param string $tf_username
		 * @param string $tf_api
		 *
		 * @return bool
		 */
		public function validate_themeforest_creds( $tf_username = "", $tf_api = "" ) {
			global $awp_options;

			// use default themeforest username
			if ( empty( $tf_username ) ) {
				$tf_username = $awp_options['themeforest_name'];
			}

			// use default themeforest api
			if ( empty( $tf_api ) ) {
				$tf_api = $awp_options['themeforest_api'];
			}

			$options = array(
				'http' => array(
					'method' => "GET",
					'header' => "Accept-language: en\r\n" .
					            "Cookie: foo=bar\r\n" .
					            "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n"
				)
			);

			$context = stream_context_create( $options );

			$themes = @file_get_contents( "http://marketplace.envato.com/api/edge/" . $tf_username . "/" . $tf_api . "/wp-list-themes.json", false, $context );
			$themes = json_decode( $themes );

			$purchased_auto = false;

			if ( ! empty( $themes ) && ! empty( $themes->{'wp-list-themes'} ) ) {
				foreach ( $themes->{'wp-list-themes'} as $theme ) {
					if ( $theme->item_id == 9210971 ) {
						$purchased_auto = true;
					}
				}
			}

			return $purchased_auto;
		}

		/**
		 * Converts listing data to new, more usable system
		 */
		public function convert_listing_data() {
			$slug_generated = get_option( "auto_slugs_generated" );

			if ( ! $slug_generated ) {

				$listing_categories = $this->get_listing_categories( true );

				if ( ! empty( $listing_categories ) ) {
					$new_listing_categories = array();

					foreach ( $listing_categories as $key => $category ) {
						// not options
						if ( $key != "options" ) {
							$slug = $this->slugify( $category['singular'] );

							$new_listing_categories[ $slug ]         = $listing_categories[ $key ];
							$new_listing_categories[ $slug ]['slug'] = $slug;
						} else {
							$new_listing_categories['options'] = $category;
							$slug                              = "options";
						}

						// update terms with new slugs in the key
						if ( ! empty( $category['terms'] ) ) {
							foreach ( $category['terms'] as $term_key => $term_value ) {
								$new_listing_categories[ $slug ]['terms'][ $this->slugify( $term_value ) ] = $term_value;
								unset( $new_listing_categories[ $slug ]['terms'][ $term_key ] );
							}
						}
					}

					// now convert listing terms
					$all_listings = get_posts( array( "post_type" => "listings", "posts_per_page" => - 1 ) );

					if ( ! empty( $all_listings ) ) {
						// D($all_listings);
						foreach ( $all_listings as $key => $listing ) {
							// foreach listing categories
							foreach ( $new_listing_categories as $category_key => $category_value ) {
								$category_post_key  = strtolower( str_replace( array(
									" ",
									"."
								), "_", $category_value['singular'] ) );
								$category_post_meta = get_post_meta( $listing->ID, $category_post_key, true );

								// now update with new meta
								update_post_meta( $listing->ID, $category_value['slug'], $category_post_meta );
							}
						}
					}

					// now convert the orderby
					$listing_orderby = get_option( "listing_orderby" );

					if ( ! empty( $listing_orderby ) ) {
						$new_orderby = array();

						foreach ( $listing_orderby as $order_category => $order_type ) {
							$new_orderby[ $this->slugify( $order_category ) ] = $order_type;
						}

						update_option( "listing_orderby", $new_orderby );
					}

					// store a backup of listing categories, just in case
					update_option( "auto_backup_listing_categories", $listing_categories );
					update_option( "listing_categories", $new_listing_categories );
				}

				update_option( "auto_slugs_generated", true );
			}
		}

		/**
		 * Used to generated the dependancy option for existing listings since automatic
		 * dependancies were only introduced in version 6.0
		 *
		 */
		public function generate_dependancy_option( $force_regenerate = false ) {
			$dependancies_generated = get_option( $this->option_name_suffix( "dependancies_generated" ) );

			if ( ! $dependancies_generated || $force_regenerate ) {
				$all_listings = get_posts( array(
					"post_type"        => "listings",
					"posts_per_page"   => - 1,
					"suppress_filters" => 0
				) );

				$dependancies      = array();
				$sold_dependancies = array();

				// additional categories
				$additional_categories = $this->get_additional_categories();

				if ( ! empty( $all_listings ) ) {
					foreach ( $all_listings as $key => $listing ) {
						$listing_categories = $this->get_listing_categories( false );

						// don't include sold terms
						$car_sold = get_post_meta( $listing->ID, "car_sold", true );

						if(!empty($listing_categories)) {
							foreach ( $listing_categories as $category_key => $category ) {
								if ( isset( $category['slug'] ) && ! empty( $category['slug'] ) ) {
									$post_meta = get_post_meta( $listing->ID, $category['slug'], true );

									if ( $car_sold == 2 ) {
										$dependancies[ $listing->ID ][ $category['slug'] ] = array( $this->slugify( $post_meta ) => $post_meta );
									} else {
										$sold_dependancies[ $listing->ID ][ $category['slug'] ] = array( $this->slugify( $post_meta ) => $post_meta );
									}
								}
							}
						}

                        if ( ! empty( $additional_categories ) ) {
                            foreach ( $additional_categories as $additional_category ) {
                                $check_handle = str_replace( " ", "_", mb_strtolower( $additional_category ) );
	                            $post_meta    = get_post_meta( $listing->ID, $check_handle, true );

                                if(!empty($check_handle) && !empty($post_meta)) {
	                                if ( $car_sold == 2 ) {
		                                $dependancies[ $listing->ID ][ $check_handle ] = array( 1 => 1 );
	                                } else {
		                                $sold_dependancies[ $listing->ID ][ $check_handle ] = array( 1 => 1 );
	                                }
                                }
                            }
                        }

					}
				}

				update_option( $this->option_name_suffix( "dependancies_generated" ), $dependancies );
				update_option( $this->option_name_suffix( "sold_dependancies_generated" ), $sold_dependancies );
			}
		}

		/**
		 * Used to update the dependancy option in the database after a user updates a listing
		 *
		 * @param $listing_id
		 * @param $listing_categories
		 *
		 */
		public function update_dependancy_option( $listing_id, $listing_categories, $car_sold = false ) {
			if($car_sold == false) {
				$car_sold = get_post_meta( $listing_id, "car_sold", true );
			}

			$dependancy_option      = (isset($car_sold) && $car_sold == 1 ? "sold_" : "") . "dependancies_generated";
			$dependancies_generated = get_option( $this->option_name_suffix( $dependancy_option ) );

			if ( is_string( $listing_categories ) && $listing_categories == "delete" ) {
				unset( $dependancies_generated[ $listing_id ] );
			} else {
				if ( $dependancies_generated ) {
					$dependancies_generated[ $listing_id ] = $listing_categories;
				}
			}

			update_option( $this->option_name_suffix( $dependancy_option ), $dependancies_generated );


			// if car sold then remove it from other array and vice versa
			$other_dependancies_name = (isset($car_sold) && $car_sold == 1 ? "" : "sold_") . "dependancies_generated";
			$other_dependancies = get_option( $this->option_name_suffix($other_dependancies_name) );

			if(isset($other_dependancies[$listing_id])){
				unset($other_dependancies[$listing_id]);
			}

			update_option( $this->option_name_suffix($other_dependancies_name), $other_dependancies);
		}


		/**
		 * Used for updating the listing category dropdowns with terms that are used
		 *
		 * @param array $current_categories
		 *
		 * @return array
		 */
		public function process_dependancies( $current_categories = array(), $is_sold = false, $min_max_ignore = array() ) {
			$dependancy_option      = ($is_sold ? "sold_dependancies_generated" : "dependancies_generated");
			$dependancies_generated = get_option( $this->option_name_suffix( $dependancy_option ) );
			$return                 = array();

			if ( ! empty( $current_categories ) ) {
				// year workaround
				if ( isset( $current_categories['yr'] ) && ! empty( $current_categories['yr'] ) ) {
					$current_categories['year'] = $current_categories['yr'];
					unset( $current_categories['yr'] );
				}

				// remove unnecessary vars
				// Only sort through listing category vars
				$valid_current_categories = array();
				foreach ( $this->get_listing_categories() as $category_key => $category_value ) {
					// min and max empty val check
					if ( isset( $current_categories[ $category_value['slug'] ] ) && is_array( $current_categories[ $category_value['slug'] ] ) &&
					     ! empty( $current_categories[ $category_value['slug'] ][0] ) && ! empty( $current_categories[ $category_value['slug'] ][1] )
					) {
						$valid_current_categories[ $category_value['slug'] ] = $current_categories[ $category_value['slug'] ];
					} elseif ( isset( $current_categories[ $category_value['slug'] ] ) && ! is_array( $current_categories[ $category_value['slug'] ] ) && ! empty( $current_categories[ $category_value['slug'] ] ) ) {
						$valid_current_categories[ $category_value['slug'] ] = $current_categories[ $category_value['slug'] ];
					}
				}

				// additional categories
				$additional_categories = $this->get_additional_categories();

				if(!empty($additional_categories)){
				    foreach($additional_categories as $additional_category){
					    $check_handle = str_replace( " ", "_", mb_strtolower( $additional_category ) );

				        if(isset($current_categories[$check_handle]) && $current_categories[$check_handle]){
				            $valid_current_categories[$check_handle] = 1;
                        }
                    }
                }

				// sold vehicles only?
				if(isset($current_categories['sold_only']) && $current_categories['sold_only']){
					$valid_current_categories['car_sold'] = 1;
				}

				$current_categories = $valid_current_categories;
			}

			if ( ! empty( $dependancies_generated ) ) {
				$listing_category_settings = $this->get_filterable_listing_categories();
				
				foreach ( $dependancies_generated as $listing_id => $categories ) {

					if ( ! empty( $current_categories ) ) {
						$has_required_values = false;

						foreach ( $current_categories as $current_key => $current_value ) {

							if ( isset( $listing_category_settings[ $current_key ]['compare_value'] ) && $listing_category_settings[ $current_key ]['compare_value'] != "=" ) {
								$has_required_values = true;
								break;
							}

							if ( ! empty( $categories ) && is_array( $categories ) && isset( $categories[ $current_key ] ) ) {

								// make sure min/max value is in between
								if ( is_array( $current_value ) ) {
									reset( $categories[ $current_key ] );
									$key = key( $categories[ $current_key ] );

									$min = ( isset( $current_value[0] ) && ! empty( $current_value[0] ) ? $current_value[0] : "" );
									$max = ( isset( $current_value[1] ) && ! empty( $current_value[1] ) ? $current_value[1] : "" );

									// keep existing min/max values available in dropdown
									$return[ $current_key ][ $min ] = $min;
									$return[ $current_key ][ $max ] = $max;

									if ( ! empty( $min ) && ! empty( $max ) && ( ( $min <= $key ) && ( $key <= $max ) ) ) {
										$has_required_values = true;
									}
								} elseif ( is_array( $categories[ $current_key ] ) ) {
									reset( $categories[ $current_key ] );
									$key = key( $categories[ $current_key ] );

									if ( $key != $current_value ) {
										$has_required_values = false;
										break;
									} else {
										$has_required_values = true;
									}
								}
							}

							// if car sold
							if($current_key == "car_sold" && $current_value == 1){
								$car_sold = get_post_meta($listing_id, "car_sold", true);

								if($car_sold == 1){
									$has_required_values = true;
								} else {
									$has_required_values = false;
								}
							}
						}

						// current listing has all required dependancies
						if ( $has_required_values ) {
							foreach ( $categories as $category_key => $category_value ) {

								// if not array, declare
								if ( ! isset( $return[ $category_key ] ) || ! is_array( $return[ $category_key ] ) ) {
									$return[ $category_key ] = ( isset( $return[ $category_key ] ) ? $return[ $category_key ] : array() );
								}

								// make sure no empty values make it into available terms
								reset( $category_value );
								$key = key( $category_value );

								$select_label = $category_value[ $key ];

								// apply currency or compare values to value
								if ( isset( $listing_category_settings[ $category_key ]['currency'] ) && $listing_category_settings[ $category_key ]['currency'] == 1 ) {
									$select_label = $this->format_currency( $select_label );
								}

								if ( isset( $listing_category_settings[ $category_key ]['compare_value'] ) && $listing_category_settings[ $category_key ]['compare_value'] != "=" && !in_array($category_key, $min_max_ignore) ) {
									$select_label = html_entity_decode( $listing_category_settings[ $category_key ]['compare_value'] ) . " " . $select_label;
								}

								if ( isset( $category_value[ $key ] ) && ! empty( $category_value[ $key ] ) && $category_value[ $key ] != "None" && ! in_array( $category_value[ $key ], $return[ $category_key ] ) ) {
									$return[ $category_key ][ $key ] = $select_label;
								}

							}
						}

					} else {
						//var_dump("ayy lmao"); D($categories);
						foreach ( $categories as $category_key => $category_value ) {
//							var_dump($category_value);

							// if not array, declare
							if ( ! isset( $return[ $category_key ] ) || ! is_array( $return[ $category_key ] ) ) {
								$return[ $category_key ] = array();
							}

							// make sure no empty values make it into available terms
							reset( $category_value );
							$key = key( $category_value );

							$select_label = $category_value[ $key ];

							// apply currency or compare values to value
							if ( isset( $listing_category_settings[ $category_key ]['currency'] ) && $listing_category_settings[ $category_key ]['currency'] == 1 ) {
								$select_label = $this->format_currency( $select_label );
							}

							if ( isset( $listing_category_settings[ $category_key ]['compare_value'] ) && $listing_category_settings[ $category_key ]['compare_value'] != "=" && !in_array($category_key, $min_max_ignore) ) {
								$select_label = html_entity_decode( $listing_category_settings[ $category_key ]['compare_value'] ) . " " . $select_label;
							}

							$category_value[ $key ] = addslashes($category_value[ $key ]);

							// checks and make sure value exists in backend
							if ( isset( $category_value[ $key ] ) && ! empty( $category_value[ $key ] ) && $category_value[ $key ] != "None" && ! in_array( $category_value[ $key ], $return[ $category_key ] ) && isset( $listing_category_settings[ $category_key ]['terms'] ) && is_array( $listing_category_settings[ $category_key ]['terms'] ) && in_array( $category_value[ $key ], $listing_category_settings[ $category_key ]['terms'] ) ) {
								$return[ $category_key ][ $key ] = $select_label;
							}
						}
					}
				}
			}

			// sort terms
			if ( ! empty( $return ) ) {
				foreach ( $return as $category_key => $category_value ) {
					// if compare value is present, use the terms specified by user
					if ( isset( $listing_category_settings[ $category_key ]['compare_value'] ) && $listing_category_settings[ $category_key ]['compare_value'] != "=" && isset($listing_category_settings[ $category_key ]['terms'])) {
						$category_value = $listing_category_settings[ $category_key ]['terms'];

						if ( ! empty( $category_value ) ) {
							foreach ( $category_value as $term_key => $term_value ) {
								// and re-format
								if ( isset( $listing_category_settings[ $category_key ]['currency'] ) && $listing_category_settings[ $category_key ]['currency'] == 1 ) {
									$category_value[ $term_key ] = $this->format_currency( $term_value );
								}

								if ( isset( $listing_category_settings[ $category_key ]['compare_value'] ) && $listing_category_settings[ $category_key ]['compare_value'] != "=" && !in_array($category_key, $min_max_ignore) ) {
									$category_value[ $term_key ] = html_entity_decode( $listing_category_settings[ $category_key ]['compare_value'] ) . " " . $category_value[ $term_key ];
								}
							}
						}
					}


					if ( isset( $listing_category_settings[ $category_key ]['sort_terms'] ) && $listing_category_settings[ $category_key ]['sort_terms'] == "desc" && is_array( $category_value ) ) {
						natsort( $category_value );

						if(isset($listing_category_settings[ $category_key ]['link_value']) && $listing_category_settings[ $category_key ]['link_value'] == "price"){
							uksort($category_value, 'strnatcasecmp');
						}

						$category_value = array_reverse( $category_value, true );
					} elseif ( is_array( $category_value ) ) {
						natsort( $category_value );

						if(isset($listing_category_settings[ $category_key ]['link_value']) && $listing_category_settings[ $category_key ]['link_value'] == "price"){
							uksort($category_value, 'strnatcasecmp');
						}
					}

					$return[ $category_key ] = $category_value;

					// tell js this is to be desc
					if(isset($listing_category_settings[$category_key]['sort_terms']) && $listing_category_settings[$category_key]['sort_terms'] == "desc"){
						$return[$category_key]['auto_term_order'] = "desc";
					}
				}
			}

			return $return;
		}

		/**
		 * Used to generate the listing dropdowns for the search shortcode, inventory dropdowns and widget dropdowns
		 *
		 * @param $category
		 * @param $prefix_text
		 * @param $select_class
		 * @param $options
		 * @param $options
		 * @param array $other_options
		 */
		public function listing_dropdown( $category, $prefix_text, $select_class, $options, $other_options = array() ) {
			$get_select = ( $category['slug'] == "year" ? "yr" : $category['slug'] );

			// variables altered by the $other_options
			$current_option = ( isset( $other_options['current_option'] ) && ! empty( $other_options['current_option'] ) ? $other_options['current_option'] : "" );
			$select_name    = ( isset( $other_options['select_name'] ) && ! empty( $other_options['select_name'] ) ? $other_options['select_name'] : $get_select );
			$select_label   = ( isset( $other_options['select_label'] ) && ! empty( $other_options['select_label'] ) ? $other_options['select_label'] : $prefix_text . " " . $category['plural'] );

			$is_min_max     = (substr($select_name, -2) == "[]" ? true : false);

			$no_options = __( "No options", "listings" );

			echo "<select name='" . ( $select_name == "year" ? "yr" : $select_name ) . "' class='" . $select_class . "' data-sort='" . $category['slug'] . "' data-prefix='" . $prefix_text . "' data-label-singular='" . $category['singular'] . "' data-label-plural='" . $category['plural'] . "' data-no-options='" . $no_options . "'" . ( $category['compare_value'] != "=" ? " data-compare-value='" . htmlspecialchars( $category['compare_value'], ENT_QUOTES , "UTF-8" ) . "'" : "" ) . ">";
			echo "<option value=''>" . $select_label . "</option>";

			if ( ! empty( $options ) ) {

				foreach ( $options as $term_key => $term_value ) {
					$on_select = $term_value;

                    if(isset($category['compare_value']) && $category['compare_value'] != "="){
                        $on_select = str_replace(html_entity_decode($category['compare_value']) . " ", "", $on_select);
                    }

					if($term_key != "auto_term_order") {
						echo "<option value='" . htmlentities( $term_value, ENT_QUOTES ) . "'" . ( isset( $current_option ) && is_string( $current_option ) ? selected( $current_option, $term_key, false ) : "" ) . " data-key='" . $term_key . "'>" . htmlentities( $on_select ) . "</option>\n";
					}
				}
			} else {
				echo "<option value=''>" . $no_options . "</option>";
			}

			echo "</select>";
		}

		public function get_attachment_id_from_url( $attachment_url = '' ) {
			global $wpdb;

			$attachment_id = false;

			// If there is no url, return.
			if ( '' == $attachment_url )
				return;

			// Get the upload directory paths
			$upload_dir_paths = wp_upload_dir();

			// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
			if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

				// If this is the URL of an auto-generated thumbnail, get the URL of the original image
				$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

				// Remove the upload path base directory from the attachment URL
				$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

				// Finally, run a custom database query to get the attachment ID from the modified attachment URL
				$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );

			}

			return $attachment_id;
		}

		public function get_video_id($url){
			// determine if youtube or vimeo
			$parsed_url = parse_url( $url );

			$youtube_urls = array("www.youtube.com", "youtube.com", "www.youtu.be", "youtu.be");
			$vimeo_urls   = array("www.vimeo.com", "vimeo.com");

			if ( in_array($parsed_url['host'], $youtube_urls) ) {
				preg_match( "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches );

				$return = (isset($matches[0]) && !empty($matches[0]) ? array("youtube", $matches[0]) : false);
			} elseif( in_array($parsed_url['host'], $vimeo_urls) ){
				preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $url, $matches);

				$return = (isset($matches[5]) && !empty($matches[5]) ? array("vimeo", $matches[5]) : false);
			} elseif( ($is_attachment = $this->get_attachment_id_from_url($url)) != false ){
				$return = array('self_hosted', $url);
			} else {
				$return = false;
			}

			return $return;
		}

		/**
		 * Generates the YouTube URL based on user options.
		 *
		 * @param $youtube_url
		 *
		 * @return mixed|string
		 */
		public function apply_youtube_options( $youtube_url ) {
			$youtube_options = ( isset( $this->lwp_options['youtube_video_options'] ) && ! empty( $this->lwp_options['youtube_video_options'] ) ? $this->lwp_options['youtube_video_options'] : array() );

			if ( isset( $youtube_options['rel'] ) && $youtube_options['rel'] == 0 ) {
				$youtube_url = add_query_arg( "rel", "0", $youtube_url );
			}
			if ( isset( $youtube_options['player_controls'] ) && $youtube_options['player_controls'] == 0 ) {
				$youtube_url = add_query_arg( "showinfo", "0", $youtube_url );
			}
			if ( isset( $youtube_options['title_actions'] ) && $youtube_options['title_actions'] == 0 ) {
				$youtube_url = add_query_arg( "controls", "0", $youtube_url );
			}
			if ( isset( $youtube_options['privacy'] ) && $youtube_options['privacy'] == 1 ) {
				$youtube_url = str_replace( "youtube.com", "youtube-nocookie.com", $youtube_url );
			}

			return $youtube_url;
		}

		/**
		 * Formats the price based on the user options
		 *
		 * @param $amount
		 *
		 * @return bool|string
		 */
		public function format_currency( $amount ) {
			if ( empty( $amount ) || is_array( $amount ) ) {
				return false;
			}

			$currency_symbol    = ( isset( $this->lwp_options['currency_symbol'] ) && ! empty( $this->lwp_options['currency_symbol'] ) ? $this->lwp_options['currency_symbol'] : "" );
			$decimal_amount     = ( isset( $this->lwp_options['currency_decimals'] ) && ! empty( $this->lwp_options['currency_decimals'] ) ? $this->lwp_options['currency_decimals'] : 0 );
			$decimal_separator  = ( isset( $this->lwp_options['currency_separator_decimal'] ) && ! empty( $this->lwp_options['currency_separator_decimal'] ) ? $this->lwp_options['currency_separator_decimal'] : "." );
			$thousand_separator = ( isset( $this->lwp_options['currency_separator'] ) && ! empty( $this->lwp_options['currency_separator'] ) ? $this->lwp_options['currency_separator'] : "," );

			if(isset($this->lwp_options['indian_number_system']) && $this->lwp_options['indian_number_system'] == 1){
				$explrestunits = "" ;
				$amount = preg_replace('/,+/', '', $amount);
				$words = explode(".", $amount);
				$des = "00";

				if(count($words)<=2) {
					$amount = $words[0];
					if ( count( $words ) >= 2 ) {
						$des = $words[1];
					}
					if ( strlen( $des ) < 2 ) {
						$des .= 0;
					} else {
						$des = substr( $des, 0, 2 );
					}
				}
				if(strlen($amount)>3) {
					$lastthree = substr( $amount, strlen( $amount ) - 3, strlen( $amount ) );
					$restunits = substr( $amount, 0, strlen( $amount ) - 3 ); // extracts the last three digits
					$restunits = ( strlen( $restunits ) % 2 == 1 ) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
					$expunit   = str_split( $restunits, 2 );
					for ( $i = 0; $i < sizeof( $expunit ); $i ++ ) {
						// creates each of the 2's group and adds a comma to the end
						if ( $i == 0 ) {
							$explrestunits .= (int) $expunit[ $i ] . ","; // if is first value , convert into integer
						} else {
							$explrestunits .= $expunit[ $i ] . ",";
						}
					}
					$thecash = $explrestunits . $lastthree;
				} else {
					$thecash = $amount;
				}

				$amount = $thecash;


			} elseif((isset($this->lwp_options['separators_functionality']) && $this->lwp_options['separators_functionality'] == 1) || !isset($this->lwp_options['separators_functionality'])) {
				$amount = number_format( (float) $amount, $decimal_amount, $decimal_separator, $thousand_separator );
			}

			$amount = ( ! isset( $this->lwp_options['currency_placement'] ) || $this->lwp_options['currency_placement'] ? $currency_symbol . $amount : $amount . $currency_symbol );

			return $amount;
		}

		/**
		 * Determines if tax is enabled or not
		 *
		 * @return bool
		 */
		public function is_tax_active() {
			return ( isset( $this->lwp_options['tax_functionality'] ) && ! empty( $this->lwp_options['tax_functionality'] ) ? $this->lwp_options['tax_functionality'] : false );
		}

		/**
		 * Detects if WPML is active
		 *
		 * @return bool
		 */
		public function is_wpml_active() {
			return ( defined( "ICL_LANGUAGE_CODE" ) ? true : false );
		}

		/**
		 * Detects if Yoast SEO is active
		 *
		 * @return bool
		 */
		public function is_yoast_active() {
			return ( defined( "WPSEO_VERSION" ) ? true : false );
		}

		//********************************************
		//	Post Type Functions
		//***********************************************************

		/**
		 * Register the Listings Post Type
		 */
		public function register_listing_post_type() {
			$this->get_listing_wp();

			$listing_features = get_option( "listing_features" );

			if(!isset($listing_features) || $listing_features != "disabled") {
				$labels = array(
					'name'               => __( 'Listings', 'listings' ),
					'singular_name'      => __( 'Listing', 'listings' ),
					'add_new'            => __( 'Add New', 'listings' ),
					'add_new_item'       => __( 'Add New Listing', 'listings' ),
					'edit_item'          => __( 'Edit Listing', 'listings' ),
					'new_item'           => __( 'New Listing', 'listings' ),
					'all_items'          => __( 'All Listings', 'listings' ),
					'view_item'          => __( 'View Listing', 'listings' ),
					'search_items'       => __( 'Search Listings', 'listings' ),
					'not_found'          => __( 'No listings found', 'listings' ),
					'not_found_in_trash' => __( 'No listings found in Trash', 'listings' ),
					'menu_name'          => __( 'Listings', 'listings' )
				);

				$args = array(
					'labels'             => $labels,
					'public'             => true,
					'publicly_queryable' => true,
					'show_ui'            => true,
					'show_in_menu'       => true,
					'query_var'          => true,
					'rewrite'            => array( 'slug' => ( isset( $this->lwp_options['listing_slug'] ) && ! empty( $this->lwp_options['listing_slug'] ) ? $this->lwp_options['listing_slug'] : 'listings' ) ),
					'capability_type'    => 'post',
					'hierarchical'       => false,
					'menu_position'      => null,
					'supports'           => array( 'title', 'editor', 'comments', 'excerpt', 'author' ),
					'has_archive'        => false
				);

				register_post_type( 'listings', $args );
			}
		}

		/**
		 * Adjust the Listings Post Type columns
		 *
		 * @param $columns
		 *
		 * @return mixed
		 */
		public function add_new_listings_columns( $columns ) {
			$new_columns['cb'] = '<input type="checkbox" />';

			if(isset($this->lwp_options['show_image_column']) && !empty($this->lwp_options['show_image_column'])) {
//				$new_columns['auto_image'] = __( 'Image', 'listings' );
				$columns = array("auto_image" => __( 'Image', 'listings' )) + $columns;
			}

			$new_columns['title'] = __( 'Title', 'listings' );

			$column_categories = $this->get_column_categories();

			if ( ! empty( $column_categories ) ) {
				foreach ( $column_categories as $column ) {
					$new_columns[ $column['slug'] ] = $column['singular'];
				}
			}

			$new_columns['date'] = __( 'Date', 'listings' );

//			return $new_columns;
			return array_merge($columns, $new_columns);
		}

		/**
		 * Gets the value for the custom columns
		 *
		 * @param $column_name
		 * @param $id
		 */
		public function manage_listings_columns( $column_name, $id ) {
			$return = "";

			if($column_name == "auto_image"){
				$images = get_post_meta( $id, "gallery_images", true);

				if(isset($images[0]) && !empty($images[0])){
					$return = $this->auto_image($images[0], "auto_thumb");
				}
			} else {
				$return = get_post_meta( $id, $column_name, true );
			}


			echo $return;
		}

		/**
		 * Registers the orderby for the custom columns
		 *
		 * @param $columns
		 *
		 * @return mixed
		 */
		public function order_column_register_sortable( $columns ) {
			$column_categories = $this->get_column_categories();

			if ( ! empty( $column_categories ) ) {
				foreach ( $column_categories as $column ) {
					$slug = $column['slug'];

					$columns[ $slug ] = $slug;
				}
			}

			return $columns;
		}

		/**
		 * Orders the values of the custom categories
		 *
		 * @param $query
		 */
		public function custom_listings_orderby( $query ) {
			if ( ! is_admin() ) {
				return;
			}

			$orderby = $query->get( 'orderby' );

			$column_categories = $this->get_column_categories();

			if ( ! empty( $column_categories ) && isset($_GET['post_type']) && $_GET['post_type'] == "listings" ) {
				foreach ( $column_categories as $column ) {
					if ( isset( $column['slug'] ) && ! empty( $column['slug'] ) ) {
						$safe = $column['slug'];

						if ( $safe == $orderby ) {
							$query->set( 'meta_key', $safe );
							$query->set( 'orderby', ( $column['compare_value'] != "=" ? 'meta_value_num' : 'meta_value' ) );
						}

						$columns[ $safe ] = $safe;
					}
				}
			}
		}

		//********************************************
		//  Filter emails with customized name & address
		//***********************************************************

		/**
		 * Change the name on outgoing emails in WordPress
		 *
		 * @param $from_name
		 *
		 * @return mixed
		 */
		public function auto_filter_email_name( $from_name ) {
			return ( isset( $this->lwp_options['default_email_name'] ) && ! empty( $this->lwp_options['default_email_name'] ) ? $this->lwp_options['default_email_name'] : $from_name );
		}

		/**
		 * Change the email on outgoing emails in WordPress
		 *
		 * @param $email
		 *
		 * @return mixed
		 */
		public function auto_filter_email_address( $email ) {
			return ( isset( $this->lwp_options['default_email_address'] ) && ! empty( $this->lwp_options['default_email_address'] ) ? $this->lwp_options['default_email_address'] : $email );
		}

		/**
		 * Generates the listing arguments
		 *
		 * @param $get_or_post
		 * @param bool|false $all
		 * @param bool|false $ajax_array
		 *
		 * @return array
		 */
		public function listing_args( $get_or_post, $ajax_array = false ) {
			if ( is_array( $ajax_array ) ) {
				$get_or_post = array_merge( $get_or_post, $ajax_array );

				foreach ( $get_or_post as $key => $value ) {
					if ( $key == "paged" ) {
						$_REQUEST['paged'] = $value;
					}
				}
			}

			//wpml language
            if($this->is_wpml_active()){
			    global $sitepress;

			    $sitepress->switch_lang($sitepress->get_current_language());
            }

			$paged                                = ( isset( $_REQUEST['paged'] ) && ! empty( $_REQUEST['paged'] ) ? $_REQUEST['paged'] : ( get_query_var( "paged" ) ? get_query_var( "paged" ) : 1 ) );
			$this->lwp_options['listings_amount'] = ( isset( $this->lwp_options['listings_amount'] ) && ! empty( $this->lwp_options['listings_amount'] ) ? $this->lwp_options['listings_amount'] : "" );

			$listing_orderby = get_auto_orderby();

			// order by
			$default_orderby = ( isset( $this->lwp_options['sortby_default'] ) && $this->lwp_options['sortby_default'] == 0 ? "DESC" : "ASC" );

			if ( isset( $get_or_post['listing_order'] ) && ! empty( $get_or_post['listing_order'] ) && isset( $get_or_post['listing_orderby'] ) && ! empty( $get_or_post['listing_orderby'] ) ) {
				$ordering[0] = $get_or_post['listing_order'];
				$ordering[1] = $get_or_post['listing_orderby'];
			} elseif ( ! empty( $listing_orderby ) ) {
				$selected = reset( $listing_orderby );
				$selected = key( $listing_orderby );

				$ordering[0] = $selected;
				$ordering[1] = $default_orderby;
			}

			$args = array(
				'post_type'        => 'listings',
				'meta_query'       => array(),
				'paged'            => ( isset( $paged ) && ! empty( $paged ) ? $paged : get_query_var( 'paged' ) ),
				'posts_per_page'   => ( $this->lwp_options['listings_amount'] ),
				'order'            => ( isset( $ordering[1] ) && ! empty( $ordering[1] ) && $ordering[1] != "undefined" ? $ordering[1] : $default_orderby ),
				'suppress_filters' => false
			);

			// sold to bottom
			$data = array(
				array(
					'key'     => 'car_sold',
					'compare' => 'EXISTS'
				)
			);

			if(isset($get_or_post['zip_code'])){
			    $args['zip_code'] = $get_or_post['zip_code'];
            }

            if(isset($get_or_post['zip_radius'])){
			    $args['radius']   = $get_or_post['zip_radius'];
            }

			if ( isset( $this->lwp_options['sortby'] ) && $this->lwp_options['sortby'] != 0 ) {
				$listing_orderby = $this->sort_by();

				if ( ! empty( $ordering[0] ) && ! empty( $ordering[1] ) ) {

					if ( $ordering[0] == "title" || $ordering[0] == "date" ) {
						$args['orderby'] = $ordering[0];
						$args['order']   = $ordering[1];
					} else {
						$args['meta_key'] = $ordering[0];

						if($ordering[0] == "title" || $ordering[0] == "date") {
							$args['orderby'] = ( isset( $ordering[0] ) && ! empty( $ordering[0] ) ? $ordering[0] : "" );
						} else {
							$args['orderby'] = ( isset( $listing_orderby[ $ordering[0] ] ) && ! empty( $listing_orderby[ $ordering[0] ] ) ? $listing_orderby[ $ordering[0] ] : "" );
						}

						if ( ! empty( $ordering[0] ) && ! empty( $listing_orderby[ $ordering[0] ] ) ) {
							$args['meta_query'][] = array(
								'key'     => $ordering[0],
								'value'   => '',
								'compare' => '!='
							);
						}
					}
				} elseif ( isset( $ordering[0] ) && $ordering[0] == "random" && empty( $ordering[1] ) ) {
					$args['orderby'] = "rand";
				} else {
					if ( ! empty( $listing_orderby ) ) {
						reset( $listing_orderby );
						$selected = key( $listing_orderby );

						if($selected == "title" || $selected == "date") {
							$args['meta_key'] = "";
							$args['orderby']  = $selected;
						} elseif($selected == "random"){
							$args['orderby'] = "rand";
							$args['order']  = $listing_orderby[ $selected ];
						} else {
							$args['meta_key'] = ($selected == "random" ? "rand" : $selected);
							$args['orderby']  = $listing_orderby[ $selected ];
						}

						$args['meta_query'][] = array(
							'key'     => $selected,
							'value'   => '',
							'compare' => '!='
						);
					}
				}
			}

			$filterable_categories = $this->get_filterable_listing_categories();

			foreach ( $filterable_categories as $filter ) {
				$get_singular = $slug = $filter['slug'];

				// year workaround, bad wordpress >:| ...
				if ( strtolower( $filter['slug'] ) == "year" && isset( $get_or_post["yr"] ) && ! empty( $get_or_post["yr"] ) ) {
					$get_singular = "yr";
				} elseif ( strtolower( $filter['slug'] ) == "year" && isset( $get_or_post["year"] ) && ! empty( $get_or_post["year"] ) ) {
					$get_singular = "year";
				}

				if ( isset( $get_or_post[ $get_singular ] ) && ! empty( $get_or_post[ $get_singular ] ) ) {
					// min max values
					if ( is_array( $get_or_post[ $get_singular ] ) && isset( $get_or_post[ $get_singular ][0] ) && ! empty( $get_or_post[ $get_singular ][0] ) && isset( $get_or_post[ $get_singular ][1] ) && ! empty( $get_or_post[ $get_singular ][1] ) ) {
						$min = $get_or_post[ $get_singular ][0];
						$max = $get_or_post[ $get_singular ][1];

						if ( is_array( $filter['terms'] ) && in_array( $get_or_post[ $get_singular ][0], $filter['terms'] ) && in_array( $get_or_post[ $get_singular ][1], $filter['terms'] ) ) {

							$data[] = array(
								'key'     => $filter['slug'],
								'value'   => array( $min, $max ),
								'type'    => 'numeric',
								'compare' => 'BETWEEN'
							);

							// also needs to exists for greater | less than
							$data[] = array(
								"key"     => $filter['slug'],
								"compare" => "NOT IN",
								"value"   => array( '', 'None', 'none' )
							);
						}
					} elseif ( is_array( $get_or_post[ $get_singular ] ) && isset( $get_or_post[ $get_singular ][0] ) && ! empty( $get_or_post[ $get_singular ][0] ) && empty( $get_or_post[ $get_singular ][1] ) ) {
						$value        = $get_or_post[ $get_singular ][0];
						$current_data = array( "key" => $filter['slug'], "value" => $value );

						if ( isset( $filter['compare_value'] ) && $filter['compare_value'] != "=" ) {
							$current_data['compare'] = html_entity_decode( $filter['compare_value'] );
							$current_data['type']    = "numeric";

							// also needs to exists for greater | less than
							$data[] = array(
								"key"     => $filter['slug'],
								"compare" => "NOT IN",
								"value"   => array( '', 'None', 'none' )
							);
						}

						$data[] = $current_data;

					} else {
						$stripped = ( $get_or_post[ $get_singular ] );

						if ( is_array( $filter['terms'] ) && is_string( $stripped ) && isset( $filter['terms'][ $stripped ] ) ) {

							$current_data = array(
								"key"   => $slug,
								"value" => stripslashes( $filter['terms'][ $stripped ] )
							);

							if ( isset( $filter['compare_value'] ) && $filter['compare_value'] != "=" ) {
								$current_data['compare'] = html_entity_decode( $filter['compare_value'] );
								$current_data['type']    = "numeric";

								// strip all other characters to sort by numbers
                                // $current_data['value']   = preg_replace("/[^0-9]/", "", $current_data['value']);

								// also needs to exists for greater | less than
								$data[] = array(
									"key"     => $slug,
									"compare" => "NOT IN",
									"value"   => array( '', 'None', 'none' )
								);
							}

							$data[] = $current_data;
						}
					}
				}
			}

			// additional categories
			$additional_categories = $this->get_additional_categories();

			if ( ! empty( $additional_categories ) ) {
				foreach ( $additional_categories as $additional_category ) {
					$check_handle = str_replace( " ", "_", mb_strtolower( $additional_category ) );

					// in url
					if ( isset( $get_or_post[ $check_handle ] ) && ! empty( $get_or_post[ $check_handle ] ) ) {
						$data[] = array( "key" => $check_handle, "value" => 1 );
					}
				}
			}

			// hide sold vehicles
			if ( isset( $_REQUEST['show_only_sold'] ) || ( isset( $get_or_post['sold_only'] ) && $get_or_post['sold_only'] == "true" ) ) {
				$data[] = array(
					"key"   => "car_sold",
					"value" => "1"
				);
			} elseif ( empty( $this->lwp_options['inventory_no_sold'] ) && ! isset( $_GET['show_sold'] ) ) {
				$data[] = array(
					"key"   => "car_sold",
					"value" => "2"
				);
			}

			// newest arrivals
			if ( isset( $get_or_post['arrivals'] ) && ! empty( $get_or_post['arrivals'] ) ) {
				$amount_days = (int) $get_or_post['arrivals'];
				$after       = date( "F d, Y", strtotime( '-' . $amount_days . ' day', time() ) );

				$args['date_query'] = array(
					array(
						"after" => $after
					)
				);
			}

			// order by
			if ( isset( $get_or_post['order_by'] ) && isset( $get_or_post['order'] ) ) {
				$args['orderby'] = $get_or_post['order_by'];
				$args['order']   = $get_or_post['order'];
			}

			if ( ! empty( $data ) ) {
				$args['meta_query'] = $data;
			}

			// keywords
			if ( isset( $_REQUEST['keywords'] ) && ! empty( $_REQUEST['keywords'] ) ) {

				if(!isset($this->lwp_options['keyword_search']) || !$this->lwp_options['keyword_search']) {
					$args['s'] = sanitize_text_field( $_REQUEST['keywords'] );

				} else {
					$keywords_args = array(
						'relation' => 'OR'
					);

					$filterable_categories = $this->get_listing_categories();

					if ( ! empty( $filterable_categories ) ) {
						foreach ( $filterable_categories as $category ) {
							$keywords_args[] = array(
								"key"     => $category['slug'],
								"value"   => sanitize_text_field( $_REQUEST['keywords'] ),
								"compare" => "LIKE"
							);
						}
					}

					$args['meta_query'][] = $keywords_args;
				}
			}

			$args = apply_filters( "listing_args", $args );

			return array( $args );
		}

		/**
		 * Generates the options for the search box shortcode when user chooses an option
		 */
		public function search_box_shortcode_update_options() {
			$current_options = ( isset( $_POST['current'] ) ? $_POST['current'] : array() );
			$min_max_values  = array();

			if ( ! empty( $current_options ) ) {
				foreach ( $current_options as $slug => $value ) {
				    if(is_array($value) && !isset($min_max_values[$slug])){
				        $min_max_values[$slug] = true;
                    }

					if ( empty( $value ) ) {
						unset( $current_options[ $slug ] );
					}
				}
			}

			echo wp_json_encode( $this->process_dependancies( $current_options, false, $min_max_values ) );

			die;
		}

		/**
		 * Determines if the page is a newly created page or editing a previous one
		 *
		 * @param null $new_edit
		 *
		 * @return bool
		 */
		public function is_edit_page( $new_edit = null ) {
			global $pagenow;

			if ( ! is_admin() ) {
				return false;
			}

			if ( $new_edit == "edit" ) {
				return in_array( $pagenow, array( 'post.php', ) );
			} elseif ( $new_edit == "new" ) //check for new post page
			{
				return in_array( $pagenow, array( 'post-new.php' ) );
			} else {
				return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
			}
		}

		/**
		 * Used to load the options from the old sort to the new way
		 *
		 * @return array
		 */
		public function sort_by_options( $lang_code = false ) {
			$enabled         = array();
			$disabled        = array();
			$listing_orderby = get_auto_orderby();
			$all_categories  = $this->get_listing_categories( false, $lang_code );

			if ( ! empty( $listing_orderby ) ) {
				foreach ( $listing_orderby as $key => $value ) {
					$single = $this->get_single_listing_category( $key );

					if ( isset( $single ) && is_array($single) && ! empty( $single['slug'] ) ) {
						$enabled[ $key ] = $single['singular'];

						// remove it from all categories so no duplicates
						if ( isset( $all_categories[ $key ] ) ) {
							unset( $all_categories[ $key ] );
						}
					}
				}
			}

			if ( ! empty( $all_categories ) && is_array( $all_categories ) ) {
				foreach ( $all_categories as $key => $category ) {
					if(is_array($category)) {
						$disabled[ $key ] = $category['singular'];
					}
				}
			}

			// other non-category ways of sorting
			$disabled['random'] = __( "Random", "listings" );
			$disabled['date']   = __( "Date Added", "listings" );
			$disabled['title']  = __( "Title", "listings" );

			return array( 'enabled' => $enabled, 'disabled' => $disabled );
		}

		/**
		 * Generate an array of options to use for sorting the inventory by
		 *
		 * @return array
		 */
		public function sort_by() {
			$option = 'sortby_categories';

			if ( $this->is_wpml_active() ) {
				$option .= '_' . ICL_LANGUAGE_CODE;
			}

			$sortby = ( isset( $this->lwp_options[ $option ] ) && ! empty( $this->lwp_options[ $option ] ) ? $this->lwp_options[ $option ] : "" );
			$return = array();

			$special_sort = array( "title", "date" );

			if ( ! empty( $sortby ) ) {
				unset( $sortby['enabled']['placebo'] );

				foreach ( $sortby['enabled'] as $key => $label ) {
					if ( $key != "random" ) {
						if ( in_array( $key, $special_sort ) ) {
							$category['compare_value'] = "=";
						} else {
							$category = $this->get_single_listing_category( $key );
						}

						$return[ $key ] = ( isset( $category['compare_value'] ) && $category['compare_value'] != "=" ? "meta_value_num" : "meta_value" );
					} else {
						$return[ $key ] = "random";
					}
				}
			}

			return $return;
		}

		/**
		 * Display a message to the user
		 *
		 * @param $title
		 * @param $message
		 * @param $type
		 * @param $id
		 */
		public function automotive_message( $title, $message, $type, $id ) {
			$hidden_notices = get_option( "automotive_hide_messages" );
			$hidden_notices = ( empty( $hidden_notices ) ? array() : $hidden_notices );

			if ( ! in_array( $id, $hidden_notices ) ) { ?>
				<div class="<?php echo $type; ?> auto_message" data-id="<?php echo $id; ?>">
					<span class="hide_message"><i class="fa fa-close"></i></span>

					<h3><?php echo $title; ?></h3>

					<p><?php echo $message; ?></p>
				</div>
				<?php
			}
		}

		public function automotive_admin_help_message($message){
			return (isset($this->lwp_options['hide_hints']) && $this->lwp_options['hide_hints'] == 1 ? '' : '<div class="help_area">
					<div class="message_area">
						' . $message . '
					</div>

					<div class="triangle"><i class="fa fa-info-circle"></i></div>
				</div>');
		}

		/**
		 * Lets the user hide the message forever!
		 */
		public function hide_automotive_message() {
			$hidden_notices = get_option( "automotive_hide_messages" );
			$hidden_notices = ( empty( $hidden_notices ) ? array() : $hidden_notices );

			$hidden_notices[] = sanitize_text_field( $_POST['id'] );

			update_option( "automotive_hide_messages", $hidden_notices );
		}

		/**
		 * Edits a listing category value
		 */
		public function edit_listing_category_value() {
			$current_value = sanitize_text_field( $_POST['current_value'] );
			$new_value     = sanitize_text_field( $_POST['new_value'] );
			$category      = sanitize_text_field( $_POST['category'] );

			$current_category = $this->get_single_listing_category( $category );

			$current_terms = $current_category['terms'];
			$return        = array(
				"message" => __( "Successfully changed the value", "listings" ),
				"status"  => "true"
			);

			if ( ! empty( $current_category ) ) {

				if ( is_array( $current_terms ) && in_array( $current_value, $current_terms ) ) {
					$term_key = array_search( $current_value, $current_terms );
					$new_slug = $this->slugify( $new_value );

					// remove old value
					unset( $current_category['terms'][ $term_key ] );

					// add new value
					$current_category['terms'][ $new_slug ] = $new_value;

					// update category
					if ( $category == "options" ) {
						$this->set_single_listing_category( $current_category, true );
					} else {
						$this->set_single_listing_category( $current_category, false );
					}
					// update value in existing listings
					$existing_listing_ids = new WP_Query( array(
						"post_type"      => "listings",
						"posts_per_page" => - 1,
						"fields"         => "ids"
					) );

					if ( $existing_listing_ids->have_posts() ) {
						global $wpdb;

						foreach ( $existing_listing_ids->posts as $id ) {
							// different method for updating options (since its serialized)
							if ( $category == "options" ) {
								$multi_options = get_post_meta( $id, "multi_options", true );

								if ( ! empty( $multi_options ) && is_array( $multi_options ) && in_array( $current_value, $multi_options ) ) {
									$option_key = array_search( $current_value, $multi_options );
									unset( $multi_options[ $option_key ] );

									$multi_options[] = $new_value;

									update_post_meta( $id, "multi_options", $multi_options );
								}
							} else {
								$wpdb->update( $wpdb->prefix . "postmeta", array( "meta_value" => $new_value ), array(
									"post_id"    => $id,
									"meta_key"   => $category,
									"meta_value" => $current_value
								) );
							}
						}
					}

					// update dependancies
					$this->generate_dependancy_option( true );

					$return['slug'] = $new_slug;

				} else {
					$return = array( "message" => __( "Term not found", "listings" ), "status" => "false" );
				}
			} else {
				$return = array( "message" => __( "Category not found", "listings" ), "status" => "false" );
			}

			echo wp_json_encode( $return );

			die;
		}

		/**
		 * Generates the WooCommerce Integration Shortcode
		 *
		 * @param $woocommerce_product_id
		 */
		public function woocommerce_integration( $woocommerce_product_id ) {
			$style = "border: ";

			$border_width = ( isset( $this->lwp_options['woocommerce_integration_border']['border-top'] ) && ! empty( $this->lwp_options['woocommerce_integration_border']['border-top'] ) ? $this->lwp_options['woocommerce_integration_border']['border-top'] : "" );
			$border_style = ( isset( $this->lwp_options['woocommerce_integration_border']['border-style'] ) && ! empty( $this->lwp_options['woocommerce_integration_border']['border-style'] ) ? $this->lwp_options['woocommerce_integration_border']['border-style'] : "" );
			$border_color = ( isset( $this->lwp_options['woocommerce_integration_border']['border-color'] ) && ! empty( $this->lwp_options['woocommerce_integration_border']['border-color'] ) ? $this->lwp_options['woocommerce_integration_border']['border-color'] : "" );

			$padding_top    = ( isset( $this->lwp_options['woocommerce_integration_padding']['padding-top'] ) && ! empty( $this->lwp_options['woocommerce_integration_padding']['padding-top'] ) ? $this->lwp_options['woocommerce_integration_padding']['padding-top'] : "" );
			$padding_right  = ( isset( $this->lwp_options['woocommerce_integration_padding']['padding-left'] ) && ! empty( $this->lwp_options['woocommerce_integration_padding']['padding-left'] ) ? $this->lwp_options['woocommerce_integration_padding']['padding-left'] : "" );
			$padding_bottom = ( isset( $this->lwp_options['woocommerce_integration_padding']['padding-bottom'] ) && ! empty( $this->lwp_options['woocommerce_integration_padding']['padding-bottom'] ) ? $this->lwp_options['woocommerce_integration_padding']['padding-bottom'] : "" );
			$padding_left   = ( isset( $this->lwp_options['woocommerce_integration_padding']['padding-left'] ) && ! empty( $this->lwp_options['woocommerce_integration_padding']['padding-left'] ) ? $this->lwp_options['woocommerce_integration_padding']['padding-left'] : "" );

			// generate border
			$style .= ( ! empty( $border_width ) ? $border_width : "0px" ) . " ";
			$style .= ( ! empty( $border_style ) ? $border_style : "none" ) . " ";
			$style .= ( ! empty( $border_color ) ? $border_color : "transparent" ) . "; ";

			// generate padding
			$style .= "padding: ";

			$style .= ( ! empty( $padding_top ) ? $padding_top : "0px" ) . " ";
			$style .= ( ! empty( $padding_right ) ? $padding_right : "0px" ) . " ";
			$style .= ( ! empty( $padding_bottom ) ? $padding_bottom : "0px" ) . " ";
			$style .= ( ! empty( $padding_left ) ? $padding_left : "0px" ) . ";";


			echo do_shortcode( '[add_to_cart id="' . $woocommerce_product_id . '" style="' . $style . '"]' );
		}

		/**
		 * Toggles Listing Features
		 */
		public function toggle_listing_features() {
			$listing_features_current = get_option( "listing_features" );

			if ( empty( $listing_features_current ) ) {
				$new_value = "disabled";
			} elseif ( $listing_features_current == "disabled" ) {
				$new_value = "enabled";
			} else {
				$new_value = "disabled";
			}

			update_option( "listing_features", $new_value );

			echo $new_value;

			die;
		}

		/**
		 * Updates the listing categories that are used when a user sends a listing to the trash
		 *
		 * @param $post_id
		 */
		public function listing_sent_trash( $post_id ) {
			$post_type = get_post_type( $post_id );

			if ( $post_type == "listings" ) {
				$this->update_dependancy_option( $post_id, "delete" );
			}
		}

		/**
		 * Updates the listing categories that are used when a user restores a listing from the trash
		 *
		 * @param $post_id
		 */
		public function listing_restore_trash( $post_id ) {
			$post_type = get_post_type( $post_id );

			if ( $post_type == "listings" ) {
				$new_listing_categories_values = array();
				$listing_categories            = $this->get_listing_categories();

				if ( ! empty( $listing_categories ) ) {
					foreach ( $listing_categories as $key => $category ) {
						$value = get_post_meta( $post_id, $category['slug'], true );

						$new_listing_categories_values[ $category['slug'] ] = array( $this->slugify( $value ) => $value );
					}
				}

				$this->update_dependancy_option( $post_id, $new_listing_categories_values );
			}
		}

		public function regenerate_listing_category_terms() {
			$this->generate_dependancy_option( true );

			echo wp_json_encode( array( "success" ) );

			die;
		}

		public function auto_image( $id, $size, $url = false ) {
			if($this->is_hotlink()){
				global $slider_thumbnails;

				// determine size
				if($size == "related_portfolio"){
					$width  = 270;
					$height = 140;
				} elseif($size == "auto_thumb"){
					$width  = $slider_thumbnails['width'];
					$height = $slider_thumbnails['height'];
				} elseif($size == "auto_slider"){
					$width  = $slider_thumbnails['slider']['width'];
					$height = $slider_thumbnails['slider']['height'];
				} elseif($size == "auto_listing"){
					$width  = $slider_thumbnails['listing']['width'];
					$height = $slider_thumbnails['listing']['height'];
				} elseif($size == "auto_portfolio"){
					$width  = 770;
					$height = 450;
				}

				// determine image
				if(!filter_var($id, FILTER_VALIDATE_URL) && isset($this->lwp_options['not_found_image']['url']) && !empty($this->lwp_options['not_found_image']['url'])){
					$id 		 = $this->lwp_options['not_found_image']['url'];
				} elseif(!filter_var($id, FILTER_VALIDATE_URL) && empty($this->lwp_options['not_found_image']['url'])) {
					$id 		 = LISTING_DIR . "images/pixel.gif";
				}

				$return = ($url == true ? $id : "<img src='" . $id . "'" . (isset($height) && isset($width) ? "style='height: " . $height . "px; width: " . $width . "px'" : "") . ">");

				return $return;
			} else {
				$return = ( $url == true ? wp_get_attachment_image_src( $id, $size ) : wp_get_attachment_image( $id, $size ) );

				return ( $url == true ? $return[0] : $return );
			}
		}

		public function is_hotlink(){
			return ((isset($this->lwp_options['hotlink']) && $this->lwp_options['hotlink'] == 0) || !isset($this->lwp_options['hotlink']) ? false : true);
		}

		public function get_listing_badge($badge, $is_sold = false){
			$badge       = ($is_sold == true ? $this->lwp_options['custom_badges']['name']['sold'] : $badge);
			$current_key = array_search($badge, $this->lwp_options['custom_badges']['name']);

			return array(
				"name" => $badge,
				"css"  => $this->numbers_to_text($this->slugify($badge)),
				"color" => $this->lwp_options['custom_badges']['color'][$current_key]
			);
		}

		public function get_listing_badge_html($listing_options, $post_meta){
			// if sold auto add badge
			$auto_sold_badge = (isset($this->lwp_options['sold_attach_badge']) && $this->lwp_options['sold_attach_badge'] == 1 && isset( $post_meta['car_sold'] ) && ! empty( $post_meta['car_sold'] ) && $post_meta['car_sold'] == 1 ? true : false);

			if($auto_sold_badge){
				$listing_options['custom_badge'] = "sold";
			}

			if ( isset( $listing_options['custom_badge'] ) && ! empty( $listing_options['custom_badge'] ) ) {
				$listing_badge = $this->get_listing_badge($listing_options['custom_badge'], $auto_sold_badge); ?>
				<div class="angled_badge <?php echo $listing_badge['css']; ?>">
					<span<?php echo( strlen( $listing_badge['name'] ) >= 7 ? " class='smaller'" : "" ); ?>><?php echo $listing_badge['name']; ?></span>
				</div>
			<?php }
		}

		public function add_new_listing_badge(){
			$name  = sanitize_text_field($_POST['name']);
			$color = sanitize_text_field($_POST['color']);
			$font  = sanitize_text_field($_POST['font']);

			$listing_options = get_option("listing_wp");
			$current_badges  = (isset($listing_options['custom_badges']) && !empty($listing_options['custom_badges']) ? $listing_options['custom_badges'] : "");

			$current_badges['name'][]  = $name;
			$current_badges['color'][] = $color;
			$current_badges['font'][]  = $font;

			$listing_options['custom_badges'] = $current_badges;

			update_option("listing_wp", $listing_options);

			die;
		}

		public function numbers_to_text($value){
			$replace = array(
				0 => "zero",
				1 => "one",
				2 => "two",
				3 => "three",
				4 => "four",
				5 => "five",
				6 => "six",
				7 => "seven",
				8 => "eight",
				9 => "nine"
			);

			return str_replace(array_keys($replace), array_values($replace), $value);
		}

		public function get_wp_roles(){
			// for now return static roles, get_editable_roles() seems to be derping
			return array(
				"read"                  => __("Subscriber", "listings"),
				"edit_posts"            => __("Contributor", "listings"),
				"edit_published_posts"  => __("Author", "listings"),
				"edit_pages"            => __("Editor", "listings"),
				"install_plugins"       => __("Administrator", "listings")
			);
		}

		public function add_sold_view( $views ) {
			global $wpdb;

			$query = "SELECT * FROM " . $wpdb->prefix . "posts as p, " . $wpdb->prefix . "postmeta as m WHERE p.ID = m.post_id AND p.post_status = 'publish' AND p.post_type = 'listings' AND m.meta_key = 'car_sold' AND m.meta_value = '1'";

			$total = count($wpdb->get_results($query));

			$views  = array_slice($views, 0, 1, true) +
			          array('sold'     => "<a href='" . add_query_arg(array("post_type" => "listings", "sold_listings" => 1), admin_url('edit.php')) . "'" . (isset($_GET['sold_listings']) && $_GET['sold_listings'] == 1 ? " class='current'" : "") . ">" . __("Sold", "listings") . " <span class=\"count\">(" . $total . ")</span></a>") +
			          array_slice($views, 1, NULL, true);

			return $views;
		}

		public function add_for_sale_view( $views ) {
			global $wpdb;

			$query = "SELECT * FROM " . $wpdb->prefix . "posts as p, " . $wpdb->prefix . "postmeta as m WHERE p.ID = m.post_id AND p.post_status = 'publish' AND p.post_type = 'listings' AND m.meta_key = 'car_sold' AND m.meta_value = '2'";

			$total = count($wpdb->get_results($query));

			$views  = array_slice($views, 0, 1, true) +
			          array('for_sale' => "<a href='" . add_query_arg(array("post_type" => "listings", "sold_listings" => 2), admin_url('edit.php')) . "'" . (isset($_GET['sold_listings']) && $_GET['sold_listings'] == 2 ? " class='current'" : "") . ">" . __("For Sale", "listings") . " <span class=\"count\">(" . $total . ")</span></a>") +
			          array_slice($views, 1, NULL, true);

			return $views;
		}


		public function add_sold_view_query( $query ){
			if(isset($_GET['sold_listings']) && $_GET['sold_listings'] == 1){
				$query->set('meta_key', 'car_sold');
				$query->set('meta_value', '1');
			} elseif(isset($_GET['sold_listings']) && $_GET['sold_listings'] == 2){
				$query->set('meta_key', 'car_sold');
				$query->set('meta_value', '2');
			}

			return $query;
		}

		public function compare_no_cache(){
			global $post;

			if( (isset($this->lwp_options['comparison_page']) || isset($this->lwp_options['inventory_page'])) && isset($post) && in_array($post->ID, array((int)$this->lwp_options['comparison_page'], (int)$this->lwp_options['inventory_page']))){
				nocache_headers();
			}
		}

		public function get_public_query_vars(){
			return array(
				'm',
				'p',
				'posts',
				'w',
				'cat',
				'withcomments',
				'withoutcomments',
				's',
				'search',
				'exact',
				'sentence',
				'debug',
				'calendar',
				'page',
				'paged',
				'more',
				'tb',
				'pb',
				'author',
				'order',
				'orderby',
				'monthnum',
				'day',
				'hour',
				'minute',
				'second',
				'name',
				'category_name',
				'tag',
				'feed',
				'author_name',
				'static',
				'pagename',
				'page_id',
				'error',
				'comments_popup',
				'attachment',
				'attachment_id',
				'subpost',
				'subpost_id',
				'preview',
				'robots',
				'taxonomy',
				'term',
				'cpage',
				'post_type',
				'order',
				'action',
				'layout',
				'paged',
				'options',
				'rand',
				'random',
				'date',
				'title'
			);
		}

		public function update_listing_categories($listing_categories){
			if($this->is_wpml_active()){
				update_option( $this->get_listing_categories_option_name(ICL_LANGUAGE_CODE), $listing_categories );
			} else {
				update_option( $this->get_listing_categories_option_name(), $listing_categories );
			}
        }

		public function add_listing_category_term($category_slug, $term){
		    $return = false;

		    // check and make sure category exists, it always should but stranger things have happened
            if(isset($this->listing_categories[$category_slug]) && !empty($this->listing_categories[$category_slug])){
	            // sanitize
	            $term               = sanitize_text_field($term);
	            $slug               = $this->slugify($term);
	            $current_category   = &$this->listing_categories[$category_slug]; // & it's modifiable

	            // if terms don't exist, make em
                if(empty($current_category['terms']) || !isset($current_category['terms'])){
                    $current_category['terms'] = array();
                }

                // add the term if it doesn't exist
                if(!isset($current_category['terms'][$slug])){
                    $current_category['terms'][$slug] = $term;

                    // only update since a new option has been added
                    $this->update_listing_categories($this->listing_categories);

                    // return the terms
                    $return = array(
                        "name"  => $term,
                        "slug"  => $slug
                    );
                }
            }

            return $return;
        }


		public function ajax_add_listing_category_term() {
			$nonce = $_REQUEST['nonce'];
			$name  = sanitize_text_field($_POST['value']);
			$exact = sanitize_text_field($_POST['exact']);

			// add the term
			if ( wp_verify_nonce( $nonce, 'add_listing_value_' . $exact ) ) {
				$add = $this->add_listing_category_term( $exact, $name );

				if($add){
				    echo wp_json_encode($add);
                } else {
				    echo wp_json_encode(
                        array(
                            "error" => __("Couldn't add the term", "listings")
                        )
                    );
                }
			}

			die;
		}

		/**
		 * Delete a listing category term
		 */
		public function ajax_delete_listing_category_term() {
            $id   = $_POST['id'];
            $type = $_POST['type'];

            $current_category = &$this->listing_categories[ $type ];

            unset( $current_category['terms'][ $id ] );

			$this->update_listing_categories($this->listing_categories);

            die;
        }

		/**
         * Sets the current query variable
         *
		 * @param $args
		 * @param bool $ajax_array
		 */
		public function set_current_query_info($args, $ajax_array = false){
		    $listing_args                               = $this->listing_args( $args, (isset($ajax_array) && !empty($ajax_array) ? $ajax_array : false) );
		    $this->current_query_info['listing_args']   = $listing_args[0];

		    if(defined("AUTO_ZIP_HOME") && class_exists("WP_GeoQuery")) {
			    $_wp_query_listings = new WP_GeoQuery( $listing_args[0] );
		    } else {
			    $_wp_query_listings = new WP_Query( $listing_args[0] );
            }

		    $this->current_query_info['listings']       = $_wp_query_listings->posts;

		    // now get total number for this set of filters (need to find a more efficient way, caching maybe?)
            $listing_args[0]['posts_per_page']          = -1;

            if(defined("AUTO_ZIP_HOME") && class_exists("WP_GeoQuery")){
	            $_wp_query_listings = new WP_GeoQuery( $listing_args[0] );
            } else {
	            $_wp_query_listings = new WP_Query( $listing_args[0] );
            }

            $this->current_query_info['total']          = count($_wp_query_listings->posts);
        }

		/**
         * Gets the additional categories on the site
         *
		 * @return array
		 */
		public function get_additional_categories(){
			$return                 = array();
			$additional_categories  = "additional_categories";

			if ( $this->is_wpml_active() ) {
				$additional_categories .= '_' . ICL_LANGUAGE_CODE;
			}

			if ( isset( $this->lwp_options[ $additional_categories ]['value'] ) && ! empty( $this->lwp_options[ $additional_categories ]['value'] ) ) {
				$return = $this->lwp_options[ $additional_categories ]['value'];
			}

			return $return;
		}

		/**
		 * Grab the first image for the listing
		 *
		 * @param $listing_id
		 * @param string $size
		 *
		 * @return array|false|string
		 */
		public function get_single_listing_image( $listing_id, $size = "auto_listing" ){
			$gallery = get_post_meta($listing_id, "gallery_images", true);

			if(isset($gallery) && !empty($gallery) && isset($gallery[0]) && !empty($gallery[0])){
				$return = $this->auto_image($gallery[0], $size, true);
			} elseif(empty($gallery[0]) && isset($this->lwp_options['not_found_image']['url']) && !empty($this->lwp_options['not_found_image']['url'])){
				$return = $this->lwp_options['not_found_image']['url'];
			} else {
				$return = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7";
			}

			return $return;
        }

		public function convert_old_data(){
			// is number upgrade convert
			$is_number_convert = get_option("is_number_convert");

			if(!$is_number_convert){
				$listing_categories = $this->get_listing_categories();

				if(!empty($listing_categories)){
					foreach($listing_categories as $category_key => $category){
						if($category_key != "options" && (isset($category['compare_value']) && $category['compare_value'] != "=")){
							$listing_categories[$category_key]['is_number'] = 1;
						}
					}
				}

				update_option('listing_categories', $listing_categories);
				update_option('is_number_convert', true);
			} else {
				$listing_categories = $this->get_listing_categories();
				$options_return     = get_option( "is_options_returned" );

				if ( ( ( ! isset( $listing_categories['options'] ) || empty( $listing_categories['options'] ) ) && ! $options_return) || (isset($_GET['force_terms_refresh']) && is_admin()) ) {
					$new_options = array();

					$all_listings = get_posts(
						array(
							"post_type"      => "listings",
							"posts_per_page" => - 1
						)
					);

					if ( ! empty( $all_listings ) ) {
						foreach ( $all_listings as $listing ) {
							$multi_options = get_post_meta( $listing->ID, "multi_options", true );

							if ( ! empty( $multi_options ) ) {
								foreach ( $multi_options as $option ) {
									$new_options[ $this->slugify( $option ) ] = $option;
								}
							}
						}
					}

					$listing_categories['options']['terms'] = $new_options;

					update_option('listing_categories', $listing_categories);
					update_option('is_options_returned', true);
				}
			}

			/* Yoast SEO _thumbnail_id convert */
			$yoast_thumbnail_id_convert = get_option("listings_yoast_thumbnail_convert");

			if(!$yoast_thumbnail_id_convert){
				$all_listings = get_posts(
					array(
						"post_type"      => "listings",
						"posts_per_page" => - 1
					)
				);

				if ( ! empty( $all_listings ) ) {
					foreach ( $all_listings as $listing ) {
						$gallery_images = get_post_meta( $listing->ID, "gallery_images", true );

						if ( ! empty( $gallery_images ) ) {
							update_post_meta($listing->ID, "_thumbnail_id", $gallery_images[0]);
						}
					}
				}

				update_option("listings_yoast_thumbnail_convert", true);
			}
        }
	}
}