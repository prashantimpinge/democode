<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://localhost.shekhar.com
 * @since      1.0.0
 *
 * @package    Customer_Feedback_Form
 * @subpackage Customer_Feedback_Form/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Customer_Feedback_Form
 * @subpackage Customer_Feedback_Form/admin
 * @author     Shekhar Verma <shekhar.impinge@gmail.com>
 */
class Customer_Feedback_Form_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'init', array($this,'register_api_settings') );
		add_action( 'init', array( $this, 'register_post_types_lead_review' ), 0 );
		add_action( 'admin_menu',  array($this,'api_settings_admin_menu'), 99 );

	}

	public function register_post_types_lead_review() {
		// Add new Lead category
		$admin_capability = 'manage_lead_review';
		

		// create post type for Lead
		$Lead_singular = __( 'Lead Review', 'wp-lead-review' );
		$Lead_plural   = __( 'Leads Reviews', 'wp-lead-review' );

		$labels = array(
						'name'                  => $Lead_plural, 
						'singular_name'         => $Lead_singular,
						'menu_name'             => __( 'Lead Review Manager', 'wp-lead-review' ),
						'all_items'             => sprintf( __( 'All %s', 'wp-lead-review' ), $Lead_plural ),
						'add_new'               => __( 'Add New', 'wp-lead-review' ),
						'add_new_item'          => sprintf( __( 'Add %s', 'wp-lead-review' ), $Lead_singular ),
						'edit'                  => __( 'Edit', 'wp-lead-review' ),
						'edit_item'             => sprintf( __( 'Edit %s', 'wp-lead-review' ), $Lead_singular ),
						'new_item'              => sprintf( __( 'New %s', 'wp-lead-review' ), $Lead_singular ),
						'view'                  => sprintf( __( 'View %s', 'wp-lead-review' ), $Lead_singular ),
						'view_item'             => sprintf( __( 'View %s', 'wp-lead-review' ), $Lead_singular ),
						'search_items'          => sprintf( __( 'Search %s', 'wp-lead-review' ), $Lead_plural ),
						'not_found'             => sprintf( __( 'No %s found', 'wp-lead-review' ), $Lead_plural ),
						'not_found_in_trash'    => sprintf( __( 'No %s found in trash', 'wp-lead-review' ), $Lead_plural ),
						'parent'                => sprintf( __( 'Parent %s', 'wp-lead-review' ), $Lead_singular )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => sprintf(__( 'This is where you can create and manage %s.','wp-lead-review'),$Lead_plural),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'capability_type'     => 'post',
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'hierarchical'        => false,
			'rewrite'             => array( 'slug' => 'Lead_review_manager' ),
			'query_var'           => true,
			'menu_position'       => null,
			'supports'            => array( 'title', 'editor','custom-fields' ),
			'has_archive'         => true,
			'delete_with_user'    => true,
		);


		register_post_type('lead_review',$args);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Customer_Feedback_Form_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Customer_Feedback_Form_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/customer-feedback-form-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Customer_Feedback_Form_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Customer_Feedback_Form_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/customer-feedback-form-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function api_settings_admin_menu(){
		add_submenu_page( 'options-general.php', 'API Settings page', 'Sugar API Settings', 'administrator', 'api_settings_keys', array($this, 'api_settings' ) );
	}

	public function api_settings(){ ?>
			<div class="api-content">
			<form method="post" action="options.php" class="form-horizontal">
					<?php settings_fields( 'api_settings_group' ); 
					$api_settings_keys=get_option('api_settings_keys');
					?>
				<div class="api-heading">
					<h1>API Settings</h1>
				</div>
				<div class="url-section">
					<h4 class="custom-heading">API URL</h4>
					<div class="form-group">
						<div class="input-group">
						<input type="text" class="form-control" name="api_settings_keys[api_url]" id="url" value="<?php echo isset($api_settings_keys['api_url'])?$api_settings_keys['api_url']:''; ?>" >
						<div class="input-group-addon"><i class="fas fa-link"></i></div>
						</div>
					</div><br>
					<h4 class="custom-heading">Email (This email address used for email notification when form data updated on Sugar CRM)</h4>
					<div class="form-group">
						<div class="input-group">
								<input type="text" class="form-control each_item" name="api_settings_keys[email]" value="<?php echo isset($api_settings_keys['email'])?$api_settings_keys['email']:''; ?>">
							</div>
						</div>

				</div>
				<div class="api-section">
					<table class="table api-table">
						<thead>
							<tr>
								<th>Username</th>
								<th>Password</th>
							</tr>
						</thead>
						<tbody>
							<tr class="template">
									<td>
										<div class="form-group">
											<input type="text" class="form-control each_item" name="api_settings_keys[api_username]" value="<?php echo isset($api_settings_keys['api_username'])?$api_settings_keys['api_username']:''; ?>">
										</div>
									</td>
									<td>
										<div class="form-group">
											<input type="password" class="form-control each_item" name="api_settings_keys[api_password]" value="<?php echo isset($api_settings_keys['api_password'])?$api_settings_keys['api_password']:''; ?>">
										</div>
									</td>
							</tr>
						</tbody>                    
					</table>

				</div>
				<?php submit_button(); ?>
			</form>
			</div>		
		<?php
	}

	public function register_api_settings(){
		register_setting( 'api_settings_group', 'api_settings_keys', array($this,'um_api_save_settings') );
	}

	function um_api_save_settings( $data){
		return $data;
	}

}
