CREATE TABLE course (
  `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `day` INT NOT NULL,
  `time` TIME NOT NULL,
  `duration` INT NOT NULL,
  `capacity` INT NOT NULL,
  `age` INT NOT NULL
);
INSERT INTO
  course (`name`, `day`, `time`, `duration`, `capacity`, `age`)
VALUES
  ('Baby Poney', 1, '09:00', 60, 20, 2),
  ('Poney Débutant', 2, '09:00', 60, 20, 3),
  ('Poney Confirmé', 3, '09:00', 60, 20, 10),
  ('Baby Poney', 4, '09:00', 60, 20, 3),
  ('Poney Débutant', 5, '10:00', 90, 20, 6),
  ('Poney Confirmé', 6, '15:00', 120, 20, 12);

CREATE TABLE parent (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `firstname` VARCHAR(255) NOT NULL,
    `lastname` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone_number` VARCHAR(255) NOT NULL
);

CREATE TABLE pupil (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `firstname` VARCHAR(255) NOT NULL,
    `lastname` VARCHAR(255) NOT NULL,
    `birthday` DATE NOT NULL,
    `experience` BOOLEAN NOT NULL,
    `parent_id` INT NOT NULL
);

ALTER TABLE pupil
ADD CONSTRAINT fk_pupil_parent
FOREIGN KEY (parent_id)
REFERENCES parent(id);

CREATE TABLE stage (
  `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `starting_day` DATE NOT NULL,
  `ending_day` DATE NOT NULL,
  `capacity` INT NOT NULL,
  `age` INT NOT NULL
);

CREATE TABLE coursing (
  `pupil_id` INT NOT NULL,
  `course_id` INT NOT NULL
);

CREATE TABLE staging (
  `pupil_id` INT NOT NULL,
  `stage_id` INT NOT NULL
);