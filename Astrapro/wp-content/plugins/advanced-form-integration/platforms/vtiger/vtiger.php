<?php

add_filter( 'adfoin_action_providers', 'adfoin_vtiger_actions', 10, 1 );
 
function adfoin_vtiger_actions( $actions ) {

    $actions['vtiger'] = array(
        'title' => __( 'Vtiger CRM', 'advanced-form-integration' ),
        'tasks' => array(
            'add_fields' => __( 'Add Organization, Contact', 'advanced-form-integration' )
        )
    );

    return $actions;
}
 
add_filter( 'adfoin_settings_tabs', 'adfoin_vtiger_settings_tab', 10, 1 );

function adfoin_vtiger_settings_tab( $providers ) {
    $providers['vtiger'] = __( 'Vtiger CRM', 'advanced-form-integration' );

    return $providers;
}
 
add_action( 'adfoin_settings_view', 'adfoin_vtiger_settings_view', 10, 1 );

function adfoin_vtiger_settings_view( $current_tab ) {
    if( $current_tab != 'vtiger' ) {
        return;
    }

    $nonce      = wp_create_nonce( 'adfoin_vtiger_settings' );
    $baseurl    = get_option( 'adfoin_vtiger_baseurl' ) ? get_option( 'adfoin_vtiger_baseurl' ) : '';
    $username   = get_option( 'adfoin_vtiger_username' ) ? get_option( 'adfoin_vtiger_username' ) : '';
    $access_key = get_option( 'adfoin_vtiger_access_key' ) ? get_option( 'adfoin_vtiger_access_key' ) : '';
    ?>

    <form name="vtiger_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
        method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_vtiger_api_token">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
        <tr valign="top">
                <th scope="row"> <?php _e( 'Base URL', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_vtiger_baseurl"
                        value="<?php echo $baseurl; ?>" placeholder="<?php _e( 'Please enter yout crm baseurl', 'advanced-form-integration' ); ?>"
                        class="regular-text"/>
                    <p class="description" id="code-description"><?php _e( 'Login to Vtiger account and copy the main URL, e.g., https://xxxxxx.odxx.vtiger.com', 'advanced-form-integration' ); ?></a></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php _e( 'Username', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_vtiger_username"
                        value="<?php echo $username; ?>" placeholder="<?php _e( 'Please enter username', 'advanced-form-integration' ); ?>"
                        class="regular-text"/>
                    <p class="description" id="code-description"><?php _e( 'Go to Settings > My Preferences > User information. Copy username and access key.', 'advanced-form-integration' ); ?></a></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php _e( 'Access Key', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_vtiger_access_key"
                        value="<?php echo $access_key; ?>" placeholder="<?php _e( 'Please enter access key', 'advanced-form-integration' ); ?>"
                        class="regular-text"/>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}
 
add_action( 'admin_post_adfoin_save_vtiger_api_token', 'adfoin_save_vtiger_api_token', 10, 0 );

function adfoin_save_vtiger_api_token() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_vtiger_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $baseurl    = sanitize_text_field( $_POST['adfoin_vtiger_baseurl'] );
    $username   = sanitize_text_field( $_POST['adfoin_vtiger_username'] );
    $access_key = sanitize_text_field( $_POST['adfoin_vtiger_access_key'] );

    // Save tokens
    update_option( 'adfoin_vtiger_baseurl', $baseurl );
    update_option( 'adfoin_vtiger_username', $username );
    update_option( 'adfoin_vtiger_access_key', $access_key );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=vtiger" );
}
 
add_action( 'adfoin_action_fields', 'adfoin_vtiger_action_fields', 10, 1 );

function adfoin_vtiger_action_fields() {
    ?>
    <script type="text/template" id="vtiger-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_fields'">
                <th scope="row">
                    <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_fields'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Owner', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[owner]" v-model="fielddata.owner" required="required">
                        <option value=""> <?php _e( 'Select Owner...', 'advanced-form-integration' ); ?> </option>
                        <option v-for="(item, index) in fielddata.ownerList" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': ownerLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_fields'">
            <td scope="row-title">
                <label for="tablecell">
                    <?php esc_attr_e( 'Entities', 'advanced-form-integration' ); ?>
                </label>
            </td>
            <td>
                <div class="object_selection" style="display: inline;">
                    <input type="checkbox" id="organization__chosen" value="true" v-model="fielddata.organization__chosen" name="fieldData[organization__chosen]">
                    <label style="margin-right:10px;" for="organization__chosen">Organization</label>
                    <input type="checkbox" id="contact__chosen" value="true" v-model="fielddata.contact__chosen" name="fieldData[contact__chosen]">
                    <label style="margin-right:10px;" for="contact__chosen">Contact</label>
                    <!-- <input type="checkbox" id="action__chosen" value="true" v-model="fielddata.action__chosen" name="fieldData[action__chosen]">
                    <label style="margin-right:10px;" for="action__chosen">Action</label> -->
                    <!-- <input type="checkbox" id="case__chosen" value="true" v-model="fielddata.case__chosen" name="fieldData[case__chosen]">
                    <label style="margin-right:10px;" for="case__chosen">Case</label>
                    <input type="checkbox" id="task__chosen" value="true" v-model="fielddata.task__chosen" name="fieldData[task__chosen]">
                    <label style="margin-right:10px;" for="task__chosen">Task</label> -->
                </div>
                
                <button class="button-secondary" @click.stop.prevent="getFields">Get Fields</button>
                <div class="spinner" v-bind:class="{'is-active': fieldsLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                
            </td>
        </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
        </table>
    </script>
    <?php
}

 /*
 * Vtiger CRM API Request
 */
function adfoin_vtiger_request( $endpoint, $method = 'GET', $data = array(), $record = array() ) {

    $baseurl    = get_option( 'adfoin_vtiger_baseurl' ) ? get_option( 'adfoin_vtiger_baseurl' ) : '';
    $username   = get_option( 'adfoin_vtiger_username' ) ? get_option( 'adfoin_vtiger_username' ) : '';
    $access_key = get_option( 'adfoin_vtiger_access_key' ) ? get_option( 'adfoin_vtiger_access_key' ) : '';
    $url        = $baseurl . '/restapi/v1/vtiger/default/' . $endpoint;

    $args = array(
        'method'  => $method,
        'headers' => array(
            'Content-Type'  => 'application/x-www-form-urlencoded',
            'Accept'        => 'application/json',
            'Authorization' => 'Basic ' . base64_encode( $username . ':' . $access_key )
        ),
    );

    $response = wp_remote_request( $url, $args );

    if ($record) {
        adfoin_add_to_log( $response, $url, $args, $record );
    }

    return $response;
}
 
add_action( 'wp_ajax_adfoin_get_vtiger_owner_list', 'adfoin_get_vtiger_owner_list', 10, 0 );

/*
* Get Vtiger Owner list
*/
function adfoin_get_vtiger_owner_list() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $users    = adfoin_get_vtiger_users();
    $groups   = adfoin_get_vtiger_groups();
    $combined = array_merge( $users, $groups );
    
    wp_send_json_success( $combined );
}

//Get User list
function adfoin_get_vtiger_users() {
    $data  = adfoin_vtiger_request( 'query?query=SELECT * FROM Users;' );
    $body  = json_decode( wp_remote_retrieve_body( $data ) );
    $users = wp_list_pluck( $body->result, 'userlabel', 'id' );

    return $users;
}

//Get User list
function adfoin_get_vtiger_groups() {
    $data   = adfoin_vtiger_request( 'query?query=SELECT * FROM Groups;' );
    $body   = json_decode( wp_remote_retrieve_body( $data ) );
    $groups = wp_list_pluck( $body->result, 'groupname', 'id' );

    return $groups;
}
 
add_action( 'wp_ajax_adfoin_get_vtiger_all_fields', 'adfoin_get_vtiger_all_fields', 10, 0 );
 
/*
* Get Vtiger CRM All Fields
*/
function adfoin_get_vtiger_all_fields() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $final_data       = array();
    $selected_objects = isset( $_POST['selectedObjects'] ) ? adfoin_sanitize_text_or_array_field( $_POST['selectedObjects'] ) : array();

    if( in_array( 'organization', $selected_objects ) ) {
        $org_fields = array(
            array( 'key' => 'org_accountname', 'value' => 'Name [Organization]', 'description' => 'Required if you want to create an organization, otherwise leave empty' ),
            array( 'key' => 'org_website', 'value' => 'Website [Organization]', 'description' => '' ),
        );

        $final_data = array_merge( $final_data, $org_fields );
    }

    if( in_array( 'contact', $selected_objects ) ) {
        $contact_fields = array(
            array( 'key' => 'contact_title', 'value' => 'Title [Contact]', 'description' => '' ),
            array( 'key' => 'contact_firstName', 'value' => 'First Name [Contact]', 'description' => 'Required if you want to create a contact, otherwise leave empty' ),
            array( 'key' => 'contact_lastName', 'value' => 'Last Name [Contact]', 'description' => '' ),
            array( 'key' => 'contact_jobTitle', 'value' => 'Job Title [Contact]', 'description' => '' ),
            array( 'key' => 'contact_workPhone', 'value' => 'Work Phone [Contact]', 'description' => '' ),
            array( 'key' => 'contact_homePhone', 'value' => 'Home Phone [Contact]', 'description' => '' ),
            array( 'key' => 'contact_mobilePhone', 'value' => 'Mobile Phone [Contact]', 'description' => '' ),
            array( 'key' => 'contact_email', 'value' => 'Email [Contact]', 'description' => '' ),
            array( 'key' => 'contact_website', 'value' => 'Website [Contact]', 'description' => '' ),
            array( 'key' => 'contact_twitter', 'value' => 'Twitter [Contact]', 'description' => '' ),
            array( 'key' => 'contact_linkedin', 'value' => 'LinkedIn [Contact]', 'description' => '' ),
            array( 'key' => 'contact_facebook', 'value' => 'Facebook [Contact]', 'description' => '' ),
            array( 'key' => 'contact_youtube', 'value' => 'YouTube [Contact]', 'description' => '' ),
            array( 'key' => 'contact_instagram', 'value' => 'Instagram [Contact]', 'description' => '' ),
            array( 'key' => 'contact_addressType', 'value' => 'Address Type [Contact]', 'description' => 'Home | Postal | Office | Billing | Shipping' ),
            array( 'key' => 'contact_address', 'value' => 'Address [Contact]', 'description' => '' ),
            array( 'key' => 'contact_city', 'value' => 'City [Contact]', 'description' => '' ),
            array( 'key' => 'contact_state', 'value' => 'State [Contact]', 'description' => '' ),
            array( 'key' => 'contact_zip', 'value' => 'Zip [Contact]', 'description' => '' ),
            array( 'key' => 'contact_country', 'value' => 'Country [Contact]', 'description' => '' ),
        );

        $final_data = array_merge( $final_data, $contact_fields );
    }

    wp_send_json_success( $final_data );
}
  
/*
* Handles sending data to Vtiger CRM API
*/
function adfoin_vtiger_send_data( $record, $posted_data ) {

    $record_data = json_decode( $record['data'], true );

    if( array_key_exists( 'cl', $record_data['action_data'] ) ) {
        if( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }

    $data       = $record_data['field_data'];
    $task       = $record['task'];
    $owner      = $data['owner'];
    $org_id     = '';
    $contact_id = '';


    if( $task == 'add_fields' ) {

        $org_data         = array();
        $contact_data     = array();

        foreach( $data as $key => $value ) {
            if( substr( $key, 0, 4 ) == 'org_' && $value ) {
                $key = substr( $key, 4 );

                $org_data[$key] = $value;
            }

            if( substr( $key, 0, 8 ) == 'contact_' && $value ) {
                $key = substr( $key, 8 );

                $contact_data[$key] = $value;
            }
        }

        if( isset( $org_data['accountname'] ) && $org_data['accountname'] ) {
            $endpoint           = 'create';
            $method             = 'POST';
            $org_holder         = array();
            $org_holder['accountname'] = adfoin_get_parsed_values( $org_data['accountname'], $posted_data );
            $org_holder['website']     = adfoin_get_parsed_values( $org_data['website'], $posted_data );

            if( $owner ) {
                $org_holder['assigned_user_id'] = $owner;
            }
            $org_holder   = array_filter( $org_holder );

            $org_args = array(
                'elementType' => 'Accounts',
                'element'     => json_encode( $org_holder )
            );

            $endpoint = add_query_arg( $org_args, $endpoint );

            // $org_id = adfoin_vtiger_party_exists( $org_holder['name'] );

            // if( $org_id ) {
            //     $endpoint = "parties/{$org_id}";
            //     $method   = 'PUT';
            // }

            
            $org_response = adfoin_vtiger_request( $endpoint, $method, array(), $record );
            $org_body     = json_decode( wp_remote_retrieve_body( $org_response ), true );

            if( isset( $org_body['result'] ) && isset( $org_body['result']['id'] ) ) {
                $org_id = $org_body['result']['id'];
            }
        }

    }

    return;
}
 
/*
* Checks if Party exists
* @returns: Party ID if exists
*/

function adfoin_vtiger_party_exists( $name ) {
 
    $endpoint = 'parties/search';

    $query_args = array(
        'q' => $name
    );

    $endpoint      = add_query_arg( $query_args, $endpoint );
    $response      = adfoin_vtiger_request( $endpoint, 'GET' );
    $response_code = wp_remote_retrieve_response_code( $response );
    $party_id      = '';
    
    if( 200 == $response_code ) {
        $response_body = json_decode( wp_remote_retrieve_body( $response ), true );

        if( isset( $response_body['parties'] ) && is_array( $response_body['parties'] ) ) {
            if( count( $response_body['parties'] ) > 0 ) {
                $party_id = $response_body['parties'][0]['id'];
            }
        }
    }

    if( $party_id ) {
        return $party_id;
    } else{
        return false;
    }
}