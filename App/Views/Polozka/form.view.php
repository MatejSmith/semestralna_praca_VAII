<?php

/**
 * Formulár na pridanie (Create) a úpravu (Update) vybavenia.
 * Ten istý pohľad sa používa pre akcie create, edit a pre opätovné zobrazenie po chybe v save.
 */

use App\Models\Polozka;

/** @var Polozka $polozka */
/** @var string[] $errors */
/** @var \Framework\Support\LinkGenerator $link */

$jeNova = ($polozka->id === null || $polozka->id === '');
?>
<nav class="breadcrumb" aria-label="Omrvinková navigácia">
    <ol>
        <li><a href="<?= $link->url('polozka.index') ?>">Katalóg</a></li>
        <li aria-current="page"><strong><?= $jeNova ? 'Nové vybavenie' : 'Úprava vybavenia' ?></strong></li>
    </ol>
</nav>

<section class="form-page" aria-labelledby="formular-nadpis">
    <h1 id="formular-nadpis"><?= $jeNova ? 'Pridať vybavenie' : 'Upraviť vybavenie' ?></h1>

    <?php if ($errors !== []) { ?>
        <div class="alert alert-error" role="alert">
            <strong>Formulár obsahuje chyby:</strong>
            <ul>
                <?php foreach ($errors as $error) { ?>
                    <li><?= e($error) ?></li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>

    <form class="card" method="post" action="<?= $link->url('polozka.save') ?>">
        <?php if (!$jeNova) { ?>
            <input type="hidden" name="id" value="<?= (int)$polozka->id ?>">
        <?php } ?>

        <div class="form-field">
            <label for="nazov">Názov</label>
            <input type="text" id="nazov" name="nazov" maxlength="150" required
                   value="<?= e($polozka->nazov) ?>">
        </div>

        <div class="form-field">
            <label for="kategoria">Kategória</label>
            <select id="kategoria" name="kategoria" required>
                <option value="">– vyberte –</option>
                <?php foreach (Polozka::KATEGORIE as $kategoria) { ?>
                    <option value="<?= e($kategoria) ?>"<?= $polozka->kategoria === $kategoria ? ' selected' : '' ?>>
                        <?= e($kategoria) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-field">
            <label for="popis">Popis</label>
            <textarea id="popis" name="popis" rows="5" maxlength="2000"><?= e($polozka->popis) ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-field">
                <label for="cena_za_den">Cena za deň (€)</label>
                <input type="number" id="cena_za_den" name="cena_za_den" min="0" step="0.01" required
                       value="<?= e($polozka->cena_za_den) ?>">
            </div>
            <div class="form-field">
                <label for="pocet_kusov">Počet kusov</label>
                <input type="number" id="pocet_kusov" name="pocet_kusov" min="1" max="1000" step="1" required
                       value="<?= e($polozka->pocet_kusov) ?>">
            </div>
        </div>

        <div class="form-field form-check">
            <input type="checkbox" id="aktivna" name="aktivna" value="1"<?= $polozka->aktivna ? ' checked' : '' ?>>
            <label for="aktivna">Aktívna (zobrazuje sa v ponuke)</label>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary"><?= $jeNova ? 'Pridať' : 'Uložiť zmeny' ?></button>
            <a class="btn" href="<?= $link->url('polozka.index') ?>">Zrušiť</a>
        </div>
    </form>
</section>
