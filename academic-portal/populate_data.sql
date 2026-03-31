TRUNCATE TABLE students;
TRUNCATE TABLE events;

INSERT INTO `events` (`event_name`, `category`, `event_date`, `description`, `venue`, `event_time`, `image`, `seats`, `status`) VALUES
('Tech Symposium 2026', 'Tech', '2026-04-15', 'A national level technical symposium featuring paper presentations, coding contests, and more.', 'Seminar Hall A', '10:00 AM', 'tech_sympo.png', 100, 'Open'),
('Cultural Fest - Echoes', 'Cultural', '2026-05-20', 'Annual cultural festival with music, dance, and arts competition.', 'Main Auditorium', '09:00 AM', 'cultural_fest.png', 500, 'Open'),
('Workshop on AI/ML', 'Workshop', '2026-04-10', 'Hands-on workshop on Artificial Intelligence and Machine Learning basics.', 'CS Lab 1', '11:00 AM', 'ai_workshop.png', 50, 'Open'),
('Hackathon 2026', 'Tech', '2026-06-10', '24-hour coding marathon focused on sustainable technology solutions.', 'Main Computer Lab', '09:00 AM', 'hackathon.png', 80, 'Open'),
('Blockchain Seminar', 'Seminar', '2026-04-20', 'Guest lecture by industry experts exploring the future of Web3 and Decentralization.', 'Conference Hall 1', '02:00 PM', 'blockchain.png', 120, 'Open'),
('Sports Meet', 'Sports', '2026-07-05', 'Annual campus athletics meet featuring track and field events.', 'Campus Stadium', '08:00 AM', 'sports_meet.png', 1000, 'Open');

INSERT INTO `students` (`name`, `rollno`, `email`, `mobile`, `year`, `department`, `password`) VALUES
('John Doe', '21CS001', 'john@example.com', '9876543210', '3', 'CSE', MD5('1234')),
('Alice Smith', '21CS002', 'alice@example.com', '9876543211', '3', 'CSE', MD5('1234')),
('Bob Jones', '21CS003', 'bob@example.com', '9876543212', '2', 'ECE', MD5('1234')),
('Charlie Brown', '21CS004', 'charlie@example.com', '9876543213', '1', 'MECH', MD5('1234'));
