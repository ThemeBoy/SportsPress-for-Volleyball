<?php
/*
 * Plugin Name: SportsPress for Volleyball
 * Plugin URI: https://www.themeboy.com/sportspress-pro/
 * Description: A suite of volleyball features for SportsPress.
 * Author: ThemeBoy
 * Author URI: http://themeboy.com/
 * Version: 0.9
 *
 * Text Domain: sportspress-for-volleyball
 * Domain Path: /languages/
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Volleyball' ) ) :

/**
 * Main SportsPress Volleyball Class
 *
 * @class SportsPress_Volleyball
 * @version	0.9
 */
class SportsPress_Volleyball {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 30 );

		// Require core
		add_action( 'tgmpa_register', array( $this, 'require_core' ) );

		// Modify text options
		add_filter( 'gettext', array( $this, 'gettext' ), 20, 3 );
		add_filter( 'sportspress_text', array( $this, 'add_text_options' ) );

		// Hide status selector
		add_filter( 'sportspress_event_performance_show_status', '__return_false' );

		// Define default sport
		add_filter( 'sportspress_default_sport', array( $this, 'default_sport' ) );

		// Include required files
		$this->includes();
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_VOLLEYBALL_VERSION' ) )
			define( 'SP_VOLLEYBALL_VERSION', '0.9' );

		if ( !defined( 'SP_VOLLEYBALL_URL' ) )
			define( 'SP_VOLLEYBALL_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_VOLLEYBALL_DIR' ) )
			define( 'SP_VOLLEYBALL_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Enqueue styles.
	 */
	public static function admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( in_array( $screen->id, array( 'sp_event', 'edit-sp_event' ) ) ) {
			wp_enqueue_script( 'sportspress-volleyball-admin', SP_VOLLEYBALL_URL . 'js/admin.js', array( 'jquery' ), SP_VOLLEYBALL_VERSION, true );
		}

		wp_enqueue_style( 'sportspress-volleyball-admin', SP_VOLLEYBALL_URL . 'css/admin.css', array( 'sportspress-admin-menu-styles' ), '0.9' );
	}

	/**
	 * Include required files.
	*/
	private function includes() {
		require_once dirname( __FILE__ ) . '/includes/class-tgm-plugin-activation.php';
	}

	/**
	 * Require SportsPress core.
	*/
	public static function require_core() {
		$plugins = array(
			array(
				'name'        => 'SportsPress',
				'slug'        => 'sportspress',
				'required'    => true,
				'version'     => '2.6.19',
				'is_callable' => array( 'SportsPress', 'instance' ),
			),
		);

		$config = array(
			'default_path' => '',
			'menu'         => 'tgmpa-install-plugins',
			'has_notices'  => true,
			'dismissable'  => true,
			'is_automatic' => true,
			'message'      => '',
			'strings'      => array(
				'nag_type' => 'updated'
			)
		);

		tgmpa( $plugins, $config );
	}

	/** 
	 * Text filter.
	 */
	public function gettext( $translated_text, $untranslated_text, $domain ) {
		if ( $domain == 'sportspress' ) {
			switch ( $untranslated_text ) {
				case 'Events':
					$translated_text = __( 'Matches', 'sportspress-for-volleyball' );
					break;
				case 'Event':
					$translated_text = __( 'Match', 'sportspress-for-volleyball' );
					break;
				case 'Add New Event':
					$translated_text = __( 'Add New Match', 'sportspress-for-volleyball' );
					break;
				case 'Edit Event':
					$translated_text = __( 'Edit Match', 'sportspress-for-volleyball' );
					break;
				case 'View Event':
					$translated_text = __( 'View Match', 'sportspress-for-volleyball' );
					break;
				case 'View all events':
					$translated_text = __( 'View all matches', 'sportspress-for-volleyball' );
					break;
			}
		}
		
		return $translated_text;
	}

	/**
	 * Add text options 
	 */
	public function add_text_options( $options = array() ) {
		return array_merge( $options, array(
			__( 'Libero', 'sportspress' ),
		) );
	}

	/**
	 * Append own goals to box score.
	*/
	public function players( $data = array(), $lineups = array(), $subs = array(), $mode = 'values' ) {
		if ( 'icons' == $mode ) return $data;

		foreach ( $data as $id => $performance ) {
			$owngoals = sp_array_value( $performance, 'owngoals', 0 );
			if ( $owngoals ) {
				$option = sp_get_main_performance_option();
				$goals = sp_array_value( $performance, $option, 0 );
				if ( $goals ) {
					$data[ $id ][ $option ] = $goals . ', ' . $owngoals . ' ' . __( 'OG', 'sportspress' );
				} else {
					$data[ $id ][ $option ] = $owngoals . ' ' . __( 'OG', 'sportspress' );
				}
			}
		}

		return $data;
	}

	/**
	 * Define default sport.
	*/
	public function default_sport() {
		return 'volleyball';
	}
}

endif;

new SportsPress_Volleyball();
