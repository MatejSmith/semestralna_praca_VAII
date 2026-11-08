<?php

/**
 * Zoznam vybavenia (Read) s tlačidlami na pridanie, úpravu a zmazanie.
 */

/** @var \App\Models\Polozka[] $polozky */
/** @var string|null $stav */
/** @var \Framework\Support\LinkGenerator $link */

$spravy = [
    'vytvorene' => 'Položka bola pridaná.',
    'upravene' => 'Položka bola upravená.',
    'zmazane' => 'Položka bola zmazaná.',
];
?>
<section aria-labelledby="katalog-nadpis">
    <div class="toolbar">
        <h1 id="katalog-nadpis">Katalóg vybavenia</h1>
        <a class="btn btn-primary" href="<?= $link->url('polozka.create') ?>">+ Pridať vybavenie</a>
    </div>

    <?php if ($stav !== null && isset($spravy[$stav])) { ?>
        <p class="alert alert-success" role="status"><?= e($spravy[$stav]) ?></p>
    <?php } ?>

    <?php if ($polozky === []) { ?>
        <p class="alert">Zatiaľ nie je v databáze žiadne vybavenie. Pridajte prvú položku.</p>
    <?php } else { ?>
        <table class="data-table">
            <thead>
            <tr>
                <th scope="col">Názov</th>
                <th scope="col">Kategória</th>
                <th scope="col">Cena / deň</th>
                <th scope="col">Počet kusov</th>
                <th scope="col">Stav</th>
                <th scope="col">Akcie</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($polozky as $polozka) { ?>
                <tr>
                    <td data-label="Názov">
                        <a href="<?= $link->url('polozka.detail', ['id' => $polozka->id]) ?>">
                            <?= e($polozka->nazov) ?>
                        </a>
                    </td>
                    <td data-label="Kategória"><span class="tag"><?= e($polozka->kategoria) ?></span></td>
                    <td data-label="Cena / deň"><?= number_format((float)$polozka->cena_za_den, 2, ',', ' ') ?> €</td>
                    <td data-label="Počet kusov"><?= (int)$polozka->pocet_kusov ?></td>
                    <td data-label="Stav"><?= $polozka->aktivna ? 'Aktívna' : 'Neaktívna' ?></td>
                    <td data-label="Akcie">
                        <div class="actions">
                            <a class="btn" href="<?= $link->url('polozka.edit', ['id' => $polozka->id]) ?>">Upraviť</a>
                            <form method="post" action="<?= $link->url('polozka.delete') ?>"
                                  onsubmit="return confirm('Naozaj chcete zmazať položku „<?= e(addslashes($polozka->nazov)) ?>“?');">
                                <input type="hidden" name="id" value="<?= (int)$polozka->id ?>">
                                <button type="submit" class="btn btn-danger">Zmazať</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</section>
