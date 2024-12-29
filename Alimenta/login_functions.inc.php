<?php 
function redirect_user ($page) {

	// Start defining the URL...
	// URL is http:// plus the host name plus the current directory:
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	
	// Remove any trailing slashes:
	$url = rtrim($url, '/\\');
	
	// Add the page:
	$url .= '/' . $page;
	
	// Redirect the user:
	header("Location: $url");
	exit(); // Quit the script.

} // End of redirect_user() function.

function check_login($dbc, $email = '', $pass = '') {

	$errors = array(); // Initialize error array.

// Validate the email address:
	if (empty ($email)) {
	    $errors[] = 'You forgot to enter your email address.'; 
	}else {
	    $e = mysqli_real_escape_string($dbc, trim($email));
	}
	
	// Validate the password:
	if (empty ($pass)) {
	    $errors[] = 'You forgot to enter your password.';
	}else {
	    $p = mysqli_real_escape_string($dbc, trim($pass));
	}

	if (empty($errors)) { // If everything's OK.

		// Retrieve the user_id and first_name for that email/password combination:
	    $q = "SELECT user_id, first_name, last_name, email FROM users WHERE email='$e' AND pass=SHA1('$p')";
		$r = @mysqli_query ($dbc, $q); // Run the query.
		
		// Check the result:
		if (mysqli_num_rows($r) == 1) {

			// Fetch the record:
			$row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
	
			// Return true and the record:
			return array(true, $row);
			
		} else { // Not a match!
			$errors[] = 'The email address and password entered do not match those on file.';
		}
		
	} // End of empty($errors) IF.
	
	// Return false and the errors:
	return array(false, $errors);

} // End of check_login() function.

function check_admin_login($dbc, $email = '', $password = '') {
    $errors = array();
    
    if (empty($email)) {
        $errors[] = 'You forgot to enter your email address.';
    } else {
        $e = mysqli_real_escape_string($dbc, trim($email));
    }
    
    if (empty($password)) {
        $errors[] = 'You forgot to enter your password.';
    } else {
        $p = mysqli_real_escape_string($dbc, trim($password));
    }
    
    if (empty($errors)) {
        // Update the query to compare the plain text password
        $q = "SELECT admin_id, first_name, password FROM admin WHERE email='$e'"; 
        $r = @mysqli_query($dbc, $q);
        
        if ($r && mysqli_num_rows($r) == 1) {
            $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
            
            // Compare the entered password with the stored password
            if ($row['password'] == $p) {
                return array(true, $row);  // Password matches, login successful and redirected to admin page
            } else {
                $errors[] = 'The email address and password entered do not match those on file.';  // Password mismatch
            }
        } else {
            $errors[] = 'The email address and password entered do not match those on file.';  // User not found
        }
    }
    
    return array(false, $errors);  // Return errors if any
}

?>