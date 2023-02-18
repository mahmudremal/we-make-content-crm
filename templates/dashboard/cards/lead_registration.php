<?php
/**
 * Checkout video clip shortner template.
 * 
 * @package WeMakeContentCMS
 */
$userInfo = get_user_by( 'id', hex2bin( get_query_var( 'lead_registration' ) ) );
if( is_user_logged_in() ) {
  $user_slug = apply_filters( 'futurewordpress/project/system/getoption', 'permalink-dashboard', 'dashboard' ) . '/' . ( ( apply_filters( 'futurewordpress/project/system/getoption', 'permalink-userby', 'id' ) == 'id' ) ? $userInfo->ID : $userInfo->data->user_login );
  wp_redirect( site_url( $user_slug ) );
}
// $_SESSION[ 'current-lead' ] = $userInfo->ID;
if( get_transient( '_lead_user_registration-' . apply_filters( 'futurewordpress/project/user/visitorip', '' ) ) ) {
  delete_transient( '_lead_user_registration-' . apply_filters( 'futurewordpress/project/user/visitorip', '' ) );
}
set_transient( '_lead_user_registration-' . apply_filters( 'futurewordpress/project/user/visitorip', '' ), $userInfo->ID, 7200 );
// if( function_exists( 'setcookie' ) ) {setcookie( '_lead_user_registration', $userInfo->ID, time()+31556926 );}

$is_done = get_user_meta( $userInfo->ID, 'registration_done', true );
if( $is_done && $is_done >= 100 ) {
  wp_redirect( apply_filters( 'futurewordpress/project/user/dashboardpermalink', $userInfo->ID, $userInfo->data->user_nicename ) );
}

$regLink = get_user_meta( $userInfo->ID, 'contract_type', true );
$regLink = apply_filters( 'futurewordpress/project/system/getoption', 'regis-link-url-' . $regLink, false );
// if( $regLink && ! empty( $regLink ) ) {$regLink = get_the_permalink( $regLink );}

if( $regLink && ! empty( $regLink ) ) {wp_redirect( esc_url( $regLink ) );} else {wp_die( __( 'Regisatration link not found.', 'domain' ) );}exit;


$userMeta = array_map( function( $a ){ return $a[0]; }, (array) get_user_meta( $userInfo->ID ) );
$userInfo = (object) wp_parse_args( $userInfo, [
  'id'            => '',
  'meta'          => (object) apply_filters( 'futurewordpress/project/usermeta/defaults', (array) $userMeta )
] );
$errorHappens = false;
// if( get_current_user_id() == $user_profile ) {}
// print_r( $userInfo );

// 'id | ID | slug | email | login ', $user_profile

if( is_wp_error( $userInfo ) || $errorHappens ) :
  http_response_code( 404 );
  status_header( 404, 'Page not found' );
  add_filter( 'pre_get_document_title', function( $title ) {global $errorHappens;return $errorHappens;}, 10, 1 );
  wp_die( $errorHappens, __( 'Error Happens', 'we-make-content-crm' ) );
else :
  add_filter( 'pre_get_document_title', function( $title ) {
    $title = apply_filters( 'futurewordpress/project/system/getoption', 'dashboard-title', __( 'Registration Field', 'we-make-content-crm' ) );
    return $title;
  }, 10, 1 );
  get_header();
  ?>
  
<div class="d-flex flex-column flex-root">
  <div class="page d-flex flex-row flex-column-fluid">
    <div class="wrapper d-flex flex-column flex-row-fluid">
      <div class="d-flex flex-column flex-column-fluid">
        <?php // include WEMAKECONTENTCMS_DIR_PATH . '/templates/dashboard/cards/toolbar.php'; ?>

        <div class="content fs-6 d-flex flex-column-fluid">
          <div class="container-xxl">
            <div class="row g-5 g-xxl-12">
              <div class="col-xl-12">
                <?php include WEMAKECONTENTCMS_DIR_PATH . '/templates/dashboard/cards/form-wizard.php'; ?>
              </div>
            </div>
          </div>
        </div>

        <?php // include WEMAKECONTENTCMS_DIR_PATH . '/templates/dashboard/cards/footer.php'; ?>
      
      </div>
    </div>

    <?php // include WEMAKECONTENTCMS_DIR_PATH . '/templates/dashboard/cards/sidebar.php'; ?>

  </div>
</div>
<style>
.content.fs-6.d-flex.flex-column-fluid {
  margin-top: 5rem;
  display: block;
  position: relative;
}
</style>



  <?php
endif;
get_footer();
?>