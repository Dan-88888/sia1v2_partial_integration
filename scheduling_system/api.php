<?php
// Consolidated API endpoint for instructor and student AJAX calls
require_once __DIR__ . '/inc/session_handler.php';
session_start();
header("Content-Type: application/json");

if (!$pdo) {
    echo json_encode(["error" => "Database unavailable"]);
    exit;
}

$action = $_GET['action'] ?? '';

switch ($action) {

    // ── Instructor: dashboard stats ─────────────────────────────────────────
    case 'dashboard':
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["assignedClasses" => 0, "totalSubjects" => 0, "classrooms" => 0]);
            exit;
        }
        $uid = $_SESSION['user_id'];

        $s = $pdo->prepare("SELECT instructor_id FROM sch_instructor WHERE user_id = ?");
        $s->execute([$uid]);
        $inst = $s->fetch();

        if (!$inst) {
            echo json_encode(["assignedClasses" => 0, "totalSubjects" => 0, "classrooms" => 0]);
            exit;
        }
        $iid = $inst['instructor_id'];

        $s = $pdo->prepare("SELECT COUNT(*) AS total FROM sch_schedule WHERE instructor_id = ?");
        $s->execute([$iid]); $assigned = $s->fetch()['total'];

        $s = $pdo->prepare("SELECT COUNT(DISTINCT subject_id) AS total FROM sch_schedule WHERE instructor_id = ?");
        $s->execute([$iid]); $subjects = $s->fetch()['total'];

        $s = $pdo->prepare("SELECT COUNT(DISTINCT room_id) AS total FROM sch_schedule WHERE instructor_id = ?");
        $s->execute([$iid]); $rooms = $s->fetch()['total'];

        echo json_encode(["assignedClasses" => $assigned, "totalSubjects" => $subjects, "classrooms" => $rooms]);
        break;

    // ── Instructor: assigned classes ────────────────────────────────────────
    case 'classes':
        if (!isset($_SESSION['user_id'])) { echo json_encode([]); exit; }
        $uid = $_SESSION['user_id'];

        $s = $pdo->prepare("SELECT instructor_id FROM sch_instructor WHERE user_id = ?");
        $s->execute([$uid]);
        $inst = $s->fetch();

        if (!$inst) { echo json_encode([]); exit; }
        $iid = $inst['instructor_id'];

        $s = $pdo->prepare("
            SELECT sub.course_code, sub.description, sch.section, sch.day,
                   sch.start_time, sch.end_time, r.room_name
            FROM sch_schedule sch
            LEFT JOIN sch_subjects sub ON sch.subject_id = sub.id
            LEFT JOIN sch_rooms    r   ON sch.room_id    = r.id
            WHERE sch.instructor_id = ?
        ");
        $s->execute([$iid]);
        $data = [];
        foreach ($s->fetchAll() as $row) {
            $data[] = [
                "code"       => $row['course_code'],
                "name"       => $row['description'],
                "section"    => $row['section'],
                "day"        => $row['day'],
                "start_time" => $row['start_time'],
                "end_time"   => $row['end_time'],
                "room_name"  => $row['room_name'],
            ];
        }
        echo json_encode($data);
        break;

    // ── Student: class schedule ─────────────────────────────────────────────
    case 'student_schedule':
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["status" => "error", "message" => "Not logged in"]);
            exit;
        }
        $uid = $_SESSION['user_id'];

        $s = $pdo->prepare("
            SELECT sub.course_code, sub.description, sub.unit, sch.section,
                   sch.day, sch.start_time, sch.end_time, r.room_name,
                   u.full_name AS instructor_name
            FROM sch_enrollment e
            INNER JOIN sch_schedule  sch ON e.schedule_id    = sch.id
            INNER JOIN sch_subjects  sub ON sch.subject_id   = sub.id
            INNER JOIN sch_rooms     r   ON sch.room_id      = r.id
            LEFT  JOIN sch_instructor i  ON sch.instructor_id = i.instructor_id
            LEFT  JOIN users u           ON i.user_id        = u.user_id
            WHERE e.user_id = ?
            ORDER BY sch.id ASC
        ");
        $s->execute([$uid]);
        $rows = $s->fetchAll();

        foreach ($rows as &$row) {
            $start = $row['start_time'] ? date("h:i A", strtotime($row['start_time'])) : '';
            $end   = $row['end_time']   ? date("h:i A", strtotime($row['end_time']))   : '';
            $row['schedule'] = $row['day'] . " " . $start . " - " . $end;
        }
        echo json_encode($rows);
        break;

    // ── Student: profile info ───────────────────────────────────────────────
    case 'student_info':
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["status" => "error", "message" => "Not logged in"]);
            exit;
        }
        $uid = $_SESSION['user_id'];

        $s = $pdo->prepare("
            SELECT u.user_id, u.full_name, sp.course_program, sp.section, sp.academic_year, sp.period
            FROM users u
            LEFT JOIN sch_student_profile sp ON u.user_id = sp.user_id
            WHERE u.user_id = ?
            LIMIT 1
        ");
        $s->execute([$uid]);
        $st = $s->fetch();

        if (!$st) {
            echo json_encode(["status" => "error", "message" => "Student record not found"]);
            exit;
        }
        echo json_encode([
            "status"        => "success",
            "name"          => $st['full_name'],
            "section"       => $st['section'] ?? "",
            "academic_year" => $st['academic_year'] ?? "",
            "period"        => $st['period'] ?? "",
        ]);
        break;

    default:
        http_response_code(400);
        echo json_encode(["error" => "Unknown action"]);
}
