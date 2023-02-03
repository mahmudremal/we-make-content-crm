<?php
/**
 * Checkout video clip shortner template.
 * 
 * @package WeMakeContentCMS
 */
is_user_logged_in() || auth_redirect();


$user_profile = get_query_var( 'user_profile' );$errorHappens = false;
// if( get_current_user_id() == $user_profile ) {}
// print_r( get_userdata( $user_profile ) );

// 'id | ID | slug | email | login ', $user_profile
$userInfo = get_user_by( apply_filters( 'futurewordpress/project/system/getoption', 'permalink-userby', 'id' ), $user_profile );
$userMeta = array_map( function( $a ){ return $a[0]; }, get_user_meta( $userInfo->ID ) );
$userInfo = (object) wp_parse_args( $userInfo, [
  'id'            => '',
  'meta'          => (object) apply_filters( 'futurewordpress/project/usermeta/defaults', (array) $userMeta )
] );
if( $errorHappens ) :
  // http_response_code( 404 );
  // status_header( 404, 'Course not found' );
  add_filter( 'pre_get_document_title', function( $title ) {global $errorHappens;return $errorHappens;}, 10, 1 );
  wp_die( $errorHappens, __( 'Error Happens', 'we-make-content-crm' ) );
else :
  add_filter( 'pre_get_document_title', function( $title ) {
    $title = apply_filters( 'futurewordpress/project/system/getoption', 'dashboard-title', __( 'User Dashbord', 'we-make-content-crm' ) );
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
                <?php include WEMAKECONTENTCMS_DIR_PATH . '/templates/dashboard/cards/content.php'; ?>
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