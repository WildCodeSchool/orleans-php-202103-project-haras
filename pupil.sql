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
