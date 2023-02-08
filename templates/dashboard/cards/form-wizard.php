<?php
$userCountries = apply_filters( 'futurewordpress/project/database/countries', [
    'no-country'			=> __( 'No Country Found', 'we-make-content-crm' )
], false );
?>
<div class="card card-full-width">
  <div class="card-header d-flex justify-content-between">
    <div class="header-title">
      <h4 class="card-title">Registration Widzard</h4>
    </div>
  </div>
  <div class="card-body">
    <form id="register-existing-account-wizard" class="mt-3 text-center" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
      <input type="hidden" name="action" value="futurewordpress/project/action/registerexisting" encoding="multipart/form-data">
      <input type="hidden" name="userid" value="<?php echo esc_attr( bin2hex( $userInfo->ID ) ); ?>">
      <?php wp_nonce_field( 'futurewordpress/project/verify/registerexisting', '_nonce', true, true ); ?>
      <ul id="top-tab-list" class="p-0 row list-inline">
        <li class="mb-2 col-lg-3 col-md-6 text-start active" id="account">
          <a href="javascript:void(0);">
            <div class="iq-icon me-3">
              <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.997 15.1746C7.684 15.1746 4 15.8546 4 18.5746C4 21.2956 7.661 21.9996 11.997 21.9996C16.31 21.9996 19.994 21.3206 19.994 18.5996C19.994 15.8786 16.334 15.1746 11.997 15.1746Z" fill="currentColor"></path>
                <path opacity="0.4" d="M11.9971 12.5838C14.9351 12.5838 17.2891 10.2288 17.2891 7.29176C17.2891 4.35476 14.9351 1.99976 11.9971 1.99976C9.06008 1.99976 6.70508 4.35476 6.70508 7.29176C6.70508 10.2288 9.06008 12.5838 11.9971 12.5838Z" fill="currentColor"></path>
              </svg>
            </div>
            <span class="dark-wizard"><?php esc_html_e( 'Account', 'we-make-content-crm' ); ?></span>
          </a>
        </li>
        <li id="social" class="mb-2 col-lg-3 col-md-6 text-start">
          <a href="javascript:void(0);">
            <div class="iq-icon me-3">
              <svg fill="none" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M16.1583 8.23285C16.1583 10.5825 14.2851 12.4666 11.949 12.4666C9.61292 12.4666 7.73974 10.5825 7.73974 8.23285C7.73974 5.88227 9.61292 4 11.949 4C14.2851 4 16.1583 5.88227 16.1583 8.23285ZM11.949 20C8.51785 20 5.58809 19.456 5.58809 17.2802C5.58809 15.1034 8.49904 14.5396 11.949 14.5396C15.3802 14.5396 18.31 15.0836 18.31 17.2604C18.31 19.4362 15.399 20 11.949 20ZM17.9571 8.30922C17.9571 9.50703 17.5998 10.6229 16.973 11.5505C16.9086 11.646 16.9659 11.7748 17.0796 11.7946C17.2363 11.8216 17.3984 11.8369 17.5631 11.8414C19.2062 11.8846 20.6809 10.821 21.0883 9.21974C21.6918 6.84123 19.9198 4.7059 17.6634 4.7059C17.4181 4.7059 17.1835 4.73201 16.9551 4.77884C16.9238 4.78605 16.8907 4.80046 16.8728 4.82838C16.8513 4.8626 16.8674 4.90853 16.8889 4.93825C17.5667 5.8938 17.9571 7.05918 17.9571 8.30922ZM20.6782 13.5126C21.7823 13.7296 22.5084 14.1727 22.8093 14.8166C23.0636 15.3453 23.0636 15.9586 22.8093 16.4864C22.349 17.4851 20.8654 17.8058 20.2887 17.8886C20.1696 17.9066 20.0738 17.8031 20.0864 17.6833C20.3809 14.9157 18.0377 13.6035 17.4315 13.3018C17.4055 13.2883 17.4002 13.2676 17.4028 13.255C17.4046 13.246 17.4154 13.2316 17.4351 13.2289C18.7468 13.2046 20.1571 13.3847 20.6782 13.5126ZM6.43711 11.8413C6.60186 11.8368 6.76304 11.8224 6.92063 11.7945C7.03434 11.7747 7.09165 11.6459 7.02718 11.5504C6.4004 10.6228 6.04313 9.50694 6.04313 8.30913C6.04313 7.05909 6.43353 5.89371 7.11135 4.93816C7.13284 4.90844 7.14806 4.86251 7.12746 4.82829C7.10956 4.80127 7.07553 4.78596 7.04509 4.77875C6.81586 4.73192 6.58127 4.70581 6.33593 4.70581C4.07951 4.70581 2.30751 6.84114 2.91191 9.21965C3.31932 10.8209 4.79405 11.8845 6.43711 11.8413ZM6.59694 13.2545C6.59962 13.268 6.59425 13.2878 6.56918 13.3022C5.9621 13.6039 3.61883 14.9161 3.91342 17.6827C3.92595 17.8034 3.83104 17.9061 3.71195 17.889C3.13531 17.8061 1.65163 17.4855 1.19139 16.4867C0.936203 15.9581 0.936203 15.3457 1.19139 14.817C1.49225 14.1731 2.21752 13.73 3.32156 13.512C3.84358 13.385 5.25294 13.2049 6.5656 13.2292C6.5853 13.2319 6.59515 13.2464 6.59694 13.2545Z" fill="currentColor" />
              </svg>
            </div>
            <span class="dark-wizard"><?php esc_html_e( 'Social', 'we-make-content-crm' ); ?></span>
          </a>
        </li>
        <li id="payment" class="mb-2 col-lg-3 col-md-6 text-start">
          <a href="javascript:void(0);">
            <div class="iq-icon me-3">
              <svg fill="none" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.7689 8.3818H22C22 4.98459 19.9644 3 16.5156 3H7.48444C4.03556 3 2 4.98459 2 8.33847V15.6615C2 19.0154 4.03556 21 7.48444 21H16.5156C19.9644 21 22 19.0154 22 15.6615V15.3495H17.7689C15.8052 15.3495 14.2133 13.7975 14.2133 11.883C14.2133 9.96849 15.8052 8.41647 17.7689 8.41647V8.3818ZM17.7689 9.87241H21.2533C21.6657 9.87241 22 10.1983 22 10.6004V13.131C21.9952 13.5311 21.6637 13.8543 21.2533 13.8589H17.8489C16.8548 13.872 15.9855 13.2084 15.76 12.2643C15.6471 11.6783 15.8056 11.0736 16.1931 10.6122C16.5805 10.1509 17.1573 9.88007 17.7689 9.87241ZM17.92 12.533H18.2489C18.6711 12.533 19.0133 12.1993 19.0133 11.7877C19.0133 11.3761 18.6711 11.0424 18.2489 11.0424H17.92C17.7181 11.0401 17.5236 11.1166 17.38 11.255C17.2364 11.3934 17.1555 11.5821 17.1556 11.779C17.1555 12.1921 17.4964 12.5282 17.92 12.533ZM6.73778 8.3818H12.3822C12.8044 8.3818 13.1467 8.04812 13.1467 7.63649C13.1467 7.22487 12.8044 6.89119 12.3822 6.89119H6.73778C6.31903 6.89116 5.9782 7.2196 5.97333 7.62783C5.97331 8.04087 6.31415 8.37705 6.73778 8.3818Z" fill="currentColor" />
              </svg>
            </div>
            <span class="dark-wizard"><?php esc_html_e( 'Payment', 'we-make-content-crm' ); ?></span>
          </a>
        </li>
        <li id="contract" class="mb-2 col-lg-3 col-md-6 text-start">
          <a href="javascript:void(0);">
            <div class="iq-icon me-3">
              <svg width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.4" d="M16.191 2H7.81C4.77 2 3 3.78 3 6.83V17.16C3 20.26 4.77 22 7.81 22H16.191C19.28 22 21 20.26 21 17.16V6.83C21 3.78 19.28 2 16.191 2Z" fill="currentColor"></path>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.07996 6.6499V6.6599C7.64896 6.6599 7.29996 7.0099 7.29996 7.4399C7.29996 7.8699 7.64896 8.2199 8.07996 8.2199H11.069C11.5 8.2199 11.85 7.8699 11.85 7.4289C11.85 6.9999 11.5 6.6499 11.069 6.6499H8.07996ZM15.92 12.7399H8.07996C7.64896 12.7399 7.29996 12.3899 7.29996 11.9599C7.29996 11.5299 7.64896 11.1789 8.07996 11.1789H15.92C16.35 11.1789 16.7 11.5299 16.7 11.9599C16.7 12.3899 16.35 12.7399 15.92 12.7399ZM15.92 17.3099H8.07996C7.77996 17.3499 7.48996 17.1999 7.32996 16.9499C7.16996 16.6899 7.16996 16.3599 7.32996 16.1099C7.48996 15.8499 7.77996 15.7099 8.07996 15.7399H15.92C16.319 15.7799 16.62 16.1199 16.62 16.5299C16.62 16.9289 16.319 17.2699 15.92 17.3099Z" fill="currentColor"></path>
              </svg>
            </div>
            <span class="dark-wizard"><?php esc_html_e( 'Contract', 'we-make-content-crm' ); ?></span>
          </a>
        </li>
      </ul>
      <!-- fieldsets -->
      <fieldset class="active">
        <div class="form-card text-start">
          <div class="row">
            <div class="col-7">
              <h3 class="mb-4"><?php esc_html_e( 'Account & Personal Information:', 'we-make-content-crm' ); ?></h3>
            </div>
            <div class="col-5">
              <h2 class="steps mb-4"><?php esc_html_e( 'Step 1 - 4', 'we-make-content-crm' ); ?></h2>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php esc_html_e( 'Email:', 'we-make-content-crm' ); ?> *</label>
                <input type="email" class="form-control" name="email" value="<?php echo esc_attr( empty( $userInfo->data->user_email ) ? $userInfo->meta->email : $userInfo->data->user_email ); ?>" disabled>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php esc_html_e( 'First Name:', 'we-make-content-crm' ); ?> *</label>
                <input type="text" class="form-control" name="metadata[first_name]" placeholder="" value="<?php echo esc_attr( $userInfo->meta->first_name ); ?>" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php esc_html_e( 'Last Name:', 'we-make-content-crm' ); ?> *</label>
                <input type="text" class="form-control" name="metadata[last_name]" placeholder="" value="<?php echo esc_attr( $userInfo->meta->last_name ); ?>" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php esc_html_e( 'Password:', 'we-make-content-crm' ); ?> *</label>
                <input type="text" class="form-control" id="password-field-1" name="password[given]" placeholder="" required>
              </div>
            </div>
            <!-- <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php esc_html_e( 'Display Name:', 'we-make-content-crm' ); ?>: *</label>
                <input type="text" class="form-control" name="userdata[display_name]" placeholder="" value="<?php echo esc_attr( $userInfo->data->display_name ); ?>">
              </div>
            </div> -->
            <!-- <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php esc_html_e( 'Confirm Password:', 'we-make-content-crm' ); ?> *</label>
                <input type="password" class="form-control" id="password-field-2" name="password[confirm]" placeholder="" required>
              </div>
            </div> -->
          </div>
        </div>
        <button type="button" name="next" class="btn btn-primary next action-button float-end" value="Next"><?php esc_html_e( 'Next', 'we-make-content-crm' ); ?></button>
      </fieldset>
      <fieldset class="">
        <div class="form-card text-start">
          <div class="row">
            <div class="col-7">
              <h3 class="mb-4"><?php esc_html_e( 'Socials & Connection:', 'we-make-content-crm' ); ?></h3>
            </div>
            <div class="col-5">
              <h2 class="steps mb-4"><?php esc_html_e( 'Step 2 - 4', 'we-make-content-crm' ); ?></h2>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label class="form-label"><?php esc_html_e( 'TikTok Handle:', 'we-make-content-crm' ); ?></label>
              <div class="form-group input-group">
                  <span class="input-group-text" id="tiktok-handle">@</span>
                  <input type="text" class="form-control" aria-label="<?php esc_attr_e( 'TikTok Handle:', 'we-make-content-crm' ); ?>" aria-describedby="tiktok-handle" name="metadata[tiktok]" placeholder="" value="<?php echo esc_attr( $userInfo->meta->tiktok ); ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php esc_html_e( 'YouTube Handle:', 'we-make-content-crm' ); ?></label>
                <div class="form-group input-group">
                  <span class="input-group-text" id="youtube-handle">@</span>
                  <input type="text" class="form-control" aria-label="<?php esc_attr_e( 'YouTube Handle:', 'we-make-content-crm' ); ?>" aria-describedby="youtube-handle" name="metadata[YouTube_url]" placeholder="" value="<?php echo esc_attr( $userInfo->meta->YouTube_url ); ?>">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php esc_html_e( 'Instagram Handle:', 'we-make-content-crm' ); ?></label>
                <div class="form-group input-group">
                  <span class="input-group-text" id="instagram-handle">@</span>
                  <input type="text" class="form-control" aria-label="<?php esc_attr_e( 'YouTube Handle:', 'we-make-content-crm' ); ?>" aria-describedby="instagram-handle" name="metadata[instagram_url]" placeholder="" value="<?php echo esc_attr( $userInfo->meta->instagram_url ); ?>">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php esc_html_e( 'Website Url:', 'we-make-content-crm' ); ?></label>
                <input type="text" class="form-control" name="metadata[website]" placeholder="" value="<?php echo esc_attr( $userInfo->meta->website ); ?>">
              </div>
            </div>



            <!-- <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php esc_html_e( 'Contact No.:', 'we-make-content-crm' ); ?> *</label>
                <input type="text" class="form-control" name="metadata[phone]" placeholder="" value="<?php echo esc_attr( $userInfo->meta->phone ); ?>" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php esc_html_e( 'Alternate Contact No.:', 'we-make-content-crm' ); ?></label>
                <input type="text" class="form-control" name="metadata[phone1]" value="<?php echo esc_attr( $userInfo->meta->phone1 ); ?>" placeholder="">
              </div>
            </div> -->
            <div class="col-md-12">
              <div class="form-group">
                <label class="form-label"><?php esc_html_e( 'Address:', 'we-make-content-crm' ); ?></label>
                <input type="text" class="form-control" name="metadata[address1]" placeholder="" value="<?php echo esc_attr( $userInfo->meta->address1 ); ?>">
              </div>
            </div>
            <!-- <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php esc_html_e( 'Alternate Address:', 'we-make-content-crm' ); ?></label>
                <input type="text" class="form-control" name="metadata[address2]" value="<?php echo esc_attr( $userInfo->meta->address2 ); ?>" placeholder="">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label" for="choices-single-default"><?php esc_html_e( 'Country:', 'we-make-content-crm' ); ?></label>
                <select class="form-select" data-trigger name="metadata[country]">
                  <option value=""><?php esc_html_e( 'Select country', 'we-make-content-crm' ); ?></option>
                  <?php foreach( $userCountries as $country_key => $country_text ) : ?>
                    <option value="<?php echo esc_attr( $country_key ); ?>" <?php echo esc_attr( ( $country_key == $userInfo->meta->country ) ? 'selected' : '' ); ?>><?php echo esc_html( $country_text ); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php esc_html_e( 'City:', 'we-make-content-crm' ); ?></label>
                <input type="text" class="form-control" name="metadata[city]" placeholder="" value="<?php echo esc_attr( $userInfo->meta->city ); ?>">
              </div>
            </div> -->
            
          </div>
        </div>
        <button type="button" name="next" class="btn btn-primary next action-button float-end" value="Next"><?php esc_html_e( 'Next', 'we-make-content-crm' ); ?></button>
        <button type="button" name="previous" class="btn btn-dark previous action-button-previous float-end me-1" value="Previous"><?php esc_html_e( 'Previous', 'we-make-content-crm' ); ?></button>
      </fieldset>
      <fieldset class="">
        <div class="form-card text-start">
          <div class="row">
            <div class="col-7">
              <h3 class="mb-4"><?php esc_html_e( 'Payment:', 'we-make-content-crm' ); ?></h3>
              <!-- <h3 class="mb-4"><?php esc_html_e( 'Image Upload:', 'we-make-content-crm' ); ?></h3> -->
            </div>
            <div class="col-5">
              <h2 class="steps mb-4"><?php esc_html_e( 'Step 3 - 4', 'we-make-content-crm' ); ?></h2>
            </div>
          </div>
          <div class="row">
            
            <div class="row d-flex justify-content-center">
              <div class="col-md-8 col-lg-6 col-xl-4">
                <div class="card rounded-3">
                  <div class="card-body mx-1 my-2">
                    <div class="d-flex align-items-center">
                      <div>
                        <i class="fab fa-cc-visa fa-4x text-black pe-3"></i>
                      </div>
                      <div>
                        <p class="d-flex flex-column mb-0">
                          <b><?php esc_html_e( 'Pay to proceed on dashboard.', 'we-make-content-crm' ); ?></b>
                        </p>
                      </div>
                    </div>

                    <div class="pt-3">

                      <div class="d-flex flex-row pb-3">
                        <div class="rounded border border-primary border-2 d-flex w-100 p-3 align-items-center"
                          style="background-color: rgba(18, 101, 241, 0.07);">
                          <div class="d-flex align-items-center pe-3">
                            <input class="form-check-input" type="radio" name="radioNoLabelX" id="radioNoLabel11"
                              value="" aria-label="..." checked />
                          </div>
                          <div class="d-flex flex-column">
                            <p class="mb-1 small text-primary"><?php esc_html_e( 'Monthly Retainer', 'we-make-content-crm' ); ?></p>
                            <h6 class="mb-0 text-primary">$<?php echo esc_html( $userInfo->meta->monthly_retainer ); ?></h6>
                          </div>
                        </div>
                      </div>

                    </div>

                    <div class="d-flex justify-content-between align-items-center pb-1">
                      <!-- <a href="<?php echo esc_url( site_url( 'pay_retainer/' . $userInfo->ID . '/' ) ); ?>" class="btn btn-primary btn-lg pay_retainer-amount" target="_blank"><?php esc_html_e( 'Pay amount', 'domain' ); ?></a> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="col-sm-6">
              <div class="form-group">
                <label class="form-label"><?php esc_html_e( 'Upload Your Photo:', 'we-make-content-crm' ); ?></label>
                <input type="file" class="form-control" name="profile-image" accept="image/*">
              </div>
              <div class="form-group profile-image-preview mb-3">
                <img id="preview" src="#" alt="Preview" />
              </div>
            </div> -->
          </div>
        </div>
        <!-- <a type="button" href="<?php echo esc_url( site_url( 'pay_retainer/' . $userInfo->ID . '/' ) ); ?>" target="_blank" name="pay-amount" class="btn btn-primary next action-button float-end" value="Submit"><?php esc_html_e( 'Pay Amount', 'we-make-content-crm' ); ?></a> -->
        <button type="submit" name="submit" class="btn btn-primary next action-button float-end" value="Submit"><?php esc_html_e( 'Submit', 'we-make-content-crm' ); ?></button>
        <button type="button" name="previous" class="btn btn-dark previous action-button-previous float-end me-1" value="Previous"><?php esc_html_e( 'Previous', 'we-make-content-crm' ); ?></button>
      </fieldset>
      <fieldset class="">
        <div class="form-card">
          <div class="row">
            <div class="col-7">
              <h3 class="mb-4 text-left"><?php esc_html_e( 'Finish:', 'we-make-content-crm' ); ?></h3>
            </div>
            <div class="col-5">
              <h2 class="steps mb-4"><?php esc_html_e( 'Step 4 - 4', 'we-make-content-crm' ); ?></h2>
            </div>
          </div>
          <br>
          <br>
          <h2 class="text-center text-success">
            <strong><?php esc_html_e( 'SUCCESS !', 'we-make-content-crm' ); ?></strong>
          </h2>
          <br>
          <div class="row justify-content-center">
            <div class="col-3">
              <img src="<?php echo esc_url( WEMAKECONTENTCMS_BUILD_URI . '/img/img-success.png' ); ?>" class="img-fluid" alt="fit-image" loading="lazy">
            </div>
          </div>
          <br>
          <br>
          <div class="row justify-content-center">
            <div class="text-center col-7">
              <h5 class="text-center purple-text"><?php esc_html_e( 'You Have Successfully Signed Up', 'we-make-content-crm' ); ?></h5>
            </div>
          </div>
        </div>
      </fieldset>
    </form>

  </div>
</div>