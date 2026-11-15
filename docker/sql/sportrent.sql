-- ============================================================
-- SportRent – SQL skript na vytvorenie tabuliek (importuje ho Docker pri štarte)
-- Entity: Kategoria (kategória vybavenia) -> tabuľka `kategorie`
--         Polozka   (vybavenie na požičanie) -> tabuľka `polozky`
-- Vzťah 1:N: jedna kategória môže mať viac položiek (`polozky`.`kategoria_id` -> `kategorie`.`id`).
-- Pri zmazaní kategórie ostanú položky v databáze a len sa im vynuluje kategória (ON DELETE SET NULL).
-- ============================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `kategorie` (
    `id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nazov` VARCHAR(100) NOT NULL,
    `popis` VARCHAR(500) NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `polozky` (
    `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `kategoria_id` INT UNSIGNED  NULL,
    `nazov`        VARCHAR(150)  NOT NULL,
    `popis`        TEXT          NULL,
    `cena_za_den`  DECIMAL(8, 2) NOT NULL,
    `pocet_kusov`  INT UNSIGNED  NOT NULL DEFAULT 1,
    `aktivna`      TINYINT(1)    NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    KEY `idx_polozky_kategoria` (`kategoria_id`),
    CONSTRAINT `fk_polozky_kategoria` FOREIGN KEY (`kategoria_id`)
        REFERENCES `kategorie` (`id`)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Ukážkové dáta
INSERT INTO `kategorie` (`id`, `nazov`, `popis`) VALUES
    (1, 'Bicykle', 'Horské, mestské a trekingové bicykle.'),
    (2, 'Zimné športy', 'Lyže, snowboardy a súvisiace vybavenie.'),
    (3, 'Beh a fitness', 'Športové hodinky, bežecké a fitness pomôcky.'),
    (4, 'Turistika', 'Batohy, palice a ďalšie vybavenie na túry.'),
    (5, 'Kemping', 'Stany, spacie vaky a kempingové potreby.');

-- Ukážkové dáta
INSERT INTO `polozky` (`kategoria_id`, `nazov`, `popis`, `cena_za_den`, `pocet_kusov`, `aktivna`) VALUES
    (1, 'Horský bicykel Trek Marlin 7',
     'Univerzálny horský bicykel pre rekreačných jazdcov. Veľkosť rámu M, 29" kolesá, 21 prevodov, hydraulické kotúčové brzdy. Prilba a zámok sú k dispozícii za príplatok.',
     12.00, 3, 1),
    (1, 'Mestský bicykel Dema Elegant',
     'Pohodlný mestský bicykel s nízkym nástupom, košíkom a svetlami. Vhodný na krátke výlety po meste.',
     9.00, 4, 1),
    (2, 'Lyže Atomic Redster S9 + palice',
     'Pretekárske carvingové lyže, dĺžka 170 cm. V cene sú palice. Viazanie nastavíme podľa vašej topánky.',
     25.00, 2, 1),
    (2, 'Snowboard Burton Custom 158',
     'Univerzálny freeride snowboard, dĺžka 158 cm. Viazanie a topánky sa požičiavajú samostatne.',
     22.00, 2, 1),
    (3, 'Bežecké hodinky Garmin Forerunner',
     'Športové hodinky s GPS a meraním tepu. Ideálne na vyskúšanie pred kúpou.',
     6.50, 5, 1),
    (4, 'Turistický batoh Osprey 45 l',
     'Ergonomický batoh s nastaviteľným chrbtom a pláštenkou. Vhodný na viacdňové túry.',
     7.00, 6, 1),
    (5, 'Stan Husky Bright 3',
     'Trojmiestny stan s predsieňou, nepremokavosť 5000 mm. Pripravený na okamžité použitie.',
     15.00, 2, 1),
    (5, 'Spací vak Pinguin Expert',
     'Zimný spací vak do -15 °C. Momentálne vyradený z ponuky (na servise).',
     8.00, 3, 0);
