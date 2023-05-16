How to Let Users Login to WordPress Using an Email Address



//remove wordpress authentication
remove_filter('authenticate', 'wp_authenticate_username_password', 20);

//Email authentication
add_filter('authenticate', function($user, $email, $password){
 
    //Check for empty fields
        if(empty($email) || empty ($password)){        
            //create new error object and add errors to it.
            $error = new WP_Error();
 
            if(empty($email)){ //No email
                $error->add('empty_username', __('<strong>ERROR</strong>: Email field is empty.'));
            }
            else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ //Invalid Email
                $error->add('invalid_username', __('<strong>ERROR</strong>: Email is invalid.'));
            }
 
            if(empty($password)){ //No password
                $error->add('empty_password', __('<strong>ERROR</strong>: Password field is empty.'));
            }
 
            return $error;
        }
 
        //Check if user exists in WordPress database
        $user = get_user_by('email', $email);
 
        //bad email
        if(!$user){
            $error = new WP_Error();
            $error->add('invalid', __('<strong>ERROR</strong>: Either the email or password you entered is invalid.'));
            return $error;
        }
        else{ //check password
            if(!wp_check_password($password, $user->user_pass, $user->ID)){ //bad password
                $error = new WP_Error();
                $error->add('invalid', __('<strong>ERROR</strong>: Either the email or password you entered is invalid.'));
                return $error;
            }else{
                return $user; //passed
            }
        }
}, 20, 3);


https://wordpress.org/plugins/wp-email-login/