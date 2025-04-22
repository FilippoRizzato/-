CREATE TABLE Persona (

                         ID_Persona INT PRIMARY KEY,

                         Nome VARCHAR(100),

                         Cognome VARCHAR(100),

                         Data_di_nascita DATE

);
CREATE TABLE Studente (
                          ID_Studente INT PRIMARY KEY,
                          USER VARCHAR(100),
                          PWD VARCHAR(100),
                          FOREIGN KEY (ID_Studente) REFERENCES Persona(ID_Persona)
);

CREATE TABLE Genitore (
                          ID_Genitore INT PRIMARY KEY,
                          USER VARCHAR(100),
                          PWD VARCHAR(100),
                          FOREIGN KEY (ID_Genitore) REFERENCES Persona(ID_Persona)
);

CREATE TABLE Docente (
                         ID_Docente INT PRIMARY KEY,
                         USER VARCHAR(100),
                         PWD VARCHAR(100),
                         FOREIGN KEY (ID_Docente) REFERENCES Persona(ID_Persona)
);
INSERT INTO Persona (ID_Persona, Nome, Cognome, Data_di_nascita) VALUES
                                                                     (1, 'Mario', 'Rossi', '1990-01-15'),
                                                                     (2, 'Luca', 'Bianchi', '1985-03-22'),
                                                                     (3, 'Anna', 'Verdi', '1992-07-30'),
                                                                     (4, 'Giulia', 'Neri', '2000-12-05');

INSERT INTO Studente (ID_Studente, USER, PWD) VALUES
                                                  (1, 'marioR', 'password1'),
                                                  (2, 'lucaB', 'password2'),
                                                  (3, 'annaV', 'password3');

INSERT INTO Genitore (ID_Genitore, USER, PWD) VALUES
                                                  (1, 'padreM', 'passPadre1'),
                                                  (2, 'madreL', 'passMadre2');

INSERT INTO Docente (ID_Docente, USER, PWD) VALUES
                                                (1, 'profG', 'passwordP'),
                                                (2, 'docB', 'passwordD');
