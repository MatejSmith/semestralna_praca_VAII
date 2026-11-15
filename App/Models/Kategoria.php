<?php

namespace App\Models;

use Framework\Core\Model;

/**
 * Class Kategoria
 *
 * Kategória vybavenia (napr. Bicykle, Kemping). Jedna kategória môže obsahovať viac položiek (vzťah 1:N),
 * cudzí kľúč je v tabuľke `polozky` (stĺpec `kategoria_id`).
 *
 * @package App\Models
 */
class Kategoria extends Model
{
    /** Názov tabuľky nastavujeme explicitne, framework by ho inak odvodil z anglického množného čísla. */
    protected static ?string $tableName = 'kategorie';

    protected static ?array $fillable = ['nazov', 'popis'];

    public mixed $id = null;
    public ?string $nazov = null;
    public ?string $popis = null;

    /**
     * Všetky položky patriace do tejto kategórie (strana "1" vzťahu 1:N).
     *
     * @return Polozka[]
     */
    public function polozky(): array
    {
        return $this->getAllRelated(Polozka::class, 'kategoria_id');
    }
}
