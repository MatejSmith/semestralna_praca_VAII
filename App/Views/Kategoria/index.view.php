<?php

/**
 * Zoznam kategórií (Read) s tlačidlami na pridanie, úpravu a zmazanie.
 */

/** @var \App\Models\Kategoria[] $kategorie */
/** @var string|null $stav */
/** @var \Framework\Support\LinkGenerator $link */

$spravy = [
    'vytvorene' => 'Kategória bola pridaná.',
    'upravene' => 'Kategória bola upravená.',
    'zmazane' => 'Kategória bola zmazaná, jej položky ostali v katalógu bez kategórie.',
];
?>
<section aria-labelledby="kategorie-nadpis">
    <div class="toolbar">
        <h1 id="kategorie-nadpis">Kategórie vybavenia</h1>
        <a class="btn btn-primary" href="<?= $link->url('kategoria.create') ?>">+ Pridať kategóriu</a>
    </div>

    <?php if ($stav !== null && isset($spravy[$stav])) { ?>
        <p class="alert alert-success" role="status"><?= e($spravy[$stav]) ?></p>
    <?php } ?>

    <?php if ($kategorie === []) { ?>
        <p class="alert">Zatiaľ nie je v databáze žiadna kategória. Pridajte prvú.</p>
    <?php } else { ?>
        <table class="data-table">
            <thead>
            <tr>
                <th scope="col">Názov</th>
                <th scope="col">Popis</th>
                <th scope="col">Počet položiek</th>
                <th scope="col">Akcie</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($kategorie as $kategoria) { ?>
                <tr>
                    <td data-label="Názov">
                        <a href="<?= $link->url('kategoria.detail', ['id' => $kategoria->id]) ?>">
                            <?= e($kategoria->nazov) ?>
                        </a>
                    </td>
                    <td data-label="Popis"><?= e((string)$kategoria->popis) ?></td>
                    <td data-label="Počet položiek"><?= count($kategoria->polozky()) ?></td>
                    <td data-label="Akcie">
                        <div class="actions">
                            <a class="btn" href="<?= $link->url('kategoria.edit', ['id' => $kategoria->id]) ?>">Upraviť</a>
                            <form method="post" action="<?= $link->url('kategoria.delete') ?>"
                                  onsubmit="return confirm('Naozaj chcete zmazať kategóriu „<?= e(addslashes($kategoria->nazov)) ?>“? Jej položky ostanú bez kategórie.');">
                                <input type="hidden" name="id" value="<?= (int)$kategoria->id ?>">
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
