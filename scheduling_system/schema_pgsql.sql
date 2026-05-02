-- PostgreSQL schema for unified_scheduling (Neon)
-- Run this once to set up the database

CREATE TABLE IF NOT EXISTS users (
    user_id   VARCHAR(45) PRIMARY KEY,
    password  VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role      VARCHAR(20) NOT NULL CHECK (role IN ('student','instructor','admin'))
);

CREATE TABLE IF NOT EXISTS instructor (
    instructor_id VARCHAR(45) PRIMARY KEY,
    user_id       VARCHAR(45) REFERENCES users(user_id),
    department    VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS rooms (
    id        INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    room_name VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS subjects (
    id          INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    course_code VARCHAR(30) NOT NULL,
    description VARCHAR(255) NOT NULL,
    unit        NUMERIC(3,1) NOT NULL
);

CREATE TABLE IF NOT EXISTS schedule (
    id             INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    unit           NUMERIC(3,1) NOT NULL DEFAULT 0,
    section        VARCHAR(50) NOT NULL DEFAULT '',
    created_by     VARCHAR(45) REFERENCES users(user_id),
    instructor_id  VARCHAR(45) REFERENCES instructor(instructor_id),
    day            VARCHAR(20),
    start_time     TIME,
    end_time       TIME,
    instructor_name VARCHAR(100),
    subject_id     INTEGER REFERENCES subjects(id),
    room_id        INTEGER REFERENCES rooms(id)
);

CREATE TABLE IF NOT EXISTS enrollment (
    id          INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    user_id     VARCHAR(45) REFERENCES users(user_id),
    schedule_id INTEGER REFERENCES schedule(id)
);

CREATE TABLE IF NOT EXISTS student_profile (
    user_id        VARCHAR(45) PRIMARY KEY REFERENCES users(user_id),
    course_program VARCHAR(45),
    section        VARCHAR(45),
    academic_year  VARCHAR(20),
    period         VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS php_sessions (
    sess_id   VARCHAR(128) PRIMARY KEY,
    sess_data TEXT NOT NULL DEFAULT '',
    sess_time BIGINT NOT NULL DEFAULT 0
);

-- ============================================================
-- Seed data
-- ============================================================
INSERT INTO users (user_id, password, full_name, role) VALUES
    ('2024001', '12345',    'Pepito Manaloto',        'student'),
    ('admin01', 'admin123', 'System Admin',           'admin'),
    ('inst01',  '12345',    'Juan Dela Cruz',         'instructor'),
    ('inst02',  '12345',    'Rowell Artiaga',         'instructor'),
    ('inst03',  '12345',    'Salvador Briones ||',    'instructor'),
    ('inst04',  '12345',    'Nicolas Pura',           'instructor')
ON CONFLICT (user_id) DO NOTHING;

INSERT INTO instructor (instructor_id, user_id, department) VALUES
    ('INST001', 'inst01', 'BSIT'),
    ('INST002', 'inst02', 'BSIT'),
    ('INST003', 'inst03', 'BSIT'),
    ('INST004', 'inst04', 'BSIT')
ON CONFLICT (instructor_id) DO NOTHING;

INSERT INTO rooms (id, room_name) OVERRIDING SYSTEM VALUE VALUES
    (1, 'Room1'), (2, 'Room2'), (3, 'Room3'),
    (4, 'Room11'), (5, 'Lab1'), (6, 'Lab2')
ON CONFLICT (id) DO NOTHING;
SELECT setval(pg_get_serial_sequence('rooms', 'id'), 7, false);

INSERT INTO subjects (id, course_code, description, unit) OVERRIDING SYSTEM VALUE VALUES
    (1, 'Math101', 'Mathematics in the Modern World', 3.0),
    (2, 'IT101',   'Introduction to Computing',       3.0),
    (3, 'Eng101',  'English Communication',           3.0)
ON CONFLICT (id) DO NOTHING;
SELECT setval(pg_get_serial_sequence('subjects', 'id'), 4, false);

INSERT INTO schedule (id, unit, section, created_by, instructor_id, day, start_time, end_time, instructor_name, subject_id, room_id)
OVERRIDING SYSTEM VALUE VALUES
    (9,  0.0, 'BSIT', 'admin01', 'INST002', 'Wednesday', '14:15:00', '14:42:00', NULL, 2, 6),
    (11, 0.0, 'BSIT', 'admin01', 'INST001', 'Monday',    '01:34:00', '05:30:00', NULL, 1, 1)
ON CONFLICT (id) DO NOTHING;
SELECT setval(pg_get_serial_sequence('schedule', 'id'), 14, false);

INSERT INTO enrollment (id, user_id, schedule_id) OVERRIDING SYSTEM VALUE VALUES
    (4, '2024001', 9),
    (5, '2024001', 11)
ON CONFLICT (id) DO NOTHING;
SELECT setval(pg_get_serial_sequence('enrollment', 'id'), 6, false);

INSERT INTO student_profile (user_id, course_program, section, academic_year, period) VALUES
    ('2024001', 'BSIT', '2B', '2025-2026', '1st')
ON CONFLICT (user_id) DO NOTHING;
