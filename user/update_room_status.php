<?php// Query to update room status based on current time
$update_status_query = "
    UPDATE rooms r
    LEFT JOIN bookings b ON r.room_number = b.room_number
    SET r.status = 'available'
    WHERE b.booking_end_time < NOW() 
    AND r.status = 'unavailable'";

// Execute the query to update the status
if (mysqli_query($conn, $update_status_query)) {
    // Room statuses have been updated
} else {
    echo "Error updating room status: " . mysqli_error($conn);
}
?>