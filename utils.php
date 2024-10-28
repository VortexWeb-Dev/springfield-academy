<?php
function get_title($page_url)
{
    $titles = array(
        'index.php' => 'Dashboard',
        'stream.php' => 'Stream',
        'profile.php' => 'Profile',
        'rating.php' => 'My Rating',
        'tests.php' => 'My Tests',
        'courses.php' => 'My Courses',
        'groups.php' => 'My Groups',
        'grades.php' => 'My Grades',
        'checks.php' => 'My Checks',
        'certificates.php' => 'My Certificates',
        'competencies.php' => 'My Competencies',
        'events.php' => 'My Events',
        'programs.php' => 'My Programs',
        'development_plan.php' => 'Individual Development Plan',
        'reports.php' => 'Reports',
        'manage_courses.php' => 'Courses',
        'manage_tests.php' => 'Tests',
        'manage_events.php' => 'Events',
        'help.php' => 'Help',
        'add_course.php' => 'Add Course',
        'edit_course.php' => 'Edit Course',
        'add_test.php' => 'Add Test',
        'add_event.php' => 'Add Event',
    );
    return isset($titles[$page_url]) ? $titles[$page_url] : 'Training';
}
