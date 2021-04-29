-- create table course
CREATE TABLE course (
  `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `day` INT NOT NULL,
  `time` TIME NOT NULL,
  `duration` INT NOT NULL,
  `capacity` INT NOT NULL
);
--insert many fake courses for test
INSERT INTO
  course (`name`, `day`, `time`, `duration`, `capacity`)
VALUES
  ('Baby Poney', 1, '09:00', 60, 20),
  ('Poney Débutant', 2, '09:00', 60, 20),
  ('Poney Confirmé', 3, '09:00', 60, 20),
  ('Baby Poney', 4, '09:00', 60, 20),
  ('Poney Débutant', 5, '10:00', 90, 20),
  ('Poney Confirmé', 6, '15:00', 120, 20);