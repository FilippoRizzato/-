
CREATE TABLE Indirizzo (
                           id INT PRIMARY KEY AUTO_INCREMENT,
                           nome VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Articolazione (
                               id INT PRIMARY KEY AUTO_INCREMENT,
                               nome VARCHAR(255) NOT NULL,
                               indirizzo_id INT NOT NULL,
                               FOREIGN KEY (indirizzo_id) REFERENCES Indirizzo(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Classe (
                        id INT PRIMARY KEY AUTO_INCREMENT,
                        nome VARCHAR(255) NOT NULL,
                        articolazione_id INT NOT NULL,
                        FOREIGN KEY (articolazione_id) REFERENCES Articolazione(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Materia (
                         id INT PRIMARY KEY AUTO_INCREMENT,
                         nome VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Persona (
                         id INT PRIMARY KEY AUTO_INCREMENT,
                         tipo ENUM('Docente', 'Genitore', 'Alunno', 'Personale') NOT NULL,
                         nome VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- Tabella Iscrizione corretta
CREATE TABLE Iscrizione (
                            id INT PRIMARY KEY AUTO_INCREMENT,
                            alunno_id INT NOT NULL,
                            classe_id INT NOT NULL,
                            anno_scolastico YEAR NOT NULL,
                            UNIQUE KEY (alunno_id, classe_id, anno_scolastico),
                            FOREIGN KEY (alunno_id) REFERENCES Persona(id) ON DELETE CASCADE,
                            FOREIGN KEY (classe_id) REFERENCES Classe(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabella Docente_Materia
CREATE TABLE Docente_Materia (
                                 docente_id INT NOT NULL,
                                 materia_id INT NOT NULL,
                                 PRIMARY KEY (docente_id, materia_id),
                                 FOREIGN KEY (docente_id) REFERENCES Persona(id) ON DELETE CASCADE,
                                 FOREIGN KEY (materia_id) REFERENCES Materia(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabella Piano_Di_Studio
CREATE TABLE Piano_Di_Studio (
                                 id INT PRIMARY KEY AUTO_INCREMENT,
                                 classe_id INT NOT NULL,
                                 materia_id INT NOT NULL,
                                 docente_id INT NOT NULL,
                                 FOREIGN KEY (classe_id) REFERENCES Classe(id) ON DELETE CASCADE,
                                 FOREIGN KEY (materia_id) REFERENCES Materia(id) ON DELETE CASCADE,
                                 FOREIGN KEY (docente_id) REFERENCES Persona(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Credenziali (
                             id INT PRIMARY KEY AUTO_INCREMENT,
                             username VARCHAR(255) NOT NULL UNIQUE,
                             password VARCHAR(255) NOT NULL,
                             persona_id INT,
                             FOREIGN KEY (persona_id) REFERENCES Persona(id)
) ENGINE=InnoDB;

-- Creazione tabella Genitore_Alunno
CREATE TABLE Genitore_Alunno (
                                 genitore_id INT NOT NULL,
                                 alunno_id INT NOT NULL,
                                 PRIMARY KEY (genitore_id, alunno_id),
                                 FOREIGN KEY (genitore_id) REFERENCES Persona(id) ON DELETE CASCADE,
                                 FOREIGN KEY (alunno_id) REFERENCES Persona(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Indirizzo
INSERT INTO Indirizzo (nome) VALUES
                                 ('Via Roma 1'),
                                 ('Via Milano 2'),
                                 ('Corso Torino 3');

-- Articolazione
INSERT INTO Articolazione (nome, indirizzo_id) VALUES
                                                   ('Scientifico', 1),
                                                   ('Classico', 2),
                                                   ('Tecnico', 3);

-- Classe
INSERT INTO Classe (nome, articolazione_id) VALUES
                                                ('1A Scientifico', 1),
                                                ('2B Classico', 2),
                                                ('3C Tecnico', 3);

-- Materia
INSERT INTO Materia (nome) VALUES
                               ('Matematica'),
                               ('Italiano'),
                               ('Storia'),
                               ('Inglese');

-- Persona
INSERT INTO Persona (tipo, nome) VALUES
                                     ('Docente', 'Mario Rossi'),
                                     ('Docente', 'Giulia Bianchi'),
                                     ('Alunno', 'Luca Verdi'),
                                     ('Alunno', 'Sofia Russo'),
                                     ('Genitore', 'Marco Neri');

-- Credenziali (password: 'password' per tutti)
INSERT INTO Credenziali (username, password, persona_id) VALUES
                                                             ('mrossi', '$2y$10$e0N1Z1Z1Z1Z1Z1Z1Z1Z1Z1u', 1),
                                                             ('gbianchi', '$2y$10$e0N1Z1Z1Z1Z1Z1Z1Z1Z1Z1u', 2),
                                                             ('lverdi', '$2y$10$e0N1Z1Z1Z1Z1Z1Z1Z1Z1Z1u', 3),
                                                             ('srusso', '$2y$10$e0N1Z1Z1Z1Z1Z1Z1Z1Z1Z1u', 4),
                                                             ('mneri', '$2y$10$e0N1Z1Z1Z1Z1Z1Z1Z1Z1Z1u', 5);

-- Piano_Di_Studio
INSERT INTO Piano_Di_Studio (classe_id, materia_id, docente_id) VALUES
                                                                    (1, 1, 1), (1, 2, 1), (1, 4, 1),
                                                                    (2, 2, 2), (2, 3, 2),
                                                                    (3, 1, 1), (3, 4, 2);

-- Iscrizione
INSERT INTO Iscrizione (alunno_id, classe_id, anno_scolastico) VALUES
                                                                   (3, 1, 2023),
                                                                   (4, 2, 2023);

-- Genitore_Alunno
INSERT INTO Genitore_Alunno (genitore_id, alunno_id) VALUES
                                                         (5, 3),
                                                         (5, 4);