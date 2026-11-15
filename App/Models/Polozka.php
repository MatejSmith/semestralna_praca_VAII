<?php

namespace App\Models;

use Framework\Core\Model;

/**
 * Class Polozka
 *
 * Model (entita) vybavenia, ktoré si zákazníci môžu požičať. Využíva vstavané ORM frameworku Vaííčko,
 * takže CRUD operácie zabezpečujú metódy zdedené z triedy Model:
 *  - Polozka::getAll(), Polozka::getOne($id)   (Read)
 *  - $polozka->save()                          (Create / Update)
 *  - $polozka->delete()                        (Delete)
 *
 * Názvy vlastností sa zhodujú s názvami stĺpcov v tabuľke `polozky` (pozri docker/sql/sportrent.sql).
 *
 * @package App\Models
 */
class Polozka extends Model
{
    /** Názov tabuľky nastavujeme explicitne, framework by ho inak odvodil z anglického množného čísla ("polozkas"). */
    protected static ?string $tableName = 'polozky';

    /**
     * Vlastnosti, ktoré možno nastaviť z požiadavky cez setFromRequest().
     * Primárny kľúč a `aktivna` (checkbox) sa nenastavujú hromadne.
     */
    protected static ?array $fillable = ['kategoria_id', 'nazov', 'popis', 'cena_za_den', 'pocet_kusov'];

    // Vlastnosti, ktoré sa načítajú z databázy, majú typ mixed: PDO vracia z MariaDB hodnoty ako reťazce
    // a vstup z formulára sa overuje až v kontroléri, takže TypeError pri nesprávnom vstupe nehrozí.
    public mixed $id = null;
    public mixed $kategoria_id = null;
    public ?string $nazov = null;
    public ?string $popis = null;
    public mixed $cena_za_den = null;
    public mixed $pocet_kusov = 1;
    public mixed $aktivna = 1;

    /**
     * Kategória, do ktorej položka patrí (strana "N" vzťahu 1:N), alebo null, ak nie je zaradená.
     */
    public function kategoria(): ?Kategoria
    {
        if ($this->kategoria_id === null || $this->kategoria_id === '') {
            return null;
        }
        return $this->getOneRelated(Kategoria::class, 'kategoria_id');
    }
}
