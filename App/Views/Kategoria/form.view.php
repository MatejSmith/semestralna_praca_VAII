<?php

/**
 * Formulár na pridanie (Create) a úpravu (Update) kategórie vrátane výberu položiek, ktoré do nej patria.
 */

/** @var \App\Models\Kategoria $kategoria */
/** @var \App\Models\Polozka[] $polozky */
/** @var int[] $vybrane */
/** @var string[] $errors */
/** @var \Framework\Support\LinkGenerator $link */

$jeNova = ($kategoria->id === null || $kategoria->id === '');
?>
<nav class="breadcrumb" aria-label="Omrvinková navigácia">
    <ol>
        <li><a href="<?= $link->url('kategoria.index') ?>">Kategórie</a></li>
        <li aria-current="page"><strong><?= $jeNova ? 'Nová kategória' : 'Úprava kategórie' ?></strong></li>
    </ol>
</nav>

<section class="form-page" aria-labelledby="formular-nadpis">
    <h1 id="formular-nadpis"><?= $jeNova ? 'Pridať kategóriu' : 'Upraviť kategóriu' ?></h1>

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

    <form class="card" method="post" action="<?= $link->url('kategoria.save') ?>">
        <?php if (!$jeNova) { ?>
            <input type="hidden" name="id" value="<?= (int)$kategoria->id ?>">
        <?php } ?>

        <div class="form-field">
            <label for="nazov">Názov</label>
            <input type="text" id="nazov" name="nazov" maxlength="100" required value="<?= e($kategoria->nazov) ?>">
        </div>

        <div class="form-field">
            <label for="popis">Popis</label>
            <textarea id="popis" name="popis" rows="3" maxlength="500"><?= e($kategoria->popis) ?></textarea>
        </div>

        <fieldset class="check-list">
            <legend>Vybavenie v tejto kategórii</legend>
            <?php if ($polozky === []) { ?>
                <p class="hint">Zatiaľ nie je v katalógu žiadne vybavenie.</p>
            <?php } ?>
            <?php foreach ($polozky as $polozka) { ?>
                <div class="form-check">
                    <input type="checkbox" id="polozka-<?= (int)$polozka->id ?>" name="polozky[]"
                           value="<?= (int)$polozka->id ?>"<?= in_array((int)$polozka->id, $vybrane, true) ? ' checked' : '' ?>>
                    <label for="polozka-<?= (int)$polozka->id ?>">
                        <?= e($polozka->nazov) ?>
                        <?php $ina = $polozka->kategoria(); ?>
                        <?php if ($ina !== null && (string)$ina->id !== (string)$kategoria->id) { ?>
                            <small>(teraz v kategórii: <?= e($ina->nazov) ?>)</small>
                        <?php } ?>
                    </label>
                </div>
            <?php } ?>
        </fieldset>

        <div class="actions">
            <button type="submit" class="btn btn-primary"><?= $jeNova ? 'Pridať' : 'Uložiť zmeny' ?></button>
            <a class="btn" href="<?= $link->url('kategoria.index') ?>">Zrušiť</a>
        </div>
    </form>
</section>
