<?php
/**
 * Utility functions for the Marathon Registration system
 */

/**
 * Sanitize input data
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Validate Thai/English name
 */
function validate_name($name) {
    return preg_match('/^[a-zA-Zก-๙\s]+$/u', $name);
}

/**
 * Validate email
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Validate study year
 */
function validate_study_year($year) {
    return is_numeric($year) && $year >= 2500 && $year <= 2600;
}

/**
 * Redirect to a page
 */
function redirect($page) {
    header("Location: $page");
    exit;
}

/**
 * Get a runner by ID
 */
function get_runner_by_id($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM runners WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

/**
 * Check if email exists (excluding current user)
 */
function email_exists_excluding_id($conn, $email, $exclude_id = null) {
    if ($exclude_id) {
        $stmt = $conn->prepare("SELECT id FROM runners WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $exclude_id);
    } else {
        $stmt = $conn->prepare("SELECT id FROM runners WHERE email = ?");
        $stmt->bind_param("s", $email);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

/**
 * Validate phone number
 */
function validate_phone($phone) {
    return preg_match('/^0[0-9]{9}$/', $phone);
}

/**
 * Validate date
 */
function validate_date($date) {
    return strtotime($date) !== false;
}

/**
 * Calculate age from birth date
 */
function calculate_age($birth_date) {
    $birthDate = new DateTime($birth_date);
    $today = new DateTime('today');
    return $birthDate->diff($today)->y;
}
?>