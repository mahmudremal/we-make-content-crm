<?php
/**
 * Checkout video clip shortner template.
 * 
 * @package WeMakeContentCMS
 */
is_user_logged_in() || auth_redirect();

global $currenttab;global $user_profile;global $userInfo;global $errorHappens;
$user_profile = get_query_var( 'user_profile' );
if( $user_profile == 'me' ) {
  $userInfo = wp_get_current_user();
  wp_redirect( apply_filters( 'futurewordpress/project/user/dashboardpermalink', $userInfo->ID, $userInfo->data->user_nicename ) );
}
$currenttab = get_query_var( 'currenttab' );
$allowedTabs = [ 'profile', 'archive', 'payments', 'settings' ];
$errorHappens = false;
// if( get_current_user_id() == $user_profile ) {}
// print_r( get_userdata( $user_profile ) );

// 'id | ID | slug | email | login ', $user_profile
$userInfo = get_user_by( apply_filters( 'futurewordpress/project/system/getoption', 'permalink-userby', 'id' ), $user_profile );

if( $currenttab && in_array( $currenttab, $allowedTabs ) ) {
  add_filter( 'futurewordpress/project/javascript/siteconfig', function( $args ) {
    global $currenttab;global $user_profile;global $userInfo;
    $args[ 'profile' ] = [
      'profilePath'         => apply_filters( 'futurewordpress/project/user/dashboardpermalink', $userInfo->ID, $userInfo->data->user_nicename ),
      'currentTab'          => $currenttab
    ];return $args;
  }, 10, 1 );
}
if( is_wp_error( $userInfo ) || $errorHappens ) :
  http_response_code( 404 );
  status_header( 404, 'Page not found' );
  add_filter( 'pre_get_document_title', function( $title ) {global $errorHappens;return $errorHappens;}, 99, 1 );
  wp_die( $errorHappens, __( 'Error Happens', 'we-make-content-crm' ) );
else :
  $userMeta = array_map( function( $a ){ return $a[0]; }, (array) get_user_meta( $userInfo->ID ) );
  $userInfo = (object) wp_parse_args( $userInfo, [
  'id'      => '',
  'meta'      => (object) apply_filters( 'futurewordpress/project/usermeta/defaults', (array) $userMeta )
  ] );
  add_filter( 'pre_get_document_title', function( $title ) {
    global $userInfo;
    $title = apply_filters( 'futurewordpress/project/system/getoption', 'dashboard-title', __( 'User Dashbord', 'we-make-content-crm' ) );
    $title = str_replace( [
      '{username}', '{sitename}'
    ], [
      $userInfo->meta->first_name . ' ' . $userInfo->meta->last_name,
      get_option( 'blogname', 'We Make Content' )
    ], $title );
    return $title;
  }, 99, 1 );
  // echo '<pre style="display: none;">';print_r( $userInfo );echo '</pre>';
  get_header();
  ?>
<main class="main-content">
  <div class="position-relative  iq-banner ">
  
  <!-- Nav Header Component Start -->
  <div class="iq-navbar-header " style="height: 215px;">
    <div class="container-fluid iq-container">
      <div class="row">
        <div class="col-md-12">
          <div class="flex-wrap d-flex justify-content-between align-items-center">
          </div>
        </div>
      </div>
    </div>
    <div class="iq-header-img">
      <img src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/dashboard/top-header.png" alt="header" class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX" loading="lazy">
    </div>
  </div>
  <!-- Nav Header Component End -->
  <!--Nav End-->
  </div>
  <div class="content-inner pb-0 container" id="page_layout">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap align-items-center justify-content-between">
          <div class="d-flex flex-wrap align-items-center">
            <div class="profile-img position-relative me-3 mb-3 mb-lg-0 profile-logo profile-logo1">
            <img src="<?php echo esc_url( get_avatar_url( $user->ID, ['size' => '100'] ) ); ?>" alt="User-Profile" class="theme-color-default-img img-fluid rounded-pill avatar-100" loading="lazy">
            </div>
            <div class="d-flex flex-wrap align-items-center mb-3 mb-sm-0">
            <h4 class="me-2 h4"><?php echo esc_html( $userInfo->meta->first_name . ' ' . $userInfo->meta->last_name ); ?></h4>
            <span><?php echo esc_html( ! empty( $userInfo->meta->city ) ? ' - ' . $userInfo->meta->city : '' ); ?></span>
            </div>
          </div>
          <ul class="d-flex nav nav-pills mb-0 text-center profile-tab nav-slider" data-toggle="slider-tab" id="profile-pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
            <a class="nav-link active show" data-bs-toggle="tab" href="#profile-profile" role="tab" aria-selected="true" tabindex="-1"><?php esc_html_e( 'Profile',   'we-make-content-crm' ); ?></a>
            </li>
            <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#profile-archive" role="tab" aria-selected="false"><?php esc_html_e( 'Archive',   'we-make-content-crm' ); ?></a>
            </li>
            <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#profile-payments" role="tab" aria-selected="false" tabindex="-1"><?php esc_html_e( 'Payments',   'we-make-content-crm' ); ?></a>
            </li>
            <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#profile-settings" role="tab" aria-selected="false" tabindex="-1"><?php esc_html_e( 'Settings',   'we-make-content-crm' ); ?></a>
            </li>
          </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3">
    <div class="card">
      <div class="card-header">
      <div class="header-title">
        <h4 class="card-title"><?php esc_html_e( 'Overview',   'we-make-content-crm' ); ?></h4>
      </div>
      </div>
      <div class="card-body">

        <?php if( ! empty( $userInfo->meta->monthly_retainer ) ): ?>
        <div class="text-center m-auto">
          <h4><?php esc_html_e( 'Monthly Retainer', 'we-make-content-crm' ); ?></h4>
          <h2 class="counter mb-2">$<?php echo esc_attr( number_format_i18n( $userInfo->meta->monthly_retainer, 2 ) ); ?></h2>
        </div>
        <div class="separator separator-dashed my-8"></div>
        <?php endif; ?>

        <ul class="list-inline m-0 p-0">
          <li class="d-flex mb-4 align-items-center active">
          <img src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/shapes/02.png" data-img="<?php echo esc_url( WEMAKECONTENTCMS_BUILD_URI . '/icons/Content creation_Flatline.svg' ); ?>" alt="story-img" class="rounded-pill avatar-70 p-1 border bg-soft-light img-fluid" loading="lazy">
          <div class="ms-3">
            <a class="" href="#" target="_blank">
              <h5><?php esc_html_e( 'Content Calendar',   'we-make-content-crm' ); ?></h5>
              <!-- <p class="mb-0">Added 1 hour ago</p> -->
            </a>
          </div>
          </li>
          <li class="d-flex mb-4 align-items-center">
          <!-- https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/shapes/04.png -->
          <img src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/shapes/06.png" data-img="<?php echo esc_url( WEMAKECONTENTCMS_BUILD_URI . '/icons/Information carousel_Monochromatic.svg' ); ?>" alt="story-img" class="rounded-pill avatar-70 p-1 border img-fluid bg-soft-danger" loading="lazy">
          <div class="ms-3">
            <a class="" href="#" target="_blank">
              <h5><?php esc_html_e( 'Content Library',   'we-make-content-crm' ); ?></h5>
              <!-- <p class="mb-0">Added 1 hour ago</p> -->
            </a>
          </div>
          </li>
        </ul>
      </div>
    </div>
    </div>
    <div class="col-lg-9">
    <div class="profile-content tab-content iq-tab-fade-up">

      <div id="profile-profile" class="tab-pane fade active show" role="tabpanel">
        <div class="card">
          <div class="card-header">
            <div class="header-title">
              <h4 class="card-title">Profile</h4>
            </div>
          </div>
          <div class="card-body">
            <div class="text-center">
              <div>
              <!-- https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/avatars/01.png -->
              <img src="<?php echo esc_url( get_avatar_url( $user->ID, ['size' => '100'] ) ); ?>" alt="profile-img" class="rounded-pill avatar-130 img-fluid" loading="lazy">
              </div>
              <div class="mt-3">
              <h3 class="d-inline-block"><?php echo esc_html( $userInfo->meta->first_name . ' ' . $userInfo->meta->last_name ); ?></h3>
              <p class="d-inline-block pl-3"><?php echo esc_html( ! empty( $userInfo->meta->city ) ? ' - ' . $userInfo->meta->city : '' ); ?></p>
              <p class="mb-0"><?php echo esc_html( $userInfo->meta->description ); ?></p>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <div class="header-title">
              <h4 class="card-title"><?php echo esc_html( sprintf( __( 'About %s',   'we-make-content-crm' ), $userInfo->data->display_name ) ); ?></h4>
            </div>
          </div>
          <div class="card-body">
            <div class="user-bio">
              <!-- <p>Tart I love sugar plum I love oat cake. Sweet roll caramels I love jujubes. Topping cake wafer.</p> -->
            </div>
            <div class="mt-2">
            <h6 class="mb-1"><?php esc_html_e( 'Joined:',   'we-make-content-crm' ); ?></h6>
            <p> <?php echo esc_html( wp_date( 'M d, Y', strtotime( $userInfo->data->user_registered ) ) ); ?></p>
            </div>
            <div class="mt-2">
            <h6 class="mb-1"><?php esc_html_e( 'Lives:',   'we-make-content-crm' ); ?></h6>
            <?php $country = apply_filters( 'futurewordpress/project/database/countries', [], $userInfo->meta->country ); ?>
            <p> <?php echo esc_html( $userInfo->meta->city . ' - ' . $country ); ?></p>
            </div>
            <div class="mt-2">
            <h6 class="mb-1"><?php esc_html_e( 'Email:',   'we-make-content-crm' ); ?></h6>
            <p><a href="mailto:<?php echo esc_attr( ! empty( $userInfo->data->user_email ) ? $userInfo->data->user_email : $userInfo->meta->email ); ?>" class="text-body"> <?php echo esc_html( ! empty( $userInfo->data->user_email ) ? $userInfo->data->user_email : $userInfo->meta->email ); ?></a></p>
            </div>
            <div class="mt-2">
            <h6 class="mb-1"><?php esc_html_e( 'Url:',   'we-make-content-crm' ); ?></h6>
            <p><a href="<?php echo esc_url( $userInfo->meta->website ); ?>" class="text-body" target="_blank"> <?php echo esc_html( $userInfo->meta->website ); ?> </a></p>
            </div>
            <div class="mt-2">
            <h6 class="mb-1"><?php esc_html_e( 'Contact:',   'we-make-content-crm' ); ?></h6>
            <p><a href="tel:<?php echo esc_attr( $userInfo->meta->phone ); ?>" class="text-body"><?php echo esc_html( $userInfo->meta->phone ); ?></a></p>
            </div>
          </div>
        </div>
      </div>
      <div id="profile-archive" class="tab-pane fade" role="tabpanel">
        <div class="card">
          <div class="card-header d-flex justify-content-between">
            <div class="header-title">
              <h4 class="card-title"><?php esc_html_e( 'Client Raw Video Archive',   'we-make-content-crm' ); ?></h4>
            </div>
          </div>
          <div class="card-body">
            <div class="border rounded mb-3 p-2">
              <form id="the-raw-video-archive-upload" class="form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="futurewordpress/project/action/submitarchives">
                <input type="hidden" name="userid" value="<?php echo esc_attr( $userInfo->ID ); ?>">
                <?php wp_nonce_field( 'futurewordpress/project/action/submitarchives', '_nonce', true, true ); ?>
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-select form-select-lg form-select-solid mb-2" name="month" data-control="select2" data-placeholder="<?php esc_attr_e( 'Upload for...', 'we-make-content-crm' ); ?>">
                    <?php $months = [ 'Jan', 'Fav', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dev' ]; ?>
                    <?php foreach( $months as $i => $month ) :
                      $dateObj   = DateTime::createFromFormat( '!m', ( $i + 1 ) );
                      $monthName = $dateObj->format('F'); ?>
                      <option value="<?php echo esc_attr( $month ); ?>" <?php echo esc_attr( ( ( $i + 1 ) == date( 'm' ) ) ? 'selected' : '' ); ?>><?php echo esc_html( $monthName ); ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <select class="form-select form-select-lg form-select-solid mb-2" name="year" data-control="select2" data-placeholder="<?php esc_attr_e( 'Upload for...', 'we-make-content-crm' ); ?>">
                    <?php for( $i = apply_filters( 'futurewordpress/project/system/getoption', 'dashboard-yearstart', date( 'Y' ) ); $i <= apply_filters( 'futurewordpress/project/system/getoption', 'dashboard-yearend', ( date( 'Y' ) + 3 ) ); $i++ ) : ?>
                      <option value="<?php echo esc_attr( $i ); ?>" <?php echo esc_attr( ( $i == date( 'Y' ) ) ? 'selected' : '' ); ?>><?php echo esc_html( $i ); ?></option>
                    <?php endfor; ?>
                    </select>
                  </div>
                  <!-- <input class="form-control form-control-solid fwp-flatpickr-field" placeholder="<?php echo esc_attr( wp_date( 'F-Y' ) ); ?>" data-config="<?php echo esc_attr( json_encode( ['enableTime' => false,'dateFormat' => 'F-Y'] ) ); ?>"/> -->
                  <div class="col-md-12">
                    <div class="fv-row my-2">
                      <!--begin::Dropzone-->
                      <div class="dropzone fwp-dropzone-field">
                      <!--begin::Message-->
                      <div class="dz-message needsclick">
                        <!--begin::Icon-->
                        <!-- <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i> -->
                        <svg fill="none" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.46583 5.23624C8.24276 5.53752 8.26838 5.96727 8.5502 6.24C8.8502 6.54 9.3402 6.54 9.6402 6.24L11.2302 4.64V8.78H12.7702V4.64L14.3602 6.24L14.4464 6.31438C14.7477 6.53752 15.1775 6.51273 15.4502 6.24C15.6002 6.09 15.6802 5.89 15.6802 5.69C15.6802 5.5 15.6002 5.3 15.4502 5.15L12.5402 2.23L12.4495 2.14848C12.3202 2.0512 12.1602 2 12.0002 2C11.7902 2 11.6002 2.08 11.4502 2.23L8.5402 5.15L8.46583 5.23624ZM6.23116 8.78512C3.87791 8.89627 2 10.8758 2 13.2875V18.2526L2.00484 18.4651C2.1141 20.8599 4.06029 22.7802 6.45 22.7802H17.56L17.7688 22.7753C20.1221 22.6641 22 20.6843 22 18.2628V13.3078L21.9951 13.0945C21.8853 10.6909 19.93 8.7802 17.55 8.7802H12.77V14.8849L12.7629 14.9922C12.7112 15.3776 12.385 15.6683 12 15.6683C11.57 15.6683 11.23 15.3224 11.23 14.8849V8.7802H6.44L6.23116 8.78512Z" fill="currentColor" />
                        </svg>
                        <!--end::Icon-->
                        <!--begin::Info-->
                        <div class="ms-4">
                        <h3 class="fs-5 fw-bolder text-gray-900 mb-1">Drop files here or click to upload.</h3>
                        <span class="fs-7 fw-bold text-primary opacity-75">Upload up to 10 files</span>
                        </div>
                        <!--end::Info-->
                      </div>
                      </div>
                      <!--end::Dropzone-->
                    </div>
                  </div>
                  <div class="com-md-12">
                    <button type="button" class="btn btn-light-primary fw-bold btn-sm mt-3 submit-archive-files" data-config="<?php echo esc_attr( json_encode( [
                      'title' => __( 'A short title', 'we-make-content-crm' ),
                      'text' => __( 'Give here a short title s that later you could understand with it. Files those uploaded, are tranfereed already on server. While you submit it, then it\'ll make a compression and send to google drive.', 'we-make-content-crm' ),
                      'icon' => 'info',
                      'confirmButtonText' => __( 'Submit', 'we-make-content-crm' ),
                      'input' => 'text',
                      'inputAttributes' => [
                        'autocapitalize' => 'off'
                      ],
                      'showCancelButton' => true,
                      'showLoaderOnConfirm' => true
                    ] ) ); ?>" role="button"><?php esc_html_e( 'Submit & Upload', 'we-make-content-crm' ); ?></button>
                  </div>
                </div>
              </form>
            </div>
            <?php $archives = apply_filters( 'futurewordpress/project/filesystem/ziparchives', [], $userInfo->ID ); ?>

            <div class="<?php echo esc_attr( ( count( $archives ) > 0 ) ? 'custom-table-effect' : '' ); ?> table-responsive  border rounded">
              <table class="table mb-0" id="datatable" data-toggle="data-table">
                <thead>
                  <tr class="bg-white">
                    <th scope="col"><?php esc_html_e( 'Uploaded time',   'we-make-content-crm' ); ?></th>
                    <th scope="col"><?php esc_html_e( 'Attached title',   'we-make-content-crm' ); ?></th>
                    <th scope="col"><?php esc_html_e( 'Download',   'we-make-content-crm' ); ?></th>
                  </tr>
                </thead>
                <tbody>
                <?php if( count( $archives ) <= 0 ) : ?>
                    <tr>
                      <td colspan="6"><img src="<?php echo esc_url( WEMAKECONTENTCMS_BUILD_URI . '/icons/Card Payment_Monochromatic.svg' ); ?>" alt="<?php esc_attr_e( 'No Payments',   'we-make-content-crm' ); ?>"></td>
                    </tr>
                  <?php else : ?>
                    <?php foreach( $archives as $i => $archive ) :?>
                    <tr>
                      <td class=""><?php echo esc_html( $archive->formonth ); ?></td>
                      <td class=""><?php echo esc_html( $archive->title ); ?></td>
                      <td>
                        <div class="d-flex justify-content-evenly">
                          <a class="btn btn-primary btn-icon btn-sm direct-download-btn" href="<?php echo esc_url( $archive->file_path ); ?>" role="button" target="_blank">
                            <span class="btn-inner">
                              <svg fill="none" xmlns="http://www.w3.org/2000/svg" class="icon-32" width="32" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.1535 16.64L14.995 13.77C15.2822 13.47 15.2822 13 14.9851 12.71C14.698 12.42 14.2327 12.42 13.9455 12.71L12.3713 14.31V9.49C12.3713 9.07 12.0446 8.74 11.6386 8.74C11.2327 8.74 10.896 9.07 10.896 9.49V14.31L9.32178 12.71C9.03465 12.42 8.56931 12.42 8.28218 12.71C7.99505 13 7.99505 13.47 8.28218 13.77L11.1139 16.64C11.1832 16.71 11.2624 16.76 11.3515 16.8C11.4406 16.84 11.5396 16.86 11.6386 16.86C11.7376 16.86 11.8267 16.84 11.9158 16.8C12.005 16.76 12.0842 16.71 12.1535 16.64ZM19.3282 9.02561C19.5609 9.02292 19.8143 9.02 20.0446 9.02C20.302 9.02 20.5 9.22 20.5 9.47V17.51C20.5 19.99 18.5 22 16.0446 22H8.17327C5.58911 22 3.5 19.89 3.5 17.29V6.51C3.5 4.03 5.4901 2 7.96535 2H13.2525C13.5 2 13.7079 2.21 13.7079 2.46V5.68C13.7079 7.51 15.1931 9.01 17.0149 9.02C17.4333 9.02 17.8077 9.02318 18.1346 9.02595C18.3878 9.02809 18.6125 9.03 18.8069 9.03C18.9479 9.03 19.1306 9.02789 19.3282 9.02561ZM19.6045 7.5661C18.7916 7.5691 17.8322 7.5661 17.1421 7.5591C16.047 7.5591 15.145 6.6481 15.145 5.5421V2.9061C15.145 2.4751 15.6629 2.2611 15.9579 2.5721C16.7203 3.37199 17.8873 4.5978 18.8738 5.63395C19.2735 6.05379 19.6436 6.44249 19.945 6.7591C20.2342 7.0621 20.0223 7.5651 19.6045 7.5661Z" fill="currentColor" /></svg>
                            </span>
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div id="profile-payments" class="tab-pane fade" role="tabpanel">
        <div class="row">
          
          <div class="col-lg-6 col-md-6">
            <div class="card text-center">
              <div class="card-body payment-page-card">
                <h3 class=""><?php esc_html_e( 'Pause Subscription',   'we-make-content-crm' ); ?></h3>
                <div class="mt-2 form-check form-check-custom form-check-solid form-switch">
                  <input class="form-check-input fwp-form-checkbox-pause-subscribe" type="checkbox" <?php echo esc_attr( in_array( $userInfo->meta->enable_subscription, [ 'on' ] ) ? 'checked' : '' ); ?> name="meta-enable_subscription" />
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-6">
            <div class="card text-center">
              <div class="card-body payment-page-card">
                <h3 class=""><?php esc_html_e( 'Cancel Subscription',   'we-make-content-crm' ); ?></h3>
                <?php if( ! in_array( $userInfo->meta->subscribe, [ true, 'on' ] ) ) : ?>
                <button type="button" class="btn btn-light-danger fw-bold btn-sm fwp-sweetalert-field" data-config="<?php echo esc_attr( json_encode( [
                  'title' => 'Attantion!',
                  'text' => 'Do you really want to cancel this Subscription?',
                  'icon' => 'error',
                  'confirmButtonText' => 'I Confirm it'
                ] ) ); ?>"><?php echo esc_html_e( 'Cancel', 'we-make-content-crm' ); ?></button>
                <?php endif; ?>
              </div>
            </div>
          </div>

        </div>
        <div class="card">
          <div class="card-header d-flex justify-content-between">
            <div class="header-title">
              <h4 class="card-title"><?php esc_html_e( 'Payment History',   'we-make-content-crm' ); ?></h4>
            </div>
          </div>
          <div class="card-body">

            <?php $payments = apply_filters( 'futurewordpress/project/payment/stripe/payment_history', [], empty( $userInfo->data->user_email ) ?  $userInfo->meta->email : $userInfo->data->user_email ); ?>

            <div class="<?php echo esc_attr( ( count( $payments ) > 0 ) ? 'custom-table-effect' : '' ); ?> table-responsive  border rounded">
              <table class="table mb-0" id="datatable" data-toggle="data-table">
                <thead>
                  <tr class="bg-white">
                    <th><?php esc_html_e( 'Name',   'we-make-content-crm' ); ?></th>
                    <th><?php esc_html_e( 'Email',   'we-make-content-crm' ); ?></th>
                    <th><?php esc_html_e( 'Date',   'we-make-content-crm' ); ?></th>
                    <th><?php esc_html_e( 'Currency',   'we-make-content-crm' ); ?></th>
                    <th><?php esc_html_e( 'Amount',   'we-make-content-crm' ); ?></th>
                    <th><?php esc_html_e( 'Status',   'we-make-content-crm' ); ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php if( count( $payments ) <= 0 ) : ?>
                    <tr>
                      <td colspan="6"><img src="<?php echo esc_url( WEMAKECONTENTCMS_BUILD_URI . '/icons/Card Payment_Monochromatic.svg' ); ?>" alt="<?php esc_attr_e( 'No Payments',   'we-make-content-crm' ); ?>"></td>
                    </tr>
                  <?php else : ?>
                    <?php foreach( $payments as $i => $pay ) :
                      $pay = (array) $pay; ?>
                    <tr>
                      <td><?php echo esc_html( $pay[ 'billing_details' ][ 'name' ] ); ?></td>
                      <td><?php echo esc_html( $pay[ 'billing_details' ][ 'email' ] ); ?></td>
                      <td><?php echo esc_html( wp_date( 'M d, Y', $pay[ 'created' ] ) ); ?></td>
                      <td><?php echo esc_html( $pay[ 'currency' ] ); ?></td>
                      <td><?php echo esc_html( $pay[ 'amount' ] ); ?></td>
                      <td><?php echo esc_html( ( $pay[ 'paid' ] ) ? __( 'Paid', 'domain' ) : __( 'Unpaid', 'domain' ) ); ?></td>
                    </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div id="profile-settings" class="tab-pane fade" role="tabpanel">
        <div class="card">
          <div class="card-header">
            <div class="header-title">
            <h4 class="card-title"><?php esc_html_e( 'Profile Settings',   'we-make-content-crm' ); ?></h4>
            </div>
          </div>
          <div class="card-body">
            <?php include WEMAKECONTENTCMS_DIR_PATH . '/templates/dashboard/cards/content.php'; ?>
          </div>
        </div>
      </div>

    </div>
    </div>
  </div>
  </div>
</main>
<style>
.content.fs-6.d-flex.flex-column-fluid {margin-top: 5rem;display: block;position: relative;}
.payment-page-card {display: flex;justify-content: space-evenly;flex-wrap: wrap;align-content: center;align-items: center;}
</style>



  <?php
endif;
get_footer();
?>
<?php if( false ) : ?>
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
<?php endif; ?>