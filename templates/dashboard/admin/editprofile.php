<?php

if( empty( $args[ 'split' ][2] ) ) {
    wp_die( __( 'User didn\'t identified. Maybe you\'re visiting on an currupted URL.', 'we-make-content-crm' ), __( 'Error Idetified', 'we-make-content-crm' ) );
}
$userStatuses = apply_filters( 'futurewordpress/project/action/statuses', [
    'no-action'             => __( 'No action fetched', 'we-make-content-crm' )
], false );
$contractForms = apply_filters( 'futurewordpress/project/action/contractforms', [
    'no-action'             => __( 'No Contract fetched', 'we-make-content-crm' )
], false );
$userContracts = apply_filters( 'futurewordpress/project/action/contracts', [
    'no-action'             => __( 'No Contract fetched', 'we-make-content-crm' )
], false );
$userDocuments = [
    'basic'                 => __( 'Basic Document',   'we-make-content-crm' )
];
if( class_exists('ESIG_SAD_Admin') ) {
    $userDocuments[ 'sad' ] = __( 'Stand Alone Document',   'we-make-content-crm' );
}
$userCountries = apply_filters( 'futurewordpress/project/database/countries', [
    'no-country'			=> __( 'No Country Found', 'we-make-content-crm' )
], false );
$userInfo = get_user_by( 'id', $args[ 'split' ][2] );
if( ! $userInfo ) {wp_die( __( 'Seems something went wrong. User not found. Please go back', 'we-make-content-crm' ) );}
// $userMeta = get_user_meta( $userInfo->ID, null, true );
// foreach( $userMeta as $meta_key => $meta_value ) {$userMeta[ $meta_key ] = $meta_value[0];}
$userMeta = array_map( function( $a ){ return $a[0]; }, (array) get_user_meta( $userInfo->ID ) );
$userInfo = (object) wp_parse_args( $userInfo, [
    'id'            => '',
    'meta'          => (object) apply_filters( 'futurewordpress/project/usermeta/defaults', (array) $userMeta )
] );
$is_edit_profile = ( ! empty( $args[ 'split' ][2] ) );

// print_r( $userInfo );
?>
<div>
    <form class="row" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
        <input type="hidden" name="action" value="futurewordpress/project/action/editsubscriber">
        <input type="hidden" name="userid" value="<?php echo esc_attr( ( $is_edit_profile ) ? $args[ 'split' ][2] : 'new' ); ?>">
        <?php wp_nonce_field( 'futurewordpress/project/nonce/editsubscriber', '_nonce', true, true ); ?>
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <h4 class="px-3"><?php esc_html_e( 'General', 'we-make-content-crm' ); ?></h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-card">
                        <div class="form-group">
                            <div class="profile-img-edit position-relative">
                                <img src="<?php echo esc_url( get_avatar_url( $userInfo->ID, ['size' => '100'] ) ); ?>" alt="profile-pic" class="theme-color-default-img profile-pic rounded avatar-100" loading="lazy" id="profile-image-preview" data-default="<?php echo esc_url( get_avatar_url( $userInfo->ID, ['size' => '100'] ) ); ?>">
                                <div class="upload-icone bg-primary">
                                    <svg class="upload-button icon-14" width="14" height="14" viewBox="0 0 24 24">
                                    <path fill="#ffffff" d="M14.06,9L15,9.94L5.92,19H5V18.08L14.06,9M17.66,3C17.41,3 17.15,3.1 16.96,3.29L15.13,5.12L18.88,8.87L20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18.17,3.09 17.92,3 17.66,3M14.06,6.19L3,17.25V21H6.75L17.81,9.94L14.06,6.19Z" />
                                    </svg>
                                    <input type="file" class="form-control file-upload profile-image-upload" name="profile-image" accept="image/*" data-preview="#profile-image-preview" data-lead="<?php echo esc_attr( $userInfo->ID ); ?>">
                                </div>
                            </div>
                            <div class="img-extension mt-3">
                                <div class="d-inline-block align-items-center">
                                    <span><?php echo wp_kses_post( sprintf( __( '%s are Recommend', 'we-make-content-crm' ), '</span><a href="javascript:void(0);">.jpg</a><a href="javascript:void(0);">.png</a><a href="javascript:void(0);">.jpeg</a><span>' ) ); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><?php esc_html_e( 'Status:', 'we-make-content-crm' ); ?></label>
                            <select name="userinfo[status]" class="selectpicker form-control" data-style="py-0">
                                <option><?php esc_html_e( 'Select a Status', 'we-make-content-crm' ); ?></option>
                                <?php foreach( $userStatuses as $status_key => $status_text ) : ?>
                                    <option value="<?php echo esc_attr( $status_key ); ?>" <?php echo esc_attr( ( $status_key == $userInfo->meta->status ) ? 'selected' : '' ); ?>><?php echo esc_html( $status_text ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="form-check d-block">
                                <input class="form-check-input" type="checkbox" name="userinfo[enable_subscription]" value="on" id="user-status-toggle" <?php echo esc_attr( in_array( $userInfo->meta->enable_subscription, [ true, 'on' ] ) ? 'checked' : '' ); ?>>
                                <label class="form-check-label" for="user-status-toggle">
                                    Enable Subscription
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="monthly_retainer"><?php esc_html_e( 'Monthly Retainer:', 'we-make-content-crm' ); ?></label>
                            <input type="text" class="form-control" id="monthly_retainer" name="userinfo[monthly_retainer]" value="<?php echo esc_attr( $userInfo->meta->monthly_retainer ); ?>" placeholder="$2000" data-registration="<?php echo site_url( 'lead-registration/source-email/' . bin2hex( $userInfo->ID ) . '/' ); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="content_calendar"><?php esc_html_e( 'Content Calendar:', 'we-make-content-crm' ); ?></label>
                            <input type="text" class="form-control" id="content_calendar" name="userinfo[content_calendar]" value="<?php echo esc_url( $userInfo->meta->content_calendar ); ?>" placeholder="Celandly URI">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="content_library"><?php esc_html_e( 'Content Library:', 'we-make-content-crm' ); ?></label>
                            <input type="text" class="form-control" id="content_library" name="userinfo[content_library]" value="<?php echo esc_url( $userInfo->meta->content_library ); ?>" placeholder="Content Library URI">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-full-width">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                    <h4 class="card-title"><?php esc_html_e( 'Quick Actions:', 'we-make-content-crm' ); ?></h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <a class="btn btn-primary btn-outline mt-2" href="<?php echo esc_url( apply_filters( 'futurewordpress/project/user/dashboardpermalink', $userInfo->ID, $userInfo->data->user_nicename ) ); ?>" role="button" target="_blank"><?php esc_html_e( 'View Frontend', 'we-make-content-crm' ); ?></a>
                        <button type="button" class="btn btn-danger btn-outline mt-2 delete-lead-user" data-id="<?php echo esc_attr( $userInfo->ID ); ?>" data-user-info="<?php echo esc_attr( json_encode( [ 'displayname' => $userInfo->display_name, 'role' => $userInfo->user_role ] ) ); ?>" role="button"><?php esc_html_e( 'Delete Account', 'we-make-content-crm' ); ?></button>
                        <button type="button" class="btn btn-success btn-outline mt-2 lead-send-registration" data-id="<?php echo esc_attr( $userInfo->ID ); ?>" data-user-info="<?php echo esc_attr( $userInfo->display_name ); ?>" role="button"><?php esc_html_e( 'Mail Registration', 'we-make-content-crm' ); ?></button>
                    </div>
                </div>
            </div>
            <div class="card card-full-width">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                    <h4 class="card-title"><?php esc_html_e( 'Contract:', 'we-make-content-crm' ); ?></h4>
                    </div>
                </div>
                <div class="card-body">
                    <!-- <div class="form-group">
                        <label class="form-label"><?php esc_html_e( 'Document type:', 'we-make-content-crm' ); ?></label>
                        <select name="userinfo[document_type]" class="selectpicker form-control" data-style="py-0" id="contract_type">
                            <option><?php esc_html_e( 'Select a type', 'we-make-content-crm' ); ?></option>
                            <?php foreach( $userDocuments as $document_key => $document_text ) : ?>
                                <option value="<?php echo esc_attr( $document_key ); ?>" <?php echo esc_attr( ( $document_key == $userInfo->meta->document_type ) ? 'selected' : '' ); ?>><?php echo esc_html( $document_text ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><?php esc_html_e( 'Contract type:', 'we-make-content-crm' ); ?></label>
                        <select name="userinfo[contract_type]" class="selectpicker form-control" data-style="py-0" id="contract_type">
                            <option><?php esc_html_e( 'Select a type', 'we-make-content-crm' ); ?></option>
                            <?php foreach( $userContracts as $contract_key => $contract_text ) : ?>
                                <option value="<?php echo esc_attr( $contract_key ); ?>" <?php echo esc_attr( ( $contract_key == $userInfo->meta->contract_type ) ? 'selected' : '' ); ?>><?php echo esc_html( $contract_text ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div> -->
                    <div class="form-group">
                        <label class="form-label"><?php esc_html_e( 'Select Registration Link:', 'we-make-content-crm' ); ?></label>
                        <select name="userinfo[contract_type]" class="selectpicker form-control" data-style="py-0" id="contract_type" data-current="<?php echo esc_attr( $userInfo->meta->contract_type ); ?>">
                            <option value=""><?php esc_html_e( 'Select a type', 'we-make-content-crm' ); ?></option>
                            <?php foreach( $contractForms as $contract_key => $contract_text ) : ?>
                                <option value="<?php echo esc_attr( $contract_key ); ?>" <?php echo esc_attr( ( $contract_key == $userInfo->meta->contract_type ) ? 'selected' : '' ); ?>><?php echo esc_html( $contract_text ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- <div class="form-group">
                        <button type="button" class="btn btn-success btn-outline mt-2 lead-send-contract" data-id="<?php echo esc_attr( $userInfo->ID ); ?>" data-user-info="<?php echo esc_attr( $userInfo->display_name ); ?>" role="button"><?php esc_html_e( 'Send Contract', 'we-make-content-crm' ); ?></button>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="col-xl-9 col-lg-8">
            <div class="card card-full-width">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                    <h4 class="card-title"><?php echo esc_html( ( $is_edit_profile ) ? __( 'Edit User Information', 'we-make-content-crm' ) : __( 'New User Information', 'we-make-content-crm' ) ); ?></h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="new-user-info">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="first_name"><?php esc_html_e( 'First name:', 'we-make-content-crm' ); ?></label>
                                <input type="text" class="form-control" id="first_name" name="userinfo[first_name]" value="<?php echo esc_attr( $userInfo->meta->first_name ); ?>" placeholder="<?php esc_attr_e( 'First name', 'we-make-content-crm' ); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="last_name"><?php esc_html_e( 'Last name:', 'we-make-content-crm' ); ?></label>
                                <input type="text" class="form-control" id="last_name" name="userinfo[last_name]" value="<?php echo esc_attr( $userInfo->meta->last_name ); ?>" placeholder="<?php esc_attr_e( 'Last name', 'we-make-content-crm' ); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="company_name"><?php esc_html_e( 'Company Name:', 'we-make-content-crm' ); ?></label>
                                <input type="text" class="form-control" id="company_name" name="userinfo[company_name]" value="<?php echo esc_attr( $userInfo->meta->company_name ); ?>" placeholder="<?php esc_attr_e( 'Company Name', 'we-make-content-crm' ); ?>">
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label"><?php esc_html_e( 'Country:', 'we-make-content-crm' ); ?></label>
                                <select name="userinfo[country]" class="selectpicker form-control" data-style="py-0">
                                <option value=""><?php esc_html_e( 'Select Country', 'we-make-content-crm' ); ?></option>
                                <?php foreach( $userCountries as $country_key => $country_text ) : ?>
                                    <option value="<?php echo esc_attr( $country_key ); ?>" <?php echo esc_attr( ( $country_key == $userInfo->meta->country ) ? 'selected' : '' ); ?>><?php echo esc_html( $country_text ); ?></option>
                                <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="phone"><?php esc_html_e( 'Mobile Number:', 'we-make-content-crm' ); ?></label>
                                <input type="text" class="form-control" id="phone" name="userinfo[phone]" value="<?php echo esc_attr( $userInfo->meta->phone ); ?>" placeholder="<?php esc_attr_e( 'Mobile Number', 'we-make-content-crm' ); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="email"><?php esc_html_e( 'Email', 'we-make-content-crm' ); ?></label>
                                <input type="email" class="form-control" id="email" name="userinfo[email]" value="<?php echo esc_attr( $userInfo->meta->email ); ?>" placeholder="<?php esc_attr_e( 'Email', 'we-make-content-crm' ); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="address1"><?php esc_html_e( 'Street Address 1:', 'we-make-content-crm' ); ?></label>
                                <input type="text" class="form-control" id="address1" name="userinfo[address1]" value="<?php echo esc_attr( $userInfo->meta->address1 ); ?>" placeholder="<?php esc_attr_e( 'Street Address 1', 'we-make-content-crm' ); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="address2"><?php esc_html_e( 'Street Address 2', 'we-make-content-crm' ); ?></label>
                                <input type="text" class="form-control" id="address2" name="userinfo[address2]" value="<?php echo esc_attr( $userInfo->meta->address2 ); ?>" placeholder="<?php esc_attr_e( 'Street Address 2', 'we-make-content-crm' ); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="zip"><?php esc_html_e( 'Zip Code:', 'we-make-content-crm' ); ?></label>
                                <input type="text" class="form-control" id="zip" name="userinfo[zip]" value="<?php echo esc_attr( $userInfo->meta->zip ); ?>" placeholder="<?php esc_attr_e( 'Zip Code', 'we-make-content-crm' ); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="city"><?php esc_html_e( 'Town/City:', 'we-make-content-crm' ); ?></label>
                                <input type="text" class="form-control" id="city" name="userinfo[city]" value="<?php echo esc_attr( $userInfo->meta->city ); ?>" placeholder="<?php esc_attr_e( 'Town/City', 'we-make-content-crm' ); ?>">
                            </div>
                        </div>
                        <hr>
                        <h5 class="mb-3"><?php esc_html_e( 'Security', 'we-make-content-crm' ); ?></h5>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="display_name"><?php esc_html_e( 'User login:', 'we-make-content-crm' ); ?></label>
                                <input type="text" class="form-control" id="display_name" name="userdata[display_name]" value="<?php echo esc_attr( $userInfo->data->display_name ); ?>" placeholder="<?php esc_attr_e( 'User Name', 'we-make-content-crm' ); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="newpassword"><?php esc_html_e( 'Password:', 'we-make-content-crm' ); ?></label>
                                <input type="text" class="form-control" id="newpassword" name="userdata[newpassword]" value="<?php echo esc_attr( $userInfo->data->newpassword ); ?>" placeholder="<?php esc_attr_e( 'Password', 'we-make-content-crm' ); ?>">
                            </div>
                        </div>
                        <!-- <div class="checkbox my-2">
                            <label class="form-label"><input class="form-check-input me-2" type="checkbox" value="" id="flexchexked">Enable Two-Factor-Authentication</label>
                        </div> -->
                        <button type="submit" class="btn btn-primary mt-2"><?php echo esc_html( ( $is_edit_profile ) ? __( 'Update User', 'we-make-content-crm' ) : __( 'Add New User', 'we-make-content-crm' ) ); ?></button>
                    </div>
                </div>
            </div>
            <div class="card card-full-width">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                    <h4 class="card-title"><?php echo esc_html( __( 'Schedule & Meeting', 'we-make-content-crm' ) ); ?></h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="new-user-info">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="next_meeting"><?php esc_html_e( 'Meeting Time:', 'we-make-content-crm' ); ?></label>
                                <input type="text" class="form-control calen dar-picker" id="next_meeting" name="userinfo[next_meeting]" value="<?php echo esc_attr( $userInfo->meta->next_meeting ); ?>" placeholder="<?php esc_attr_e( 'Meeting time', 'we-make-content-crm' ); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="meeting_link"><?php esc_html_e( 'Meeting Link:', 'we-make-content-crm' ); ?></label>
                                <input type="url" class="form-control" id="meeting_link" name="userinfo[meeting_link]" value="<?php echo esc_attr( $userInfo->meta->meeting_link ); ?>" placeholder="<?php esc_attr_e( 'Meeting Link', 'we-make-content-crm' ); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-full-width">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                    <h4 class="card-title"><?php esc_html_e( 'Social Profile:', 'we-make-content-crm' ); ?></h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label" for="tiktok"><?php esc_html_e( 'TikTok Handle:', 'we-make-content-crm' ); ?></label>
                        <input type="text" class="form-control" id="tiktok" name="userinfo[tiktok]" value="<?php echo esc_attr( $userInfo->meta->tiktok ); ?>" placeholder="<?php esc_attr_e( 'TikTok URL', 'we-make-content-crm' ); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="YouTube_url"><?php esc_html_e( 'YouTube Handle:', 'we-make-content-crm' ); ?></label>
                        <input type="text" class="form-control" id="YouTube_url" name="userinfo[YouTube_url]" value="<?php echo esc_attr( $userInfo->meta->YouTube_url ); ?>" placeholder="<?php esc_attr_e( 'YouTube URL', 'we-make-content-crm' ); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="instagram_url"><?php esc_html_e( 'Instagram Handle:', 'we-make-content-crm' ); ?></label>
                        <input type="text" class="form-control" id="instagram_url" name="userinfo[instagram_url]" value="<?php echo esc_attr( $userInfo->meta->instagram_url ); ?>" placeholder="<?php esc_attr_e( 'Instagram Url', 'we-make-content-crm' ); ?>">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label" for="website"><?php esc_html_e( 'Website Url:', 'we-make-content-crm' ); ?></label>
                        <input type="text" class="form-control" id="website" name="userinfo[website]" value="<?php echo esc_attr( $userInfo->meta->website ); ?>" placeholder="<?php esc_attr_e( 'Website URL', 'we-make-content-crm' ); ?>">
                    </div>
                </div>
            </div>
            <div class="card card-full-width">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                    <h4 class="card-title"><?php esc_html_e( 'Contract Services:', 'we-make-content-crm' ); ?></h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label" for="services"><?php esc_html_e( 'Services:', 'we-make-content-crm' ); ?></label>
                        <textarea class="form-control" name="userinfo[services]" id="services" cols="30" rows="10"><?php echo esc_html( $userInfo->meta->services ); ?></textarea>
                    </div>
                </div>
            </div>
            <div class="card card-full-width">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                    <h4 class="card-title"><?php esc_html_e( 'Message to Client:', 'we-make-content-crm' ); ?></h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label" for="message"><?php esc_html_e( 'Leave it blank for disapearing notice.', 'we-make-content-crm' ); ?></label>
                        <textarea class="form-control" name="userinfo[message]" id="message" cols="30" rows="10" data-ckeditor="{}"><?php echo esc_html( $userInfo->meta->message ); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>