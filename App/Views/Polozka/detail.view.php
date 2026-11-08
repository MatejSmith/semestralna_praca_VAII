<?php

/**
 * Detail vybavenia s galériou, hodnoteniami a rezervačným formulárom.
 * Základné údaje položky sa načítavajú z databázy, hodnotenia a galéria sú zatiaľ statické
 * a ovládacie prvky rezervácie nie sú funkčné.
 */

/** @var \App\Models\Polozka $polozka */
/** @var \Framework\Support\LinkGenerator $link */

$cenaZaDen = (float)$polozka->cena_za_den;
$pocetKusov = max(1, (int)$polozka->pocet_kusov);
$pocetDni = 3; // ukážkový termín (statický)
?>
<nav class="breadcrumb" aria-label="Omrvinková navigácia">
    <ol>
        <li><a href="<?= $link->url('polozka.index') ?>">Katalóg</a></li>
        <li><?= e($polozka->kategoria) ?></li>
        <li aria-current="page"><strong><?= e($polozka->nazov) ?></strong></li>
    </ol>
</nav>

<div class="detail-grid">

    <!-- Galéria -->
    <section class="gallery" aria-label="Galéria fotografií">
        <h1><?= e($polozka->nazov) ?></h1>
        <div class="item-meta">
            <span class="tag"><?= e($polozka->kategoria) ?></span>
            <span class="stars" aria-hidden="true">★★★★☆</span>
            <span>4,2 (12 hodnotení)</span>
        </div>

        <div class="photo photo-main">
            Hlavná fotografia
            <button type="button" class="nav-arrow prev" aria-label="Predchádzajúca fotografia">‹</button>
            <button type="button" class="nav-arrow next" aria-label="Ďalšia fotografia">›</button>
        </div>

        <ul class="thumbs">
            <li class="photo selected">Foto 1</li>
            <li class="photo">Foto 2</li>
            <li class="photo">Foto 3</li>
            <li class="photo">Foto 4</li>
        </ul>
    </section>

    <!-- Popis a hodnotenia -->
    <article class="item-info">
        <section aria-labelledby="popis-nadpis">
            <h2 id="popis-nadpis">Popis vybavenia</h2>
            <p><?= nl2br(e($polozka->popis)) ?></p>
            <dl class="specs">
                <dt>Kategória</dt><dd><?= e($polozka->kategoria) ?></dd>
                <dt>Cena za deň</dt><dd><?= number_format($cenaZaDen, 2, ',', ' ') ?> €</dd>
                <dt>Počet kusov</dt><dd><?= $pocetKusov ?></dd>
                <dt>Stav</dt><dd><?= $polozka->aktivna ? 'Aktívna' : 'Neaktívna' ?></dd>
            </dl>
            <div class="actions">
                <a class="btn" href="<?= $link->url('polozka.edit', ['id' => $polozka->id]) ?>">Upraviť</a>
                <a class="btn" href="<?= $link->url('polozka.index') ?>">← Späť na katalóg</a>
            </div>
        </section>

        <section aria-labelledby="hodnotenia-nadpis">
            <h2 id="hodnotenia-nadpis">Hodnotenia zákazníkov</h2>

            <article class="review">
                <header>
                    <strong>Peter N.</strong>
                    <span class="stars" aria-label="5 z 5 hviezdičiek">★★★★★</span>
                </header>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Skvelý bicykel na výlety.</p>
            </article>

            <article class="review">
                <header>
                    <strong>Jana S.</strong>
                    <span class="stars" aria-label="4 z 5 hviezdičiek">★★★★☆</span>
                </header>
                <p>Sed do eiusmod tempor incididunt ut labore. Odporúčam, len brzdy by chceli nastaviť.</p>
            </article>

            <nav class="pagination" aria-label="Stránkovanie hodnotení">
                <a href="#" class="current">1</a>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#" aria-label="Ďalšia strana">›</a>
            </nav>

            <form class="card" action="#" method="post">
                <h3>Pridať hodnotenie</h3>
                <p class="hint">Dostupné len zákazníkovi, ktorý si vybavenie požičal.</p>
                <div class="form-field">
                    <label for="hviezdy">Hodnotenie</label>
                    <select id="hviezdy" name="hviezdy">
                        <option>5 – výborné</option>
                        <option>4 – veľmi dobré</option>
                        <option>3 – dobré</option>
                        <option>2 – slabé</option>
                        <option>1 – zlé</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="komentar">Komentár</label>
                    <textarea id="komentar" name="komentar"></textarea>
                </div>
                <button type="button" class="btn">Odoslať hodnotenie</button>
            </form>
        </section>
    </article>

    <!-- Rezervácia -->
    <aside class="reservation" aria-label="Rezervácia">
        <form class="card" action="#" method="post">
            <h2>Rezervácia</h2>
            <p class="price"><?= number_format($cenaZaDen, 2, ',', ' ') ?> € <small>/ deň</small></p>

            <div class="form-row">
                <div class="form-field">
                    <label for="datum-od">Dátum od</label>
                    <input type="date" id="datum-od" name="datum_od" value="2026-11-05">
                </div>
                <div class="form-field">
                    <label for="datum-do">Dátum do</label>
                    <input type="date" id="datum-do" name="datum_do" value="2026-11-08">
                </div>
            </div>

            <div class="form-field">
                <label for="pocet">Počet kusov</label>
                <input type="number" id="pocet" name="pocet" min="1" max="<?= $pocetKusov ?>" value="1">
            </div>

            <div class="availability" role="status">
                ✔ V zvolenom termíne je voľných <?= $pocetKusov ?> z <?= $pocetKusov ?> kusov
            </div>

            <div class="form-field">
                <label for="poznamka">Poznámka</label>
                <textarea id="poznamka" name="poznamka" placeholder="Voliteľná poznámka k rezervácii"></textarea>
            </div>

            <div class="total">
                <span>Celková cena (<?= $pocetDni ?> dni)</span>
                <span><?= number_format($cenaZaDen * $pocetDni, 2, ',', ' ') ?> €</span>
            </div>

            <button type="button" class="btn btn-primary btn-block">Rezervovať</button>
            <p class="hint">Rezerváciu potvrdí správca. Do schválenia ju môžete zrušiť v sekcii „Moje rezervácie“.</p>
        </form>

        <section class="card" aria-label="Obsadenosť">
            <h3>Obsadenosť</h3>
            <div class="calendar-placeholder">Kalendár obsadenosti – placeholder</div>
        </section>
    </aside>

</div>
