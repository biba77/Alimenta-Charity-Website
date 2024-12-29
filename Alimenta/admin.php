<?php
// This is the admin page of the website
include 'admin.html';
require 'mysqli_connect.php'; // Database connection

$page_title = 'Admin Page - View Registered Users';

// Number of records to show per page for registered users:
$display = 10;

// Determine how many pages there are for registered users...
if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already determined
    $pages = $_GET['p'];
} else { // Need to determine
    $q = "SELECT COUNT(user_id) FROM users";
    $r = @mysqli_query($dbc, $q);
    $row = @mysqli_fetch_array($r, MYSQLI_NUM);
    $records = $row[0];
    
    // Calculate the number of pages for registered users
    $pages = ($records > $display) ? ceil($records / $display) : 1;
}

// Determine where in the database to start returning results for registered users
$start = (isset($_GET['s']) && is_numeric($_GET['s'])) ? $_GET['s'] : 0;

// Sort logic for registered users
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'rd';
switch ($sort) {
    case 'ln': $order_by = 'last_name ASC'; break;
    case 'fn': $order_by = 'first_name ASC'; break;
    case 'rd': $order_by = 'registration_date ASC'; break;
    default: $order_by = 'registration_date ASC'; $sort = 'rd';
}

// Define the query for registered users
$q = "SELECT last_name, first_name, DATE_FORMAT(registration_date, '%M %d, %Y') AS dr, user_id
      FROM users ORDER BY $order_by LIMIT $start, $display";
$r = @mysqli_query($dbc, $q);

// Registered Users Table
echo '<section class="users">';
echo '<h2>Registered Users</h2>';
echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">';
echo '<tr>
        <td align="left"><b>Edit</b></td>
        <td align="left"><b>Delete</b></td>
        <td align="left"><b>Last Name</b></td>
        <td align="left"><b>First Name</b></td>
        <td align="left"><b>Date Registered</b></td>
      </tr>';

$bg = '#eeeeee';
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
    $bg = ($bg == '#eeeeee' ? '#ffffff' : '#eeeeee');
    echo '<tr bgcolor="' . $bg . '">
            <td align="left"><a href="edit_user.php?id=' . $row['user_id'] . '">Edit</a></td>
            <td align="left"><a href="delete_user.php?id=' . $row['user_id'] . '">Delete</a></td>
            <td align="left">' . $row['last_name'] . '</td>
            <td align="left">' . $row['first_name'] . '</td>
            <td align="left">' . $row['dr'] . '</td>
          </tr>';
}
echo '</table>';
echo '</section>';

// Free result set for registered users
mysqli_free_result($r);

// Now, let's display the volunteering table:

// Number of records to show per page for volunteering applications:
$display_volunteer = 10;

// Determine how many pages there are for volunteering applications...
if (isset($_GET['p_vol']) && is_numeric($_GET['p_vol'])) { // Already determined
    $pages_vol = $_GET['p_vol'];
} else { // Need to determine
    $q_vol = "SELECT COUNT(id) FROM volunteering";
    $r_vol = @mysqli_query($dbc, $q_vol);
    $row_vol = @mysqli_fetch_array($r_vol, MYSQLI_NUM);
    $records_vol = $row_vol[0];
    
    // Calculate the number of pages for volunteering applications
    $pages_vol = ($records_vol > $display_volunteer) ? ceil($records_vol / $display_volunteer) : 1;
}

// Determine where in the database to start returning results for volunteering applications
$start_vol = (isset($_GET['s_vol']) && is_numeric($_GET['s_vol'])) ? $_GET['s_vol'] : 0;

// Sort logic for volunteering applications
$sort_vol = isset($_GET['sort_vol']) ? $_GET['sort_vol'] : 'first_name';
switch ($sort_vol) {
    case 'first_name': $order_by_vol = 'first_name ASC'; break;
    case 'last_name': $order_by_vol = 'last_name ASC'; break;
    case 'age': $order_by_vol = 'age ASC'; break;
    case 'event': $order_by_vol = 'event ASC'; break;
    default: $order_by_vol = 'first_name ASC'; $sort_vol = 'first_name';
}

// Define the query for volunteering applications
$q_vol = "SELECT id, first_name, last_name, email, phone_number, country, event, age, areas, terms,
                 DATE_FORMAT(submitted_at, '%M %d, %Y') AS submitted_at
          FROM volunteering
          ORDER BY $order_by_vol LIMIT $start_vol, $display_volunteer";
$r_vol = @mysqli_query($dbc, $q_vol);

// Volunteering Applications Table
echo '<section class="volunteers">';
echo '<h2>Volunteering Applications</h2>';
echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">';
echo '<tr>
        <td align="left"><b>First Nam</b></td>
        <td align="left"><b>Last Name</b></td>
        <td align="left"><b>Email</b></td>
        <td align="left"><b>Phone Number</b></td>
        <td align="left"><b>Country</b></td>
        <td align="left"><b>Event</b></td>
        <td align="left"><b>Age</b></td>
        <td align="left"><b>Areas</b></td>
        <td align="left"><b>Terms</b></td>
        <td align="left"><b>Submitted At</b></td>
      </tr>';

$bg = '#eeeeee';
while ($row_vol = mysqli_fetch_array($r_vol, MYSQLI_ASSOC)) {
    $bg = ($bg == '#eeeeee' ? '#ffffff' : '#eeeeee');
    echo '<tr bgcolor="' . $bg . '">
            <td align="left">' . $row_vol['first_name'] . '</td>
            <td align="left">' . $row_vol['last_name'] . '</td>
            <td align="left">' . $row_vol['email'] . '</td>
            <td align="left">' . $row_vol['phone_number'] . '</td>
            <td align="left">' . $row_vol['country'] . '</td>
            <td align="left">' . $row_vol['event'] . '</td>
            <td align="left">' . $row_vol['age'] . '</td>
            <td align="left">' . $row_vol['areas'] . '</td>
            <td align="left">' . $row_vol['terms'] . '</td>
            <td align="left">' . $row_vol['submitted_at'] . '</td>
          </tr>';
}
echo '</table>';
echo '</section>';

// Free result set for volunteering applications
mysqli_free_result($r_vol);

// Now, let's display the fundraising table:
// Number of records to show per page for volunteering applications:
$display_volunteer = 10;

// Determine how many pages there are...
if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already been determined
    $pages = $_GET['p'];
} else { // Need to determine
    // Count the number of records:
    $q = "SELECT COUNT(id) FROM fundraise";
    $r = @mysqli_query($dbc, $q);
    $row = @mysqli_fetch_array($r, MYSQLI_NUM);
    $records = $row[0];
    // Calculate the number of pages...
    if ($records > $display) { // More than 1 page
        $pages = ceil($records / $display);
    } else {
        $pages = 1;
    }
} // End of p IF.

// Determine where in the database to start returning results...
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
    $start = $_GET['s'];
} else {
    $start = 0;
}

// Determine the sort...
// Default is by title.
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'title';

// Determine the sorting order:
switch ($sort) {
    case 'title': $order_by = 'title ASC'; break;
    case 'amount': $order_by = 'amount ASC'; break;
    case 'sdate': $order_by = 'sdate ASC'; break;
    case 'edate': $order_by = 'edate ASC'; break;
    default: $order_by = 'title ASC'; $sort = 'title'; break;
}

// Define the query:
$q = "SELECT id, title, description, organizer, email, phone, amount, DATE_FORMAT(sdate, '%M %d, %Y') AS sdate, DATE_FORMAT(edate, '%M %d, %Y') AS edate, type, location, terms, status FROM fundraise ORDER BY $order_by LIMIT $start, $display";
$r = @mysqli_query($dbc, $q); // Run the query

// Table header:
echo '<section class="fundraisers">';
echo '<h2>Fundraisers</h2>';
echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">
<tr>
    <td align="left"><b><a href="view_fundraise.php?sort=title">Title</a></b></td>
    <td align="left"><b><a href="view_fundraise.php?sort=description">Description</a></b></td>
    <td align="left"><b><a href="view_fundraise.php?sort=organizer">Organizer</a></b></td>
    <td align="left"><b><a href="view_fundraise.php?sort=email">Email</a></b></td>
    <td align="left"><b><a href="view_fundraise.php?sort=phone">Phone</a></b></td>
    <td align="left"><b><a href="view_fundraise.php?sort=amount">Amount</a></b></td>
    <td align="left"><b><a href="view_fundraise.php?sort=sdate">Start Date</a></b></td>
    <td align="left"><b><a href="view_fundraise.php?sort=edate">End Date</a></b></td>
    <td align="left"><b><a href="view_fundraise.php?sort=type">Type</a></b></td>
    <td align="left"><b><a href="view_fundraise.php?sort=location">Location</a></b></td>
    <td align="left"><b><a href="view_fundraise.php?sort=terms">Terms</a></b></td>
    <td align="left"><b><a href="view_fundraise.php?sort=status">Status</a></b></td>
    <td align="left"><b>Actions</b></td>
</tr>';

// Fetch and print all the records
$bg = '#eeeeee';
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
    $bg = ($bg == '#eeeeee' ? '#ffffff' : '#eeeeee');
    echo '<tr bgcolor="' . $bg . '">
        <td align="left">' . $row['title'] . '</td>
        <td align="left">' . $row['description'] . '</td>
        <td align="left">' . $row['organizer'] . '</td>
        <td align="left">' . $row['email'] . '</td>
        <td align="left">' . $row['phone'] . '</td>
        <td align="left">$' . number_format($row['amount'], 2) . '</td>
        <td align="left">' . $row['sdate'] . '</td>
        <td align="left">' . $row['edate'] . '</td>
        <td align="left">' . $row['type'] . '</td>
        <td align="left">' . $row['location'] . '</td>
        <td align="left">' . $row['terms'] . '</td>
        <td align="left">' . $row['status'] . '</td>
        <td align="left">
            <form action="process_fundraise.php" method="post" style="display:inline;">
                <input type="hidden" name="id" value="' . $row['id'] . '">
                <input type="submit" name="action" value="Accept" class="btn btn-success">
            </form>
            <form action="process_fundraise.php" method="post" style="display:inline;">
                <input type="hidden" name="id" value="' . $row['id'] . '">
                <input type="submit" name="action" value="Reject" class="btn btn-danger">
            </form>
        </td>
    </tr>';
} // End of WHILE loop.

echo '</table>';
mysqli_free_result($r);



// Now, let's display the donations table:

// Number of records to show per page:
$display = 10;

// Determine how many pages there are...
if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already determined.
    $pages = $_GET['p'];
} else { // Need to determine.
    // Count the number of records:
    $q = "SELECT COUNT(user_id) FROM donations";
    $r = @mysqli_query($dbc, $q);
    $row = @mysqli_fetch_array($r, MYSQLI_NUM);
    $records = $row[0];
    
    // Calculate the number of pages...
    if ($records > $display) { // More than 1 page.
        $pages = ceil($records / $display);
    } else {
        $pages = 1;
    }
} // End of p IF.

// Determine where in the database to start returning results...
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
    $start = $_GET['s'];
} else {
    $start = 0;
}

// Determine the sort...
// Default is by donation date.
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'dd';

// Determine the sorting order:
switch ($sort) {
    case 'da':
        $order_by = 'donation_amount ASC';
        break;
    case 'dd':
        $order_by = 'donation_date ASC';
        break;
    case 'fr':
        $order_by = 'frequency ASC';
        break;
    case 'pm':
        $order_by = 'payment_method ASC';
        break;
    default:
        $order_by = 'donation_date ASC';
        $sort = 'dd';
        break;
}

// Define the query:
$q = "SELECT user_id, donation_amount, DATE_FORMAT(donation_date, '%M %d, %Y') AS dd, frequency, payment_method FROM donations ORDER BY $order_by LIMIT $start, $display";
$r = @mysqli_query($dbc, $q); // Run the query.

// Table header:
echo '<section class="donations">';
echo '<h2>Donations</h2>';
echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">';
echo '<tr>
        <td align="left"><b>Edit</td>
        <td align="left"><b>Delete</td>
        <td align="left"><b>Donation Amount</b></td>
        <td align="left"><b>Donation Date</b></td>
        <td align="left"><b>Frequency</b></td>
        <td align="left"><b>Payment Method</b></td>
       </tr>';

// Fetch and print all the records....
$bg = '#eeeeee'; // Background color toggle.
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
    $bg = ($bg == '#eeeeee' ? '#ffffff' : '#eeeeee');
    echo '<tr bgcolor="' . $bg . '">
        <td align="left"><a href="edit_donation.php?id=' . $row['user_id'] . '">Edit</a></td>
        <td align="left"><a href="delete_donation.php?id=' . $row['user_id'] . '">Delete</a></td>
        <td align="left">' . $row['donation_amount'] . '</td>
        <td align="left">' . $row['dd'] . '</td>
        <td align="left">' . $row['frequency'] . '</td>
        <td align="left">' . $row['payment_method'] . '</td>
    </tr>';
} // End of WHILE loop.
echo '</table>';
echo '</section>';

echo '</table>';
mysqli_free_result($r);


// Close the database connection
mysqli_close($dbc);


?>
