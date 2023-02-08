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

if( is_wp_error( $userInfo ) || $errorHappens ) :
  http_response_code( 404 );
  status_header( 404, 'Page not found' );
  add_filter( 'pre_get_document_title', function( $title ) {global $errorHappens;return $errorHappens;}, 10, 1 );
  wp_die( $errorHappens, __( 'Error Happens', 'we-make-content-crm' ) );
else :
  $userMeta = array_map( function( $a ){ return $a[0]; }, (array) get_user_meta( $userInfo->ID ) );
  $userInfo = (object) wp_parse_args( $userInfo, [
    'id'            => '',
    'meta'          => (object) apply_filters( 'futurewordpress/project/usermeta/defaults', (array) $userMeta )
  ] );
  add_filter( 'pre_get_document_title', function( $title ) {
    $title = apply_filters( 'futurewordpress/project/system/getoption', 'dashboard-title', __( 'User Dashbord', 'we-make-content-crm' ) );
    return $title;
  }, 10, 1 );
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
                        <img src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/avatars/01.png" alt="User-Profile" class="theme-color-default-img img-fluid rounded-pill avatar-100" loading="lazy">
                      </div>
                      <div class="d-flex flex-wrap align-items-center mb-3 mb-sm-0">
                        <h4 class="me-2 h4">Austin Robertson</h4>
                        <span> - Web Developer</span>
                      </div>
                    </div>
                    <ul class="d-flex nav nav-pills mb-0 text-center profile-tab nav-slider" data-toggle="slider-tab" id="profile-pills-tab" role="tablist">
                      <li class="nav-item" role="presentation">
                        <a class="nav-link active show" data-bs-toggle="tab" href="#profile-feed" role="tab" aria-selected="true">Feed</a>
                      </li>
                      <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#profile-activity" role="tab" aria-selected="false" tabindex="-1">Activity</a>
                      </li>
                      <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#profile-friends" role="tab" aria-selected="false" tabindex="-1">Friends</a>
                      </li>
                      <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#profile-profile" role="tab" aria-selected="false" tabindex="-1">Profile</a>
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
                <h4 class="card-title">Twitter Feeds</h4>
            </div>
          </div>
          <div class="card-body">
            <div class="twit-feed">
                <div class="d-flex align-items-center mb-2">
                  <img class="rounded-pill img-fluid avatar-50 me-3 p-1 bg-soft-danger ps-2" src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/avatars/03.png" alt="" loading="lazy">
                  <div class="media-support-info">
                      <h6 class="mb-0">Wade Warren</h6>
                      <p class="mb-0">@wade007
                        <span class="text-primary">
                            <svg width="15" viewBox="0 0 24 24">
                              <path fill="currentColor" d="M10,17L5,12L6.41,10.58L10,14.17L17.59,6.58L19,8M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"></path>
                            </svg>
                        </span>
                      </p>
                  </div>
                </div>
                <div class="media-support-body">
                  <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
                  <div class="d-flex flex-wrap">
                      <a href="#" class="twit-meta-tag pe-2">#Html</a>
                      <a href="#" class="twit-meta-tag pe-2">#Bootstrap</a>
                  </div>
                  <div class="twit-date">07 Jan 2021</div>
                </div>
            </div>
            <hr class="my-4">
            <div class="twit-feed">
                <div class="d-flex align-items-center mb-2">
                  <img class="rounded-pill img-fluid avatar-50 me-3 p-1 bg-soft-primary" src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/avatars/04.png" alt="" loading="lazy">
                  <div class="media-support-info">
                      <h6 class="mb-0">Jane Cooper</h6>
                      <p class="mb-0">@jane59
                        <span class="text-primary">
                            <svg width="15" viewBox="0 0 24 24">
                              <path fill="currentColor" d="M10,17L5,12L6.41,10.58L10,14.17L17.59,6.58L19,8M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"></path>
                            </svg>
                        </span>
                      </p>
                  </div>
                </div>
                <div class="media-support-body">
                  <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
                  <div class="d-flex flex-wrap">
                      <a href="#" class="twit-meta-tag pe-2">#Js</a>
                      <a href="#" class="twit-meta-tag pe-2">#Bootstrap</a>
                  </div>
                  <div class="twit-date">18 Feb 2021</div>
                </div>
            </div>
            <hr class="my-4">
            <div class="twit-feed">
                <div class="d-flex align-items-center mb-2">
                      <img class="rounded-pill img-fluid avatar-50 me-3 p-1 bg-soft-warning pt-2" src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/avatars/02.png" alt="" loading="lazy">
                  <div class="mt-2">
                      <h6 class="mb-0">Guy Hawkins</h6>
                      <p class="mb-0">@hawk_g
                        <span class="text-primary">
                            <svg width="15" viewBox="0 0 24 24">
                              <path fill="currentColor" d="M10,17L5,12L6.41,10.58L10,14.17L17.59,6.58L19,8M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"></path>
                            </svg>
                        </span>
                      </p>
                  </div>
                </div>
                <div class="media-support-body">
                  <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
                  <div class="d-flex flex-wrap">
                      <a href="#" class="twit-meta-tag pe-2">#Html</a>
                      <a href="#" class="twit-meta-tag pe-2">#CSS</a>
                  </div>
                  <div class="twit-date">15 Mar 2021</div>
                </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-9">
          <div class="profile-content tab-content iq-tab-fade-up">
            <div id="profile-feed" class="tab-pane fade active show" role="tabpanel">
              <div class="card">
                  <div class="card-header d-flex align-items-center justify-content-between pb-4">
                    <div class="header-title">
                        <div class="d-flex flex-wrap">
                          <div class="media-support-user-img me-3">
                              <img class="rounded-pill img-fluid avatar-60 bg-soft-danger p-1 ps-2" src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/avatars/02.png" alt="" loading="lazy">
                          </div>
                          <div class="media-support-info mt-2">
                              <h5 class="mb-0">Anna Sthesia</h5>
                              <p class="mb-0 text-primary">colleages</p>
                          </div>
                        </div>
                    </div>                        
                    <div class="dropdown">
                        <span class="dropdown-toggle" id="dropdownMenuButton7" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                        29 mins 
                        </span>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton7">
                          <a class="dropdown-item " href="javascript:void(0);">Action</a>
                          <a class="dropdown-item " href="javascript:void(0);">Another action</a>
                          <a class="dropdown-item " href="javascript:void(0);">Something else here</a>
                        </div>
                    </div>
                  </div>
                  <div class="card-body p-0">
                    <div class="user-post">
                        <a href="javascript:void(0);"><img src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/pages/03-page.jpg" alt="post-image" class="img-fluid" loading="lazy"></a>
                    </div>
                  </div>
              </div>
            </div>
            <div id="profile-activity" class="tab-pane fade" role="tabpanel">
              <div class="card">
                  <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Activity</h4>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="iq-timeline0 m-0 d-flex align-items-center justify-content-between position-relative">
                        <ul class="list-inline p-0 m-0">
                          <li>
                              <div class="timeline-dots timeline-dot1 border-primary text-primary"></div>
                              <h6 class="float-left mb-1">Client Login</h6>
                              <small class="float-right mt-1">24 November 2019</small>
                              <div class="d-inline-block w-100">
                                <p>Bonbon macaroon jelly beans gummi bears jelly lollipop apple</p>
                              </div>
                          </li>
                          <li>
                              <div class="timeline-dots timeline-dot1 border-success text-success"></div>
                              <h6 class="float-left mb-1">Scheduled Maintenance</h6>
                              <small class="float-right mt-1">23 November 2019</small>
                              <div class="d-inline-block w-100">
                                <p>Bonbon macaroon jelly beans gummi bears jelly lollipop apple</p>
                              </div>
                          </li>
                          <li>
                              <div class="timeline-dots timeline-dot1 border-danger text-danger"></div>
                              <h6 class="float-left mb-1">Dev Meetup</h6>
                              <small class="float-right mt-1">20 November 2019</small>
                              <div class="d-inline-block w-100">
                                <p>Bonbon macaroon jelly beans <a href="#">gummi bears</a>gummi bears jelly lollipop apple</p>
                                <div class="iq-media-group iq-media-group-1">
                                    <a href="#" class="iq-media-1">
                                      <div class="icon iq-icon-box-3 rounded-pill">SP</div>
                                    </a>
                                    <a href="#" class="iq-media-1">
                                      <div class="icon iq-icon-box-3 rounded-pill">PP</div>
                                    </a>
                                    <a href="#" class="iq-media-1">
                                      <div class="icon iq-icon-box-3 rounded-pill">MM</div>
                                    </a>
                                </div>
                              </div>
                          </li>
                          <li>
                              <div class="timeline-dots timeline-dot1 border-primary text-primary"></div>
                              <h6 class="float-left mb-1">Client Call</h6>
                              <small class="float-right mt-1">19 November 2019</small>
                              <div class="d-inline-block w-100">
                                <p>Bonbon macaroon jelly beans gummi bears jelly lollipop apple</p>
                              </div>
                          </li>
                          <li>
                              <div class="timeline-dots timeline-dot1 border-warning text-warning"></div>
                              <h6 class="float-left mb-1">Mega event</h6>
                              <small class="float-right mt-1">15 November 2019</small>
                              <div class="d-inline-block w-100">
                                <p>Bonbon macaroon jelly beans gummi bears jelly lollipop apple</p>
                              </div>
                          </li>
                        </ul>
                    </div>
                  </div>
              </div>
            </div>
            <div id="profile-friends" class="tab-pane fade" role="tabpanel">
              <div class="card">
                  <div class="card-header">
                    <div class="header-title">
                        <h4 class="card-title">Friends</h4>
                    </div>
                  </div>
                  <div class="card-body">
                    <ul class="list-inline m-0 p-0">
                        <li class="d-flex mb-4 align-items-center">
                          <img src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/avatars/01.png" alt="story-img" class="rounded-pill avatar-40" loading="lazy">
                          <div class="ms-3 flex-grow-1">
                              <h6>Paul Molive</h6>
                              <p class="mb-0">Web Designer</p>
                          </div>
                          <div class="dropdown">
                              <span class="dropdown-toggle" id="dropdownMenuButton9" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                              </span>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton9">
                                <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                <a class="dropdown-item " href="javascript:void(0);">block</a>
                              </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                          <img src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/avatars/05.png" alt="story-img" class="rounded-pill avatar-40" loading="lazy">
                          <div class="ms-3 flex-grow-1">
                              <h6>Paul Molive</h6>
                              <p class="mb-0">trainee</p>
                          </div>
                          <div class="dropdown">
                              <span class="dropdown-toggle" id="dropdownMenuButton10" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                              </span>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton10">
                                <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                <a class="dropdown-item " href="javascript:void(0);">block</a>
                              </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                          <img src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/avatars/02.png" alt="story-img" class="rounded-pill avatar-40" loading="lazy">
                          <div class="ms-3 flex-grow-1">
                              <h6>Anna Mull</h6>
                              <p class="mb-0">Web Developer</p>
                          </div>
                          <div class="dropdown">
                              <span class="dropdown-toggle" id="dropdownMenuButton11" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                              </span>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton11">
                                <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                <a class="dropdown-item " href="javascript:void(0);">block</a>
                              </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                          <img src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/avatars/03.png" alt="story-img" class="rounded-pill avatar-40" loading="lazy">
                          <div class="ms-3 flex-grow-1">
                              <h6>Paige Turner</h6>
                              <p class="mb-0">trainee</p>
                          </div>
                          <div class="dropdown">
                              <span class="dropdown-toggle" id="dropdownMenuButton12" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                              </span>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton12">
                                <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                <a class="dropdown-item " href="javascript:void(0);">block</a>
                              </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                          <img src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/avatars/04.png" alt="story-img" class="rounded-pill avatar-40" loading="lazy">
                          <div class="ms-3 flex-grow-1">
                              <h6>Barb Ackue</h6>
                              <p class="mb-0">Web Designer</p>
                          </div>
                          <div class="dropdown">
                              <span class="dropdown-toggle" id="dropdownMenuButton13" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                              </span>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton13">
                                <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                <a class="dropdown-item " href="javascript:void(0);">block</a>
                              </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                          <img src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/avatars/05.png" alt="story-img" class="rounded-pill avatar-40" loading="lazy">
                          <div class="ms-3 flex-grow-1">
                              <h6>Greta Life</h6>
                              <p class="mb-0">Tester</p>
                          </div>
                          <div class="dropdown">
                              <span class="dropdown-toggle" id="dropdownMenuButton14" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                              </span>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton14">
                                <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                <a class="dropdown-item " href="javascript:void(0);">block</a>
                              </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                          <img src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/avatars/03.png" alt="story-img" class="rounded-pill avatar-40" loading="lazy">                              <div class="ms-3 flex-grow-1">
                              <h6>Ira Membrit</h6>
                              <p class="mb-0">Android Developer</p>
                          </div>
                          <div class="dropdown">
                              <span class="dropdown-toggle" id="dropdownMenuButton15" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                              </span>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton15">
                                <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                <a class="dropdown-item " href="javascript:void(0);">block</a>
                              </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                          <img src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/avatars/02.png" alt="story-img" class="rounded-pill avatar-40" loading="lazy">
                          <div class="ms-3 flex-grow-1">
                              <h6>Pete Sariya</h6>
                              <p class="mb-0">Web Designer</p>
                          </div>
                          <div class="dropdown">
                              <span class="dropdown-toggle" id="dropdownMenuButton16" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                              </span>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton16">
                                <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                <a class="dropdown-item " href="javascript:void(0);">block</a>
                              </div>
                          </div>
                        </li>
                    </ul>
                  </div>
              </div>
            </div>
            <div id="profile-profile" class="tab-pane fade" role="tabpanel">
              <div class="card">
                  <div class="card-header">
                    <div class="header-title">
                        <h4 class="card-title">Profile</h4>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="text-center">
                        <div>
                          <img src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/avatars/01.png" alt="profile-img" class="rounded-pill avatar-130 img-fluid" loading="lazy">
                        </div>
                        <div class="mt-3">
                          <h3 class="d-inline-block">Austin Robertson</h3>
                          <p class="d-inline-block pl-3"> - Web developer</p>
                          <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
                        </div>
                    </div>
                  </div>
              </div>
              <div class="card">
                  <div class="card-header">
                    <div class="header-title">
                        <h4 class="card-title">About User</h4>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="user-bio">
                        <p>Tart I love sugar plum I love oat cake. Sweet roll caramels I love jujubes. Topping cake wafer.</p>
                    </div>
                    <div class="mt-2">
                    <h6 class="mb-1">Joined:</h6>
                    <p>Feb 15, 2021</p>
                    </div>
                    <div class="mt-2">
                    <h6 class="mb-1">Lives:</h6>
                    <p>United States of America</p>
                    </div>
                    <div class="mt-2">
                    <h6 class="mb-1">Email:</h6>
                    <p><a href="#" class="text-body"> austin@gmail.com</a></p>
                    </div>
                    <div class="mt-2">
                    <h6 class="mb-1">Url:</h6>
                    <p><a href="#" class="text-body" target="_blank"> www.bootstrap.com </a></p>
                    </div>
                    <div class="mt-2">
                    <h6 class="mb-1">Contact:</h6>
                    <p><a href="#" class="text-body">(001) 4544 565 456</a></p>
                    </div>
                  </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</main>
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