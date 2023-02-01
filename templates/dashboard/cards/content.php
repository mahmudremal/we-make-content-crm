
<!--begin::Root-->
<div class="d-flex flex-column flex-root">
  <!--begin::Page-->
  <div class="page  d-flex flex-row flex-column-fluid">
    <!--begin::Wrapper-->
    <div class="wrapper  d-flex flex-column flex-row-fluid" id="kt_wrapper">
      <!--begin::Main-->
      <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div class="content fs-6 d-flex flex-column-fluid" id="kt_content">
          <!--begin::Container-->
          <div class=" container-xxl ">
            <!--begin::Profile Account-->
            <div class="card">
              <!--begin::Sidebar-->
              
              <!--end::Sidebar-->

              <!--begin::Form-->
              <form class="form d-flex flex-center" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
              <input type="hidden" name="action" value="futurewordpress/project/action/dashboard">
              <?php wp_nonce_field( 'futurewordpress/project/nonce/dashboard', '_nonce', true, true ); ?>
                <div class="card-body mw-800px py-20">
                  <?php if( $alert = (array) get_transient( 'futurewordpress/project/transiant/dashboard/' . get_current_user_id() ) && isset( $alert[ 'type' ] ) && isset( $alert[ 'message' ] ) ) : ?>
                    <?php delete_transient( 'futurewordpress/project/transiant/dashboard/' . get_current_user_id() ); ?>
                    <!--begin::Alert-->
                    <div class="alert alert-<?php echo esc_attr( $alert[ 'type' ] ); ?> d-flex align-items-center p-5 mb-10">
                      <!--begin::Icon-->
                      <span class="svg-icon svg-icon-2hx svg-icon-<?php echo esc_attr( $alert[ 'type' ] ); ?> me-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="black"></path><path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z" fill="black"></path></svg>
                      </span>
                      <!--end::Icon-->
                      <!--begin::Wrapper-->
                      <div class="d-flex flex-column">
                        <!--begin::Title-->
                        <h4 class="mb-1 text-dark">This is an alert</h4>
                        <!--end::Title-->
                        <!--begin::Content-->
                        <span><?php echo wp_kses_post( $alert[ 'message' ] ); ?></span>
                        <!--end::Content-->
                      </div>
                      <!--end::Wrapper-->
                    </div>
                    <!--end::Alert-->
                  <?php endif; ?>
                  <!--begin::Form row-->
                  <div class="row mb-8">
                    <label class="col-lg-3 col-form-label"><?php esc_html_e( 'Monthly Retainer', 'we-make-content-crm' ); ?></label>
                    <div class="col-lg-9">
                      <div class="input-group input-group-solid mb-5">
                        <span class="input-group-text">$</span>
                        <input type="text" class="form-control" aria-label="<?php esc_attr_e( 'Amount (to the nearest dollar)', 'we-make-content-crm' ); ?>"  value="200" readonly>
                        <!-- <span class="input-group-text"><?php esc_html_e( '.00', 'we-make-content-crm' ); ?></span> -->
                      </div>
                      <div class="form-text"><?php esc_html_e( 'Your Monthly retainer that could be chaged anytime. Once you\'vr changed this amount, will be sync with your stripe account.', 'we-make-content-crm' ); ?> <a href="#" class="fw-bold"><?php esc_html_e( 'Learn more', 'we-make-content-crm' ); ?></a>. </div>
                    </div>
                  </div>
                  <!--end::Form row-->
                  <!--begin::Form row-->
                  <div class="row mb-13">
                    <label class="col-lg-3 col-form-label"><?php esc_html_e( 'Content Calendar', 'we-make-content-crm' ); ?></label>
                    <div class="col-lg-9">
                      <a href="#" target="_blank" class="btn btn-light-success fw-bold btn-sm"><?php esc_html_e( 'Open calendly', 'we-make-content-crm' ); ?></a>
                      <div class="form-text py-2"> <?php esc_html_e( 'See your content calendar on Calendly.', 'we-make-content-crm' ); ?> <a href="#" class="fw-boldk"><?php esc_html_e( 'Learn more', 'we-make-content-crm' ); ?></a>. </div>
                    </div>
                  </div>
                  <!--end::Form row-->

                  <div class="separator separator-dashed my-10"></div>
                  
                  <!--begin::Form row-->
                  <div class="row mb-8">
                    <label class="col-lg-3 col-form-label"><?php esc_html_e( 'Client Raw Video Files Upload', 'we-make-content-crm' ); ?></label>
                    <div class="col-lg-9 row">
                      <div class="col-md-6">
                        <select class="form-select form-select-lg form-select-solid mb-2" data-control="select2" data-placeholder="<?php esc_attr_e( 'Upload for...', 'we-make-content-crm' ); ?>">
                          <?php $months = [ 'Jan', 'Fav', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dev' ]; ?>
                          <?php foreach( $months as $i => $month ) :
                            $dateObj   = DateTime::createFromFormat( '!m', ( $i + 1 ) );
                            $monthName = $dateObj->format('F'); ?>
                              <option value="<?php echo esc_attr( $month ); ?>" <?php echo esc_attr( ( $month == date( 'M' ) ) ? 'selected' : '' ); ?>><?php echo esc_html( $monthName ); ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <select class="form-select form-select-lg form-select-solid mb-2" data-control="select2" data-placeholder="<?php esc_attr_e( 'Upload for...', 'we-make-content-crm' ); ?>">
                          <?php for( $i = apply_filters( 'futurewordpress/project/system/getoption', 'dashboard-yearstart', date( 'Y' ) ); $i <= apply_filters( 'futurewordpress/project/system/getoption', 'dashboard-yearend', ( date( 'Y' ) + 3 ) ); $i++ ) : ?>
                              <option value="<?php echo esc_attr( $i ); ?>" <?php echo esc_attr( ( $i == date( 'Y' ) ) ? 'selected' : '' ); ?>><?php echo esc_html( $i ); ?></option>
                          <?php endfor; ?>
                        </select>
                      </div>
                      <!-- <input class="form-control form-control-solid fwp-flatpickr-field" placeholder="<?php echo esc_attr( wp_date( 'F-Y' ) ); ?>" data-config="<?php echo esc_attr( json_encode( ['enableTime' => false,'dateFormat' => 'F-Y'] ) ); ?>"/> -->

                      <div class="fv-row">
                        <!--begin::Dropzone-->
                        <div class="dropzone fwp-dropzone-field">
                          <!--begin::Message-->
                          <div class="dz-message needsclick">
                            <!--begin::Icon-->
                            <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
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
                  </div>
                  <!--end::Form row-->
                  <!--begin::Form row-->
                  <div class="row mb-13">
                    <label class="col-lg-3 col-form-label"><?php esc_html_e( 'Client Raw Video Archive', 'we-make-content-crm' ); ?></label>
                    <div class="col-lg-9">
                      <button type="button" class="btn btn-light-primary fw-bold btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_1"><?php esc_html_e( 'Open Popup', 'we-make-content-crm' ); ?></button>
                      <div class="form-text py-2"> <?php esc_html_e( 'All of the video files are here. Click on the buton to open all archive list.', 'we-make-content-crm' ); ?> <a href="#" class="fw-boldk"><?php esc_html_e( 'Learn more', 'we-make-content-crm' ); ?></a>. </div>
                    </div>
                  </div>
                  <!--end::Form row-->

                  
            <!--begin::Modal-->
            <div class="modal fade" tabindex="-1" id="kt_modal_1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modal title</h5>                            
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
<span class="svg-icon svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"/>
<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"/>
</svg></span>
<!--end::Svg Icon-->                            </div>
                            <!--end::Close-->
                        </div>

                        <div class="modal-body">
                            <div class="mb-0">
                                <label for="" class="form-label">Select date</label>
                                <input class="form-control form-control-solid" placeholder="Pick date" id="kt_datepicker_10"/>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Modal-->
            
                  <div class="separator separator-dashed my-10"></div>
                  
                  <!--begin::Form row-->
                  <div class="row mb-13">
                    <label class="col-lg-3 col-form-label"><?php esc_html_e( 'Manage your Retainer', 'we-make-content-crm' ); ?></label>
                    <div class="col-lg-9 col-xl-9 align-items-center">
                      <div class="row col-12">
                        <div class="col-sm-3 form-check form-check-custom form-check-solid form-switch">
                          <input class="form-check-input" type="checkbox" checked />
                        </div>
                        <div class="col-sm-9">
                          <button type="button" class="btn btn-light-danger fw-bold btn-sm fwp-sweetalert-field" data-config="<?php echo esc_attr( json_encode( [
                            'title' => 'Attantion!',
                            'text' => 'Do you really want to cancel this Subscription?',
                            'icon' => 'error',
                            'confirmButtonText' => 'I Confirm it'
                          ] ) ); ?>"><?php echo esc_html_e( 'Cancel Subscription', 'domain' ); ?></button>
                        </div>
                      </div>
                      <div class="form-text py-2"> <?php esc_html_e( 'All of the video files are here. Click on the buton to open all archive list.', 'we-make-content-crm' ); ?> <a href="#" class="fw-boldk"><?php esc_html_e( 'Learn more', 'we-make-content-crm' ); ?></a>. </div>
                    </div>
                  </div>
                  <!--end::Form row-->

                  <div class="separator separator-dashed my-10"></div>
                  
                  <!--begin::Form row-->
                  <div class="row mb-8">
                    <label class="col-lg-3 col-form-label"><?php esc_html_e( 'Payment History', 'we-make-content-crm' ); ?></label>
                    <div class="col-lg-9">
                      <button type="button" class="btn btn-light-primary fw-bold btn-sm"><?php esc_html_e( 'Open history', 'we-make-content-crm' ); ?></button>
                      <div class="form-text"> <?php esc_html_e( 'Payment history is synced form your stripe account since you started subscription.', 'we-make-content-crm' ); ?> <a href="#" class="fw-bold"><?php esc_html_e( 'Learn more', 'we-make-content-crm' ); ?></a>. </div>
                    </div>
                  </div>
                  <!--end::Form row-->

                  <div class="separator separator-dashed my-10"></div>
                  
                  <!--begin::Form row-->
                  <div class="row mb-8">
                    <label class="col-lg-3 col-form-label"><?php esc_html_e( 'Change Password', 'we-make-content-crm' ); ?></label>
                    <div class="col-lg-9">
                      <div class="spinner spinner-sm spinner-primary spinner-right">
                        <input class="form-control form-control-lg form-control-solid" id="change-password-field" type="password" value="" placeholder="******************" />
                      </div>
                      <div class="form-text"><?php esc_html_e( 'Change your password from here. This won\'t store on our database. Only encrypted password we store and make sure you\'ve saved your password on a safe place.', 'we-make-content-crm' ); ?> <a href="#" class="fw-bold"><?php esc_html_e( 'Learn more', 'we-make-content-crm' ); ?></a>. </div>
                    </div>
                  </div>
                  <!--end::Form row-->

                  <div class="separator separator-dashed my-10"></div>
                  
                  <!--begin::Form row-->
                  <div class="row mb-8">
                    <label class="col-lg-3 col-form-label"><?php esc_html_e( 'Contact Number', 'we-make-content-crm' ); ?></label>
                    <div class="col-lg-9">
                      <div class="input-group mb-5">
                        <input type="text" class="form-control form-control-lg form-control-solid" placeholder="123 456 789" aria-label="123 456 789" aria-describedby="basic-editopen-contact" oldautocomplete="remove" autocomplete="off" disabled>
                        <span class="input-group-text" id="basic-editopen-contact">
                          <i class="fas fa-pencil-alt fs-4"></i>
                        </span>
                      </div>
                      <div class="form-text"><?php esc_html_e( 'Your conatct number is necessery in case if you need to communicate with you.', 'we-make-content-crm' ); ?> <a href="#" class="fw-bold"><?php esc_html_e( 'Learn more', 'we-make-content-crm' ); ?></a>. </div>
                    </div>
                  </div>
                  <!--end::Form row-->
                  <!--begin::Form row-->
                  <div class="row mb-8">
                    <label class="col-lg-3 col-form-label"><?php esc_html_e( 'Website URL', 'we-make-content-crm' ); ?></label>
                    <div class="col-lg-9">
                      <div class="input-group mb-5 spinner spinner-sm spinner-primary spinner-right">
                        <input class="form-control form-control-lg form-control-solid" type="url" value="<?php echo esc_url( site_url() ); ?>" disabled />
                        <span class="input-group-text" id="basic-editopen-website">
                          <i class="fas fa-pencil-alt fs-4"></i>
                        </span>
                      </div>
                      <div class="form-text"><?php esc_html_e( 'Your conatct number is necessery in case if you need to communicate with you.', 'we-make-content-crm' ); ?> <a href="#" class="fw-bold"><?php esc_html_e( 'Learn more', 'we-make-content-crm' ); ?></a>. </div>
                    </div>
                  </div>
                  <!--end::Form row-->

                  
                  <div class="separator separator-dashed my-10"></div>
                  <!--begin::Form row-->
                  <div class="row mb-8">
                    <label class="col-lg-3 col-form-label"><?php esc_html_e( 'Instagram Handles', 'we-make-content-crm' ); ?></label>
                    <div class="col-lg-9">
                      <div class="input-group input-group-solid mb-5">
                        <span class="input-group-text" id="basic-pre-addons-1"><?php echo esc_html( 'https://www.instagram.com/' ); ?></span>
                        <input type="text" class="form-control" id="basic-url-1" aria-describedby="basic-addon3" value="" placeholder="<?php echo esc_url( '@username' ); ?>" disabled>
                        <span class="input-group-text" id="basic-editopen-instagram">
                          <i class="fas fa-pencil-alt fs-4"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <!--end::Form row-->
                  <!--begin::Form row-->
                  <div class="row mb-8">
                    <label class="col-lg-3 col-form-label"><?php esc_html_e( 'TikTok Handles', 'we-make-content-crm' ); ?></label>
                    <div class="col-lg-9">
                      <div class="input-group input-group-solid mb-5">
                        <span class="input-group-text" id="basic-pre-addons-2"><?php echo esc_html( 'https://www.tiktok.com/' ); ?></span>
                        <input type="text" class="form-control" id="basic-url-2" aria-describedby="basic-addon3" value="" placeholder="<?php echo esc_url( '@username' ); ?>" disabled>
                        <span class="input-group-text" id="basic-editopen-tiktok">
                          <i class="fas fa-pencil-alt fs-4"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <!--end::Form row-->
                  <!--begin::Form row-->
                  <div class="row mb-8">
                    <label class="col-lg-3 col-form-label"><?php esc_html_e( 'YouTube Channel', 'we-make-content-crm' ); ?></label>
                    <div class="col-lg-9">
                      <div class="input-group input-group-solid mb-5">
                        <span class="input-group-text" id="basic-pre-addons-3"><?php echo esc_html( 'https://youtube.com/' ); ?></span>
                        <input type="text" class="form-control" id="basic-url-3" aria-describedby="basic-addon3" value="" placeholder="<?php echo esc_url( '@username' ); ?>" disabled>
                        <span class="input-group-text" id="basic-editopen-youtube">
                          <i class="fas fa-pencil-alt fs-4"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <!--end::Form row-->

                  <!--begin::Form row-->
                  <!-- <div class="row">
                    <label class="col-lg-3 col-form-label"></label>
                    <div class="col-lg-9">
                      <button type="submit" class="btn btn-primary fw-bolder px-6 py-3 me-3"><?php esc_html_e( 'Save Changes', 'we-make-content-crm' ); ?></button>
                      <button type="reset" class="btn btn-color-gray-600 btn-active-light-primary fw-bolder px-6 py-3"><?php esc_html_e( 'Cancel', 'we-make-content-crm' ); ?></button>
                    </div>
                  </div> -->
                  <!--end::Form row-->
                </div>
              </form>
              <!--end::Form-->
            </div>
            <!--end::Profile Account-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::Content-->
      </div>
      <!--end::Main-->
    </div>
    <!--end::Wrapper-->
  </div>
  <!--end::Page-->
</div>
<!--end::Root-->
<?php include WEMAKECONTENTCMS_DIR_PATH . '/templates/dashboard/cards/model.php'; ?>