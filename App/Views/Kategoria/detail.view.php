<?php

/**
 * Detail kategórie so zoznamom položiek, ktoré do nej patria.
 */

/** @var \App\Models\Kategoria $kategoria */
/** @var \App\Models\Polozka[] $polozky */
/** @var \Framework\Support\LinkGenerator $link */
?>
<nav class="breadcrumb" aria-label="Omrvinková navigácia">
    <ol>
        <li><a href="<?= $link->url('kategoria.index') ?>">Kategórie</a></li>
        <li aria-current="page"><strong><?= e($kategoria->nazov) ?></strong></li>
    </ol>
</nav>

<section aria-labelledby="kategoria-nadpis">
    <div class="toolbar">
        <h1 id="kategoria-nadpis"><?= e($kategoria->nazov) ?></h1>
        <div class="actions">
            <a class="btn" href="<?= $link->url('kategoria.edit', ['id' => $kategoria->id]) ?>">Upraviť</a>
            <a class="btn" href="<?= $link->url('kategoria.index') ?>">← Späť na kategórie</a>
        </div>
    </div>

    <?php if ($kategoria->popis !== null && $kategoria->popis !== '') { ?>
        <p><?= nl2br(e($kategoria->popis)) ?></p>
    <?php } ?>

    <h2>Vybavenie v kategórii (<?= count($polozky) ?>)</h2>
    <?php if ($polozky === []) { ?>
        <p class="alert">V tejto kategórii zatiaľ nie je žiadne vybavenie.</p>
    <?php } else { ?>
        <table class="data-table">
            <thead>
            <tr>
                <th scope="col">Názov</th>
                <th scope="col">Cena / deň</th>
                <th scope="col">Počet kusov</th>
                <th scope="col">Stav</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($polozky as $polozka) { ?>
                <tr>
                    <td data-label="Názov">
                        <a href="<?= $link->url('polozka.detail', ['id' => $polozka->id]) ?>"><?= e($polozka->nazov) ?></a>
                    </td>
                    <td data-label="Cena / deň"><?= number_format((float)$polozka->cena_za_den, 2, ',', ' ') ?> €</td>
                    <td data-label="Počet kusov"><?= (int)$polozka->pocet_kusov ?></td>
                    <td data-label="Stav"><?= $polozka->aktivna ? 'Aktívna' : 'Neaktívna' ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</section>
