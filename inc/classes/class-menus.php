<?php
/**
 * Register Menus
 *
 * @package WeMakeContentCMS
 */
namespace WEMAKECONTENTCMS_THEME\Inc;
use WEMAKECONTENTCMS_THEME\Inc\Traits\Singleton;
class Menus {
	use Singleton;
	protected function __construct() {
		// load class.
		$this->setup_hooks();
	}
	protected function setup_hooks() {
		/**
		 * Actions.
		 */
		// add_action( 'init', [ $this, 'register_menus' ] );
		
    add_filter( 'futurewordpress/project/settings/general', [ $this, 'general' ], 10, 1 );
    add_filter( 'futurewordpress/project/settings/fields', [ $this, 'menus' ], 10, 1 );
	}
	public function register_menus() {
		register_nav_menus([
			'aquila-header-menu' => esc_html__( 'Header Menu', 'we-make-content-crm' ),
			'aquila-footer-menu' => esc_html__( 'Footer Menu', 'we-make-content-crm' ),
		]);
	}
	/**
	 * Get the menu id by menu location.
	 *
	 * @param string $location
	 *
	 * @return integer
	 */
	public function get_menu_id( $location ) {
		// Get all locations
		$locations = get_nav_menu_locations();
		// Get object id by location.
		$menu_id = ! empty($locations[$location]) ? $locations[$location] : '';
		return ! empty( $menu_id ) ? $menu_id : '';
	}
	/**
	 * Get all child menus that has given parent menu id.
	 *
	 * @param array   $menu_array Menu array.
	 * @param integer $parent_id Parent menu id.
	 *
	 * @return array Child menu array.
	 */
	public function get_child_menu_items( $menu_array, $parent_id ) {
		$child_menus = [];
		if ( ! empty( $menu_array ) && is_array( $menu_array ) ) {
			foreach ( $menu_array as $menu ) {
				if ( intval( $menu->menu_item_parent ) === $parent_id ) {
					array_push( $child_menus, $menu );
				}
			}
		}
		return $child_menus;
	}

  /**
   * WordPress Option page.
   * 
   * @return array
   */
	public function general( $args ) {
		return $args;
	}
	public function menus( $args ) {
    // get_FwpOption( 'key', 'default' ) | apply_filters( 'futurewordpress/project/system/getoption', 'key', 'default' )
		// is_FwpActive( 'key' ) | apply_filters( 'futurewordpress/project/system/isactive', 'key' )
		$args = [];
		$args['standard'] 		= [
			'title'							=> __( 'General', 'we-make-content-crm' ),
			'description'				=> __( 'Generel fields comst commonly used to changed.', 'we-make-content-crm' ),
			'fields'						=> [
				[
					'id' 						=> 'general-enable',
					'label'					=> __( 'Enable', 'we-make-content-crm' ),
					'description'		=> __( 'Mark to enable function of this Plugin.', 'we-make-content-crm' ),
					'type'					=> 'checkbox',
					'default'				=> true
				],
				[
					'id' 						=> 'general-address',
					'label'					=> __( 'Address', 'we-make-content-crm' ),
					'description'		=> __( 'Company address, that might be used on invoice and any public place if needed.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'general-archivedelete',
					'label'					=> __( 'Archive delete', 'we-make-content-crm' ),
					'description'		=> __( 'Enable archive delete permission on frontend, so that user can delete archive files and data from their profile.', 'we-make-content-crm' ),
					'type'					=> 'checkbox',
					'default'				=> ''
				],
			]
		];
		$args['permalink'] 		= [
			'title'						=> __( 'Permalink', 'we-make-content-crm' ),
			'description'			=> __( 'Setup some permalink like dashboard and like this kind of things.', 'we-make-content-crm' ),
			'fields'					=> [
				[
					'id' 							=> 'permalink-dashboard',
					'label'						=> __( 'Dashboard Slug', 'we-make-content-crm' ),
					'description'			=> __( 'Enable dashboard parent Slug. By default it is "/dashboard". Each time you change this field you\'ve to re-save permalink settings.', 'we-make-content-crm' ),
					'type'						=> 'text',
					'default'					=> 'dashboard'
				],
				[
					'id' 						=> 'permalink-userby',
					'label'					=> __( 'Dashboard Slug', 'we-make-content-crm' ),
					'description'		=> __( 'Enable dashboard parent Slug. By default it is "/dashboard".', 'we-make-content-crm' ),
					'type'					=> 'radio',
					'default'				=> 'id',
					'options'				=> [ 'id' => __( 'User ID', 'we-make-content-crm' ), 'slug' => __( 'User Unique Name', 'we-make-content-crm' ) ]
				],
			]
		];
		$args['dashboard'] 		= [
			'title'							=> __( 'Dashboard', 'we-make-content-crm' ),
			'description'				=> __( 'Dashboard necessery fields, text and settings can configure here. Some tags on usable fields can be replace from here.', 'we-make-content-crm' ) . $this->commontags( true ),
			'fields'						=> [
				[
					'id' 						=> 'dashboard-title',
					'label'					=> __( 'Dashboard title', 'we-make-content-crm' ),
					'description'		=> __( 'The title on dahsboard page. make sure you user tags properly.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> sprintf( __( 'Client Dashoard | %s | %s', 'we-make-content-crm' ), '{username}', '{sitename}' )
				],
				[
					'id' 						=> 'dashboard-yearstart',
					'label'					=> __( 'Year Starts', 'we-make-content-crm' ),
					'description'		=> __( 'The Year range on dashboard starts from.', 'we-make-content-crm' ),
					'type'					=> 'number',
					'default'				=> date( 'Y' )
				],
				[
					'id' 						=> 'dashboard-yearend',
					'label'					=> __( 'Yeah Ends with', 'we-make-content-crm' ),
					'description'		=> __( 'The Year range on dashboard ends on.', 'we-make-content-crm' ),
					'type'					=> 'number',
					'default'				=> ( date( 'Y' ) + 3 )
				],
				[
					'id' 						=> 'dashboard-headerbg',
					'label'					=> __( 'Header Background', 'we-make-content-crm' ),
					'description'		=> __( 'Dashboard header background image url.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
			]
		];
		$args['links'] 		= [
			'title'							=> __( 'Links', 'we-make-content-crm' ),
			'description'				=> __( 'Documentation feature and their links can be change from here. If you leave blank anything then these "Learn More" never display.', 'we-make-content-crm' ) . $this->commontags( true ),
			'fields'						=> [
				[
					'id' 						=> 'docs-monthlyretainer',
					'label'					=> __( 'Monthly Retainer', 'we-make-content-crm' ),
					'description'		=> __( 'Your Monthly retainer that could be chaged anytime. Once you\'ve changed this amount, will be sync with your stripe account.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-monthlyretainerurl',
					'label'					=> __( 'Learn more', 'we-make-content-crm' ),
					'description'		=> __( 'The URL to place on Learn more.', 'we-make-content-crm' ),
					'type'					=> 'url',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-contentcalendly',
					'label'					=> __( 'Content Calendar', 'we-make-content-crm' ),
					'description'		=> __( 'See your content calendar on Calendly.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-contentcalendlyurl',
					'label'					=> __( 'Learn more', 'we-make-content-crm' ),
					'description'		=> __( 'The URL to place on Learn more.', 'we-make-content-crm' ),
					'type'					=> 'url',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-contentlibrary',
					'label'					=> __( 'Content Library', 'we-make-content-crm' ),
					'description'		=> __( 'Open content library from here.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-contentlibraryurl',
					'label'					=> __( 'Learn more', 'we-make-content-crm' ),
					'description'		=> __( 'The URL to place on Learn more.', 'we-make-content-crm' ),
					'type'					=> 'url',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-clientrowvideos',
					'label'					=> __( 'Client Raw Video Archive', 'we-make-content-crm' ),
					'description'		=> __( 'All of the video files are here. Click on the buton to open all archive list.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-clientrowvideosurl',
					'label'					=> __( 'Learn more', 'we-make-content-crm' ),
					'description'		=> __( 'The URL to place on Learn more.', 'we-make-content-crm' ),
					'type'					=> 'url',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-manageretainer',
					'label'					=> __( 'Manage your Retainer', 'we-make-content-crm' ),
					'description'		=> __( 'Manage your retainer from here. You can pause or cancel it from here.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-manageretainerurl',
					'label'					=> __( 'Learn more', 'we-make-content-crm' ),
					'description'		=> __( 'The URL to place on Learn more.', 'we-make-content-crm' ),
					'type'					=> 'url',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-paymenthistory',
					'label'					=> __( 'Payment History', 'we-make-content-crm' ),
					'description'		=> __( 'Payment history is synced form your stripe account since you started subscription.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-paymenthistoryurl',
					'label'					=> __( 'Learn more', 'we-make-content-crm' ),
					'description'		=> __( 'The URL to place on Learn more.', 'we-make-content-crm' ),
					'type'					=> 'url',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-changepassword',
					'label'					=> __( 'Payment History', 'we-make-content-crm' ),
					'description'		=> __( 'Change your password from here. This won\'t store on our database. Only encrypted password we store and make sure you\'ve saved your password on a safe place.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-changepasswordurl',
					'label'					=> __( 'Learn more', 'we-make-content-crm' ),
					'description'		=> __( 'The URL to place on Learn more.', 'we-make-content-crm' ),
					'type'					=> 'url',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-emailaddress',
					'label'					=> __( 'Email Address', 'we-make-content-crm' ),
					'description'		=> __( 'Email address required. Don\'t worry, we won\'t sent spam.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-emailaddressurl',
					'label'					=> __( 'Learn more', 'we-make-content-crm' ),
					'description'		=> __( 'The URL to place on Learn more.', 'we-make-content-crm' ),
					'type'					=> 'url',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-contactnumber',
					'label'					=> __( 'Contact Number', 'we-make-content-crm' ),
					'description'		=> __( 'Your conatct number is necessery in case if you need to communicate with you.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-contactnumberurl',
					'label'					=> __( 'Learn more', 'we-make-content-crm' ),
					'description'		=> __( 'The URL to place on Learn more.', 'we-make-content-crm' ),
					'type'					=> 'url',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-website',
					'label'					=> __( 'Website URL', 'we-make-content-crm' ),
					'description'		=> __( 'Give here you websute url if you have. Some case we might need to get idea about your and your company information.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'docs-websiteurl',
					'label'					=> __( 'Learn more', 'we-make-content-crm' ),
					'description'		=> __( 'The URL to place on Learn more.', 'we-make-content-crm' ),
					'type'					=> 'url',
					'default'				=> ''
				],
			]
		];
		$args['rest'] 		= [
			'title'							=> __( 'Rest API', 'we-make-content-crm' ),
			'description'				=> __( 'Setup what happened when a rest api request fired on this site.', 'we-make-content-crm' ),
			'fields'						=> [
				[
					'id' 						=> 'rest-createprofile',
					'label'					=> __( 'Create profile', 'we-make-content-crm' ),
					'description'		=> __( 'When a request email doesn\'t match any account, so will it create a new user account?.', 'we-make-content-crm' ),
					'type'					=> 'checkbox',
					'default'				=> true
				],
				[
					'id' 						=> 'rest-updateprofile',
					'label'					=> __( 'Update profile', 'we-make-content-crm' ),
					'description'		=> __( 'When a request email detected an account, so will it update profile with requested information?.', 'we-make-content-crm' ),
					'type'					=> 'checkbox',
					'default'				=> false
				],
				[
					'id' 						=> 'rest-preventemail',
					'label'					=> __( 'Prevent Email', 'we-make-content-crm' ),
					'description'		=> __( 'Creating an account will send an email by default. Would you like to prevent sending email from rest request operation?', 'we-make-content-crm' ),
					'type'					=> 'checkbox',
					'default'				=> true
				],
				[
					'id' 						=> 'rest-defaultpass',
					'label'					=> __( 'Default Password', 'we-make-content-crm' ),
					'description'		=> __( 'The default password will be applied if any request contains emoty password or doesn\'t. Default value is random number.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
			]
		];
		$args['auth'] 		= [
			'title'							=> __( 'Social Auth', 'we-make-content-crm' ),
			'description'				=> __( 'Social anuthentication requeired provider API keys and some essential information. Claim them and setup here. Every API has an expiry date. So further if you face any problem with social authentication, make sure if api validity expired.', 'we-make-content-crm' ),
			'fields'						=> [
				[
					'id' 						=> 'auth-enable',
					'label'					=> __( 'Enable Social Authetication', 'we-make-content-crm' ),
					'description'		=> __( 'Mark this field to run social authentication. Once you disable from here, social authentication will be disabled from everywhere.', 'we-make-content-crm' ),
					'type'					=> 'checkbox',
					'default'				=> true
				],
				[
					'id' 						=> 'auth-google',
					'label'					=> __( 'Enable Google Authetication', 'we-make-content-crm' ),
					'description'		=> __( 'If you don\'t want to enable google authentication, you can disable this function from here.', 'we-make-content-crm' ),
					'type'					=> 'checkbox',
					'default'				=> true
				],
				[
					'id' 						=> 'auth-connectdrive',
					'label'					=> __( 'Connect with Google Drive?', 'we-make-content-crm' ),
					'description'		=> sprintf( __( 'Click on this %slink%s and allow access to connect with it.', 'we-make-content-crm' ), '<a href="'. site_url( '/auth/drive/redirect/' ) . '" target="_blank">', '</a>' ),
					'type'					=> 'textcontent'
				],
				[
					'id' 						=> 'auth-googleclientid',
					'label'					=> __( 'Google Client ID', 'we-make-content-crm' ),
					'description'		=> __( 'Your Google client or App ID, that you created for Authenticate.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'auth-googleclientsecret',
					'label'					=> __( 'Google Client Secret', 'we-make-content-crm' ),
					'description'		=> __( 'Your Google client or App Secret. Is required here.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'auth-googledrivefolder',
					'label'					=> __( 'Storage Folder ID', 'we-make-content-crm' ),
					'description'		=> __( 'ID of that specific folder where you want to sync files.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'auth-googleclientredirect',
					'label'					=> __( 'Google App Redirect', 'we-make-content-crm' ),
					'description'		=> __( 'Place this link on Google Auth Callback or Redirect field on your Google App.', 'we-make-content-crm' ) . '<code>' . apply_filters( 'futurewordpress/project/socialauth/redirect', '/handle/google', 'google' ) . '</code>',
					'type'					=> 'textcontent'
				],
				[
					'id' 						=> 'auth-googleauthlink',
					'label'					=> __( 'Google Auth Link', 'we-make-content-crm' ),
					'description'		=> __( 'Use this link on your "Login with Google" button.', 'we-make-content-crm' ) . '<code>' . apply_filters( 'futurewordpress/project/socialauth/link', '/auth/google', 'google' ) . '</code>',
					'type'					=> 'textcontent'
				],
			]
		];
		$args['signature'] 		= [
			'title'							=> __( 'E-Signature', 'we-make-content-crm' ),
			'description'				=> __( 'Setup e-signature plugin some customize settings from here. Four tags for Contract is given below.', 'we-make-content-crm' ) . $this->contractTags( ['{client_name}','{client_address}','{todays_date}','{retainer_amount}'] ),
			'fields'						=> [
				[
					'id' 						=> 'signature-addressplaceholder',
					'label'					=> __( 'Address Placeholder', 'we-make-content-crm' ),
					'description'		=> __( 'What shouldbe replace if address1 & address2 both are empty. If you leave it blank, then it\'ll be blank.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> 'N/A'
				],
				[
					'id' 						=> 'signature-dateformat',
					'label'					=> __( 'Date formate', 'we-make-content-crm' ),
					'description'		=> __( 'The date format which will apply on {{todays_date}} place.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> get_option('date_format')
				],
				[
					'id' 						=> 'signature-emptyrrtainer',
					'label'					=> __( 'Empty Retainer amount', 'we-make-content-crm' ),
					'description'		=> __( 'if anytime we found empty retainer amount, so what will be replace there?', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> 'N/A'
				],
				[
					'id' 						=> 'signature-defaultcontract',
					'label'					=> __( 'Default contract form', 'we-make-content-crm' ),
					'description'		=> __( 'When admin doesn\'t select a registration from before sending it to client, user is taken to this contract. It should be a page where a simple wp-form will apear with client name, service type, retainer amount if necessery.', 'we-make-content-crm' ),
					'type'					=> 'url',
					'default'				=> ''
				],
			]
		];
		$args['email'] 		= [
			'title'							=> __( 'E-Mail', 'we-make-content-crm' ),
			'description'				=> __( 'Setup email configuration here', 'we-make-content-crm' ) . $this->contractTags( ['{client_name}','{client_address}','{todays_date}','{retainer_amount}', '{registration_link}', '{{site_name}}', '{{passwordreset_link}}' ] ),
			'fields'						=> [
				// [
				// 	'id' 						=> 'email-registationlink',
				// 	'label'					=> __( 'Registration Link', 'we-make-content-crm' ),
				// 	'description'		=> __( 'Registration link that contains WP-Form registration form.', 'we-make-content-crm' ),
				// 	'type'					=> 'text',
				// 	'default'				=> "https://wemakecontent.net/test-page/"
				// ],
				[
					'id' 						=> 'email-registationsubject',
					'label'					=> __( 'Subject', 'we-make-content-crm' ),
					'description'		=> __( 'The Subject, used on registration link sending mail.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> "Invitation to Register for [Event/Service/Product]"
				],
				[
					'id' 						=> 'email-sendername',
					'label'					=> __( 'Sender name', 'we-make-content-crm' ),
					'description'		=> __( 'Sender name that should be on mail metadata..', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> "Invitation to Register for [Event/Service/Product]"
				],
				[
					'id' 						=> 'email-registationbody',
					'label'					=> __( 'Registration link Template', 'we-make-content-crm' ),
					'description'		=> __( 'The template, used on registration link sending mail.', 'we-make-content-crm' ),
					'type'					=> 'textarea',
					'default'				=> "Dear [Name],\nWe are delighted to invite you to join us for [Event/Service/Product], a [brief description of event/service/product].\n[Event/Service/Product] offers [brief summary of benefits or features]. As a valued member of our community, we would like to extend a special invitation for you to be part of this exciting opportunity.\nTo register, simply click on the link below:\n[Registration link]\nShould you have any questions or require additional information, please do not hesitate to contact us at [contact information].\nWe look forward to seeing you at [Event/Service/Product].\nBest regards,\n[Your Name/Company Name]",
					'attr'					=> [ 'data-tinymce' => true ]
				],
				[
					'id' 						=> 'email-passresetsubject',
					'label'					=> __( 'Password Reset Subject', 'we-make-content-crm' ),
					'description'		=> __( 'The email subject on password reset mail.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> __( 'Password Reset Request',   'we-make-content-crm' )
				],
				[
					'id' 						=> 'email-passresetbody',
					'label'					=> __( 'Password Reset Template', 'we-make-content-crm' ),
					'description'		=> __( 'The template, used on password reset link sending mail.', 'we-make-content-crm' ),
					'type'					=> 'textarea',
					'default'				=> "Dear {{client_name}},\n\nYou recently requested to reset your password for your {{site_name}} account. Please follow the link below to reset your password:\n\n{{passwordreset_link}}\n\nIf you did not make this request, you can safely ignore this email.\n\nBest regards,\n{{site_name}} Team"
				],
			]
		];
		$args['stripe'] 		= [
			'title'							=> __( 'Stripe', 'we-make-content-crm' ),
			'description'				=> __( 'Stripe payment system configuration process should be do carefully. Here some field is importent to work with no inturrupt. Such as API key or secret key, if it\'s expired on your stripe id, it won\'t work here. New user could face problem fo that reason.', 'we-make-content-crm' ),
			'fields'						=> [
				[
					'id' 						=> 'stripe-cancelsubscription',
					'label'					=> __( 'Cancellation', 'we-make-content-crm' ),
					'description'		=> __( 'Enable it to make a possibility to user to cancel subscription from client dashboard.', 'we-make-content-crm' ),
					'type'					=> 'checkbox',
					'default'				=> false
				],
				[
					'id' 						=> 'stripe-publishablekey',
					'label'					=> __( 'Publishable Key', 'we-make-content-crm' ),
					'description'		=> __( 'The key which is secure, could import into JS, and is safe evenif any thirdparty got those code. Note that, secret key is not a publishable key.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'stripe-secretkey',
					'label'					=> __( 'Secret Key', 'we-make-content-crm' ),
					'description'		=> __( 'The secret key that never share with any kind of frontend functionalities and is ofr backend purpose. Is required.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'stripe-currency',
					'label'					=> __( 'Currency', 'we-make-content-crm' ),
					'description'		=> __( 'Default currency which will use to create payment link.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> 'usd'
				],
				[
					'id' 						=> 'stripe-productname',
					'label'					=> __( 'Product name text', 'we-make-content-crm' ),
					'description'		=> __( 'A text to show on product name place on checkout sanbox.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> __( 'Subscription',   'we-make-content-crm' )
				],
				[
					'id' 						=> 'stripe-productdesc',
					'label'					=> __( 'Product Description', 'we-make-content-crm' ),
					'description'		=> __( 'Some text to show on product description field.', 'we-make-content-crm' ),
					'type'					=> 'text',
					'default'				=> __( 'Payment for',   'we-make-content-crm' ) . ' ' . get_option( 'blogname', 'We Make Content' )
				],
				[
					'id' 						=> 'stripe-productimg',
					'label'					=> __( 'Product Image', 'we-make-content-crm' ),
					'description'		=> __( 'A valid image url for product. If image url are wrong or image doesn\'t detect by stripe, process will fail.', 'we-make-content-crm' ),
					'type'					=> 'url',
					'default'				=> esc_url( WEMAKECONTENTCMS_BUILD_URI . '/icons/Online payment_Flatline.svg' )
				],
				[
					'id' 						=> 'stripe-paymentmethod',
					'label'					=> __( 'Payment Method', 'we-make-content-crm' ),
					'description'		=> __( 'Select which payment method you will love to get payment.', 'we-make-content-crm' ),
					'type'					=> 'select',
					'default'				=> 'card',
					'options'				=> apply_filters( 'futurewordpress/project/payment/stripe/payment_methods', [] )
				],
			]
		];
		$args['regis'] 		= [
			'title'							=> __( 'Registrations', 'we-make-content-crm' ),
			'description'				=> __( 'Setup registration link and WP-forms information here.', 'we-make-content-crm' ),
			'fields'						=> [
				[
					'id' 						=> 'regis-rows',
					'label'					=> __( 'Rows', 'we-make-content-crm' ),
					'description'		=> __( 'How many registration links do you have.', 'we-make-content-crm' ),
					'type'					=> 'number',
					'default'				=> 2
				],
			]
		];
		for( $i = 1;$i <= apply_filters( 'futurewordpress/project/system/getoption', 'regis-rows', 3 ); $i++ ) {
			$args['regis'][ 'fields' ][] = [
				'id' 						=> 'regis-link-title-' . $i,
				'label'					=> __( 'Link title #' . $i, 'we-make-content-crm' ),
				'description'		=> '',
				'type'					=> 'text',
				'default'				=> 'Link #' . $i
			];
			$args['regis'][ 'fields' ][] = [
				'id' 						=> 'regis-link-url-' . $i,
				'label'					=> __( 'Link URL #' . $i, 'we-make-content-crm' ),
				'description'		=> '',
				'type'					=> 'url',
				'default'				=> ''
			];
		}
		$args['docs'] 		= [
			'title'							=> __( 'Documentations', 'we-make-content-crm' ),
			'description'				=> __( 'The workprocess is tring to explain here.', 'we-make-content-crm' ),
			'fields'						=> [
				[
					'id' 						=> 'auth-brifing',
					'label'					=> __( 'How to setup thank you page?', 'we-make-content-crm' ),
					'description'		=> sprintf( __( 'first go to %sthis link%s Create or Edit an "Stand Alone" document. Give your thankyou custom page link here %s', 'we-make-content-crm' ), '<a href="'. admin_url( 'admin.php?page=esign-docs&document_status=stand_alone' ) . '" target="_blank">', '</a>', '<img src="' . WEMAKECONTENTCMS_DIR_URI . '/docs/Stand-alone-esign-metabox.PNG' . '" alt="" />' ),
					'type'					=> 'textcontent'
				],
			]
		];
		return $args;
	}
	/**
	 * Supply necessry tags that could be replace on frontend.
	 * 
	 * @return string
	 * @return array
	 */
	public function commontags( $html = false ) {
		$arg = [];$tags = [
			'username', 'sitename', 
		];
		if( $html === false ) {return $tags;}
		foreach( $tags as $tag ) {
			$arg[] = sprintf( "%s{$tag}%s", '<code>{', '}</code>' );
		}
		return implode( ', ', $arg );
	}
	public function contractTags( $tags ) {
		$arg = [];
		foreach( $tags as $tag ) {
			$arg[] = sprintf( "%s{$tag}%s", '<code>{', '}</code>' );
		}
		return implode( ', ', $arg );
	}
}

/**
 * {{client_name}}, {{client_address}}, {{todays_date}}, {{retainer_amount}}
 */
