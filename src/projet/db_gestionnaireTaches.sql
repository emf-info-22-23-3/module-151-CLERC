-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema bd_gestionnaireTaches
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema bd_gestionnaireTaches
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `bd_gestionnaireTaches` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin ;
USE `bd_gestionnaireTaches` ;

-- -----------------------------------------------------
-- Table `bd_gestionnaireTaches`.`t_utilisateur`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_gestionnaireTaches`.`t_utilisateur` (
  `pk_utilisateur` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(30) NOT NULL,
  `prenom` VARCHAR(20) NOT NULL,
  `login` VARCHAR(20) NOT NULL,
  `password` VARCHAR(40) NOT NULL,
  PRIMARY KEY (`pk_utilisateur`),
  UNIQUE INDEX `login_UNIQUE` (`login` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `bd_gestionnaireTaches`.`t_tache`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_gestionnaireTaches`.`t_tache` (
  `pk_tache` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(100) NOT NULL,
  `date_creation` DATETIME NOT NULL,
  `date_echeance` DATETIME NULL DEFAULT NULL,
  `categorie` ENUM('todo', 'inprogress', 'totest', 'validated') NOT NULL,
  `priorite` ENUM('basse', 'moyenne', 'haute', 'urgente') NOT NULL,
  `fk_utilisateur_tache` INT NOT NULL,
  PRIMARY KEY (`pk_tache`),
  INDEX `fk_utilisateur_tache_idx` (`fk_utilisateur_tache` ASC) VISIBLE,
  CONSTRAINT `fk_utilisateur_tache`
    FOREIGN KEY (`fk_utilisateur_tache`)
    REFERENCES `bd_gestionnaireTaches`.`t_utilisateur` (`pk_utilisateur`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `bd_gestionnaireTaches`.`t_commentaire`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_gestionnaireTaches`.`t_commentaire` (
  `pk_commentaire` INT NOT NULL AUTO_INCREMENT,
  `commentaire` VARCHAR(200) NOT NULL,
  `date_creation` DATETIME NOT NULL,
  `fk_utilisateur_commentaire` INT NOT NULL,
  `fk_tache` INT NOT NULL,
  PRIMARY KEY (`pk_commentaire`),
  INDEX `fk_utilisateur_commentaire_idx` (`fk_utilisateur_commentaire` ASC) VISIBLE,
  INDEX `fk_tache_idx` (`fk_tache` ASC) VISIBLE,
  CONSTRAINT `fk_tache`
    FOREIGN KEY (`fk_tache`)
    REFERENCES `bd_gestionnaireTaches`.`t_tache` (`pk_tache`),
  CONSTRAINT `fk_utilisateur_commentaire`
    FOREIGN KEY (`fk_utilisateur_commentaire`)
    REFERENCES `bd_gestionnaireTaches`.`t_utilisateur` (`pk_utilisateur`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
