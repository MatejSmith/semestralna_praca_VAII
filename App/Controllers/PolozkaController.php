<?php

namespace App\Controllers;

use App\Models\Polozka;
use Framework\Core\BaseController;
use Framework\Http\HttpException;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

/**
 * Class PolozkaController
 * Správa vybavenia na požičanie (CRUD nad entitou Polozka cez vstavané ORM frameworku).
 *
 * Akcie:
 *  - index   (GET)  zoznam všetkých položiek                  -> Views/Polozka/index.view.php   (Read)
 *  - detail  (GET)  detail jednej položky (?id=)              -> Views/Polozka/detail.view.php  (Read)
 *  - create  (GET)  prázdny formulár                          -> Views/Polozka/form.view.php
 *  - edit    (GET)  formulár s údajmi položky (?id=)          -> Views/Polozka/form.view.php
 *  - save    (POST) uloží novú alebo upravenú položku         (Create / Update)
 *  - delete  (POST) zmaže položku                             (Delete)
 *
 * Po úspešnom uložení alebo zmazaní sa používateľ presmeruje na zoznam (vzor Post/Redirect/Get),
 * takže sa vždy zobrazí aktuálny stav údajov a obnovenie stránky formulár neodošle znova.
 *
 * @package App\Controllers
 */
class PolozkaController extends BaseController
{
    /**
     * Zoznam všetkých položiek z databázy.
     */
    public function index(Request $request): Response
    {
        $stav = $request->get('stav');

        return $this->html([
            'polozky' => Polozka::getAll(orderBy: '`nazov` ASC'),
            'stav' => is_string($stav) ? $stav : null, // správa o poslednej operácii
        ]);
    }

    /**
     * Detail jednej položky (rezervačná stránka z úlohy 4, teraz s dátami z databázy).
     */
    public function detail(Request $request): Response
    {
        return $this->html([
            'polozka' => $this->nacitajPolozku($request->get('id')),
        ]);
    }

    /**
     * Prázdny formulár na pridanie novej položky.
     */
    public function create(Request $request): Response
    {
        return $this->html([
            'polozka' => new Polozka(),
            'errors' => [],
        ], 'form');
    }

    /**
     * Formulár na úpravu existujúcej položky.
     */
    public function edit(Request $request): Response
    {
        return $this->html([
            'polozka' => $this->nacitajPolozku($request->get('id')),
            'errors' => [],
        ], 'form');
    }

    /**
     * Spracuje formulár: ak je v ňom `id`, položku upraví, inak vytvorí novú.
     */
    public function save(Request $request): Response
    {
        $this->vyzadujPost($request);

        $id = $request->post('id');
        $jeNova = ($id === null || $id === '');
        $polozka = $jeNova ? new Polozka() : $this->nacitajPolozku($id);

        // Nastaví len vlastnosti uvedené v Polozka::$fillable, primárny kľúč sa nikdy neprepíše
        $polozka->setFromRequest($request);
        $polozka->aktivna = $request->post('aktivna') ? 1 : 0; // nezaškrtnutý checkbox sa v POST nenachádza

        // Základná úprava vstupu (odstránenie medzier, desatinná čiarka -> bodka)
        $polozka->nazov = trim((string)$polozka->nazov);
        $polozka->popis = trim((string)$polozka->popis);
        $polozka->cena_za_den = str_replace(',', '.', trim((string)$polozka->cena_za_den));
        $polozka->pocet_kusov = trim((string)$polozka->pocet_kusov);

        $errors = $this->validuj($polozka);
        if ($errors !== []) {
            // Formulár sa zobrazí znova so zadanými údajmi a chybami
            return $this->html(['polozka' => $polozka, 'errors' => $errors], 'form');
        }

        $polozka->save();

        return $this->presmerujNaZoznam($jeNova ? 'vytvorene' : 'upravene');
    }

    /**
     * Zmaže položku podľa `id` odoslaného cez POST.
     */
    public function delete(Request $request): Response
    {
        $this->vyzadujPost($request);

        $this->nacitajPolozku($request->post('id'))->delete();

        return $this->presmerujNaZoznam('zmazane');
    }

    // ------------------------------------------------------------------
    // Pomocné metódy (private, takže nie sú dostupné ako akcie cez URL)
    // ------------------------------------------------------------------

    /**
     * Načíta položku podľa id alebo vyhodí chybu 404, ak id nie je platné alebo položka neexistuje.
     */
    private function nacitajPolozku(mixed $id): Polozka
    {
        if (!is_string($id) || !ctype_digit($id)) {
            throw new HttpException(404, 'Položka nebola nájdená.');
        }

        $polozka = Polozka::getOne((int)$id);
        if ($polozka === null) {
            throw new HttpException(404, 'Položka nebola nájdená.');
        }

        return $polozka;
    }

    /**
     * Akcie, ktoré menia dáta, sú povolené len cez POST.
     */
    private function vyzadujPost(Request $request): void
    {
        if (!$request->isPost()) {
            throw new HttpException(405);
        }
    }

    /**
     * Presmeruje na zoznam; kód 303 zaručí, že prehliadač zopakuje požiadavku metódou GET.
     */
    private function presmerujNaZoznam(string $stav): Response
    {
        return $this->redirect($this->url('polozka.index', ['stav' => $stav]))->setStatusCode(303);
    }

    /**
     * Jednoduchá serverová validácia vstupu.
     *
     * @return string[] Chybové hlášky (prázdne pole, ak je všetko v poriadku)
     */
    private function validuj(Polozka $polozka): array
    {
        $errors = [];

        if ($polozka->nazov === '') {
            $errors[] = 'Názov je povinný.';
        } elseif (mb_strlen($polozka->nazov) > 150) {
            $errors[] = 'Názov môže mať najviac 150 znakov.';
        }

        if (!in_array($polozka->kategoria, Polozka::KATEGORIE, true)) {
            $errors[] = 'Vyberte platnú kategóriu.';
        }

        if (mb_strlen((string)$polozka->popis) > 2000) {
            $errors[] = 'Popis môže mať najviac 2000 znakov.';
        }

        if (!preg_match('/^\d{1,6}(\.\d{1,2})?$/', (string)$polozka->cena_za_den)) {
            $errors[] = 'Cena za deň musí byť nezáporné číslo (najviac 2 desatinné miesta).';
        }

        if (!ctype_digit((string)$polozka->pocet_kusov) || (int)$polozka->pocet_kusov < 1
            || (int)$polozka->pocet_kusov > 1000) {
            $errors[] = 'Počet kusov musí byť celé číslo od 1 do 1000.';
        }

        return $errors;
    }
}
