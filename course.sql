-- create table course
CREATE TABLE course (
  `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `day` VARCHAR(8) NOT NULL,
  `time` TIME NOT NULL,
  `duration` INT NOT NULL,
  `capacity` INT NOT NULL
);
--insert many fake courses for test
INSERT INTO
  course (`name`, `day`, `time`, `duration`, `capacity`)
VALUES
  ('Baby Poney', 'lundi', '09:00', 60, 20),
  ('Baby Poney', 'mardi', '09:00', 60, 20),
  ('Poney Débutant', 'jeudi', '10:00', 90, 20),
  ('Poney Confirmé', 'mercredi', '15:00', 120, 20);