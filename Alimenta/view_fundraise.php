<?php
$page_title = 'View Fundraisers';

echo '<h1>Fundraiser Requests</h1>';

// Connect to the database
require('mysqli_connect.php');

// Number of records to show per page:
$display = 10;

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
    case 'title':
        $order_by = 'title ASC';
        break;
    case 'amount':
        $order_by = 'amount ASC';
        break;
    case 'sdate':
        $order_by = 'sdate ASC';
        break;
    case 'edate':
        $order_by = 'edate ASC';
        break;
    default:
        $order_by = 'title ASC';
        $sort = 'title';
        break;
}

// Define the query:
$q = "SELECT id, title, description, organizer, email, phone, amount, DATE_FORMAT(sdate, '%M %d, %Y') AS sdate, DATE_FORMAT(edate, '%M %d, %Y') AS edate, type, location, terms, status FROM fundraise ORDER BY $order_by LIMIT $start, $display";
$r = @mysqli_query($dbc, $q); // Run the query

// Table header:
echo '<h1>Fundraisers</h1>';
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
    </tr>';
} // End of WHILE loop.

echo '</table>';
mysqli_free_result($r);

// Close the database connection
mysqli_close($dbc);

// Make the links to other pages, if necessary
if ($pages > 1) {
    echo '<br /><p>';
    $current_page = ($start / $display) + 1;
    
    // If it's not the first page, make a Previous button:
    if ($current_page != 1) {
        echo '<a href="view_fundraise.php?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
    }
    
    // Make all the numbered pages:
    for ($i = 1; $i <= $pages; $i++) {
        if ($i != $current_page) {
            echo '<a href="view_fundraise.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
        } else {
            echo $i . ' ';
        }
    }
    
    // If it's not the last page, make a Next button:
    if ($current_page != $pages) {
        echo '<a href="view_fundraise.php?s=' . ($start + $display) . '&p=' . $pages . '&sort=' . $sort . '">Next</a>';
    }
    
    echo '</p>'; // Close the paragraph
}
?>
