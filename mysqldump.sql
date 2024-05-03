-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema lagdatabase
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `lagdatabase` ;
USE `lagdatabase` ;

-- -----------------------------------------------------
-- Table `lagdatabase`.`arrangement`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lagdatabase`.`arrangement` (
  `IDarrangement` INT(11) NOT NULL AUTO_INCREMENT,
  `Tittel` LONGTEXT NOT NULL,
  `Spill` VARCHAR(100) NOT NULL,
  `beskrivelse` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`IDarrangement`))
ENGINE = InnoDB
AUTO_INCREMENT = 6;


-- -----------------------------------------------------
-- Table `lagdatabase`.`brukere`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lagdatabase`.`brukere` (
  `IDbruker` INT(11) NOT NULL AUTO_INCREMENT,
  `passord` LONGTEXT NOT NULL,
  `Admin` INT(11) NOT NULL,
  `brukernavn` VARCHAR(255) NOT NULL,
  `navn` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`IDbruker`),
  UNIQUE INDEX `brukernavn_UNIQUE` (`brukernavn` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 8;


-- -----------------------------------------------------
-- Table `lagdatabase`.`brukere_has_arrangement`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lagdatabase`.`brukere_has_arrangement` (
  `Brukere_IDbruker` INT(11) NOT NULL,
  `Arrangement_IDarrangement` INT(11) NOT NULL,
  `turnerings_plass` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Brukere_IDbruker`, `Arrangement_IDarrangement`),
  INDEX `fk_Brukere_has_Arrangement_Arrangement1_idx` (`Arrangement_IDarrangement` ASC),
  INDEX `fk_Brukere_has_Arrangement_Brukere_idx` (`Brukere_IDbruker` ASC),
  CONSTRAINT `fk_Brukere_has_Arrangement_Arrangement1`
    FOREIGN KEY (`Arrangement_IDarrangement`)
    REFERENCES `lagdatabase`.`arrangement` (`IDarrangement`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Brukere_has_Arrangement_Brukere`
    FOREIGN KEY (`Brukere_IDbruker`)
    REFERENCES `lagdatabase`.`brukere` (`IDbruker`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

INSERT INTO `brukere` (`passord`, `Admin`, `brukernavn`, `navn`) VALUES ('Admin', '1', 'Admin', 'Admin');

