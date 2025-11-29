CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `emri` varchar (255) NOT NULL,
    `username` varchar (255) NOT NULL,
    `email` varchar (255) NOT NULL, 
    `password` varchar (255) NOT NULL,
    `confirm_password` varchar (255) NOT NULL,
    `is_admin` varchar (255) NOT NULL
) 

CREATE TABLE `movies` (
    `id` int (11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `movie_name` varchar (255) NOT NULL,
    `movie_desc` varchar (255) NOT NULL,
    `movie_quality` varchar (255) NOT NULL,
    `movie_rating` varchar (255) NOT NULL,
    `movie_image` varchar (255) NOT NULL
    )

 CREATE TABLE `bookings` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` int (11) NOT NULL,
    `movie_id` int (11) NOT NULL,
    `nr_tickets` int(11) NOT NULL,
    `date` varchar (255) NOT NULL,
    `is_approved` varchar (255) NOT NULL,
    `time` varchar(255) NOT NULL
    )