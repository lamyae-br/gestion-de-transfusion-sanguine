CREATE DATABASE IF NOT EXISTS `edoc`;
USE `edoc`;

-- Table admin
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `aemail` varchar(255) NOT NULL,
  `apassword` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`aemail`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `admin` (`aemail`, `apassword`) VALUES
('admin@edoc.com', '123');

-- Table doctor
-- Table des hôpitaux
DROP TABLE IF EXISTS `hospital`;
CREATE TABLE `hospital` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hname` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Exemples d'hôpitaux
INSERT INTO `hospital` (`hname`) VALUES
('Hôpital mere et enfant'),
('Hôpital Omar Idrissi'),
('Hôpital Ibn Al-Hassan'),
('Hôpital onconologie'),
('Hôpital des Spécialités');
 

-- Table des docteurs avec référence à l'hôpital
DROP TABLE IF EXISTS `doctor`;
CREATE TABLE `doctor` (
  `docid` int(11) NOT NULL AUTO_INCREMENT,
  `docemail` varchar(255) DEFAULT NULL,
  `docname` varchar(255) DEFAULT NULL,
  `docpassword` varchar(255) DEFAULT NULL,
  `docnic` varchar(15) DEFAULT NULL,
  `doctel` varchar(15) DEFAULT NULL,
  `specialties` int(2) DEFAULT NULL,
  `hospital` int(11) DEFAULT NULL,
  PRIMARY KEY (`docid`),
  KEY `specialties` (`specialties`),
  KEY `hospital` (`hospital`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `doctor` (docemail, docname, docpassword, docnic, doctel, specialties)
VALUES 
('doctor@edoc.com', 'Test Doctor', '123', '000000000', '0110000000', 1),
('lamyae@edoc.com', 'lamyae', '123', '000000001', '0110000001', 2);


-- Table patient
DROP TABLE IF EXISTS `patient`;
CREATE TABLE `patient` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `pemail` varchar(255) DEFAULT NULL,
  `pname` varchar(255) DEFAULT NULL,
  `ppassword` varchar(255) DEFAULT NULL,
  `paddress` varchar(255) DEFAULT NULL,
  `pnic` varchar(15) DEFAULT NULL,
  `pdob` date DEFAULT NULL,
  `ptel` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `patient` (pemail, pname, ppassword, paddress, pnic, pdob, ptel)
VALUES 
('patient@edoc.com', 'Test Patient', '123', 'Sri Lanka', '0000000000', '2000-01-01', '0120000000'),
('emhashenudara@gmail.com', 'Hashen Udara', '123', 'Sri Lanka', '0110000000', '2022-06-03', '0700000000');
-- Table schedule modifiée
DROP TABLE IF EXISTS `schedule`;
CREATE TABLE `schedule` (
  `scheduleid` int(11) NOT NULL AUTO_INCREMENT,
  `docid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `nop` int(4) DEFAULT NULL,
  `status` ENUM('active','finished','pending') DEFAULT 'pending',
  PRIMARY KEY (`scheduleid`),
  KEY `docid` (`docid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- Exemple de mise à jour : ici on dit que le docteur 1 termine sa dernière session active
UPDATE schedule 
SET end_date = CURDATE(), status = 'finished' 
WHERE docid = 1 
  AND status = 'active' 
ORDER BY start_date DESC 
LIMIT 1;



-- Exemple de données
INSERT INTO `schedule` (`docid`, `title`, `start_date`, `end_date`, `nop`)
VALUES 
(1, 'Test Session', '2050-01-01', '2050-01-01', 50),
(1, 'Consultation', '2022-06-10', '2022-06-10', 1);

-- Table user_sessions (pour gérer PHP sessions)
DROP TABLE IF EXISTS `user_sessions`;
CREATE TABLE `user_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('d','p','a') NOT NULL,
  `login_time` datetime NOT NULL,
  `last_activity` datetime NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- Table appointment
DROP TABLE IF EXISTS `appointment`;
CREATE TABLE `appointment` (
  `appoid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `apponum` int(3) DEFAULT NULL,
  `scheduleid` int(11) DEFAULT NULL,
  `appodate` date DEFAULT NULL,
  PRIMARY KEY (`appoid`),
  KEY `pid` (`pid`),
  KEY `scheduleid` (`scheduleid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `appointment` (`pid`, `apponum`, `scheduleid`, `appodate`) VALUES
(1, 1, 1, '2022-06-03');

-- Table specialties
DROP TABLE IF EXISTS `specialties`;

CREATE TABLE `specialties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sname` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `specialties` (`sname`) VALUES
('Allergologie et immunologie'),
('Anesthésiologie'),
('Cardiologie'),
('Dermatologie'),
('Médecine d’urgence'),
('Endocrinologie'),
('Médecine familiale'),
('Gastro-entérologie'),
('Gériatrie'),
('Hématologie'),
('Maladies infectieuses'),
('Médecine interne'),
('Néphrologie'),
('Neurologie'),
('Neurochirurgie'),
('Obstétrique et gynécologie'),
('Oncologie'),
('Ophtalmologie'),
('Chirurgie orthopédique'),
('ORL (Oto-rhino-laryngologie)'),
('Pédiatrie'),
('Médecine physique et réadaptation'),
('Chirurgie plastique'),
('Psychiatrie'),
('Pneumologie'),
('Radiologie'),
('Rhumatologie'),
('Chirurgie générale'),
('Urologie'),
('Chirurgie vasculaire');

-- Table webuser
DROP TABLE IF EXISTS `webuser`;
CREATE TABLE `webuser` (
  `email` varchar(255) NOT NULL,
  `usertype` char(1) DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `webuser` (`email`, `usertype`) VALUES
('admin@edoc.com', 'a'),
('doctor@edoc.com', 'd'),
('patient@edoc.com', 'p'),
('emhashenudara@gmail.com', 'p'),
('lamyae@edoc.com', 'd');

-- Table demande
DROP TABLE IF EXISTS `demande`;
CREATE TABLE `demande` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `groupe_sanguin` VARCHAR(5) NOT NULL,
  `niveau_urgence` ENUM('Faible', 'Moyenne', 'Élevée') NOT NULL,
  `quantite_demandee` INT(4) NOT NULL,
  `type_sang` VARCHAR(50) NOT NULL,
  `fichier_justificatif` VARCHAR(255) DEFAULT NULL,
  `service_concerne` VARCHAR(100) NOT NULL,
  `commentaire` TEXT DEFAULT NULL,
  `date_demande` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('En attente', 'Acceptée', 'Refusée') DEFAULT 'En attente',
  `docid` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `docid` (`docid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

