-- ============================================================
-- SportRent – SQL skript na vytvorenie tabuľky (importuje ho Docker pri štarte)
-- Entita: Polozka (vybavenie na požičanie) -> tabuľka `polozky`
-- ============================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `polozky` (
    `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `nazov`       VARCHAR(150)  NOT NULL,
    `kategoria`   VARCHAR(50)   NOT NULL,
    `popis`       TEXT          NULL,
    `cena_za_den` DECIMAL(8, 2) NOT NULL,
    `pocet_kusov` INT UNSIGNED  NOT NULL DEFAULT 1,
    `aktivna`     TINYINT(1)    NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Ukážkové dáta
INSERT INTO `polozky` (`nazov`, `kategoria`, `popis`, `cena_za_den`, `pocet_kusov`, `aktivna`) VALUES
    ('Horský bicykel Trek Marlin 7', 'Bicykle',
     'Univerzálny horský bicykel pre rekreačných jazdcov. Veľkosť rámu M, 29" kolesá, 21 prevodov, hydraulické kotúčové brzdy. Prilba a zámok sú k dispozícii za príplatok.',
     12.00, 3, 1),
    ('Mestský bicykel Dema Elegant', 'Bicykle',
     'Pohodlný mestský bicykel s nízkym nástupom, košíkom a svetlami. Vhodný na krátke výlety po meste.',
     9.00, 4, 1),
    ('Lyže Atomic Redster S9 + palice', 'Zimné športy',
     'Pretekárske carvingové lyže, dĺžka 170 cm. V cene sú palice. Viazanie nastavíme podľa vašej topánky.',
     25.00, 2, 1),
    ('Snowboard Burton Custom 158', 'Zimné športy',
     'Univerzálny freeride snowboard, dĺžka 158 cm. Viazanie a topánky sa požičiavajú samostatne.',
     22.00, 2, 1),
    ('Bežecké hodinky Garmin Forerunner', 'Beh a fitness',
     'Športové hodinky s GPS a meraním tepu. Ideálne na vyskúšanie pred kúpou.',
     6.50, 5, 1),
    ('Turistický batoh Osprey 45 l', 'Turistika',
     'Ergonomický batoh s nastaviteľným chrbtom a pláštenkou. Vhodný na viacdňové túry.',
     7.00, 6, 1),
    ('Stan Husky Bright 3', 'Kemping',
     'Trojmiestny stan s predsieňou, nepremokavosť 5000 mm. Pripravený na okamžité použitie.',
     15.00, 2, 1),
    ('Spací vak Pinguin Expert', 'Kemping',
     'Zimný spací vak do -15 °C. Momentálne vyradený z ponuky (na servise).',
     8.00, 3, 0);
