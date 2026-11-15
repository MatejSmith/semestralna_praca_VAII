<?php

namespace App\Controllers;

use App\Models\Kategoria;
use App\Models\Polozka;
use Framework\Core\BaseController;
use Framework\Http\HttpException;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

/**
 * Class KategoriaController
 * Správa kategórií vybavenia (CRUD nad entitou Kategoria cez vstavané ORM frameworku).
 *
 * Kategória je v spojení 1:N s položkami (Polozka.kategoria_id). Vo formulári kategórie sa dá nastaviť,
 * ktoré položky do nej patria, a v detaile sa zobrazujú jej položky.
 *
 * Akcie:
 *  - index   (GET)  zoznam kategórií s počtom položiek
 *  - detail  (GET)  kategória a jej položky (?id=)
 *  - create  (GET)  prázdny formulár
 *  - edit    (GET)  formulár s údajmi kategórie a výberom položiek (?id=)
 *  - save    (POST) uloží novú alebo upravenú kategóriu aj priradenie položiek
 *  - delete  (POST) zmaže kategóriu (jej položky ostanú, len bez kategórie)
 *
 * @package App\Controllers
 */
class KategoriaController extends BaseController
{
    public function index(Request $request): Response
    {
        $stav = $request->get('stav');

        return $this->html([
            'kategorie' => Kategoria::getAll(orderBy: '`nazov` ASC'),
            'stav' => is_string($stav) ? $stav : null,
        ]);
    }

    public function detail(Request $request): Response
    {
        $kategoria = $this->nacitajKategoriu($request->get('id'));

        return $this->html([
            'kategoria' => $kategoria,
            'polozky' => $kategoria->polozky(),
        ]);
    }

    public function create(Request $request): Response
    {
        return $this->formular(new Kategoria(), [], []);
    }

    public function edit(Request $request): Response
    {
        $kategoria = $this->nacitajKategoriu($request->get('id'));
        $vybrane = array_map(fn(Polozka $p) => (int)$p->id, $kategoria->polozky());

        return $this->formular($kategoria, $vybrane, []);
    }

    /**
     * Uloží kategóriu (Create / Update) a podľa zaškrtnutých položiek upraví ich cudzí kľúč.
     */
    public function save(Request $request): Response
    {
        $this->vyzadujPost($request);

        $id = $request->post('id');
        $jeNova = ($id === null || $id === '');
        $kategoria = $jeNova ? new Kategoria() : $this->nacitajKategoriu($id);

        $kategoria->setFromRequest($request);
        $kategoria->nazov = trim((string)$kategoria->nazov);
        $kategoria->popis = trim((string)$kategoria->popis);
        if ($kategoria->popis === '') {
            $kategoria->popis = null;
        }

        // Vybrané položky z formulára (pole id), ostatné hodnoty sa ignorujú
        $vybrane = [];
        $post = $request->post('polozky');
        if (is_array($post)) {
            foreach ($post as $hodnota) {
                if (is_string($hodnota) && ctype_digit($hodnota)) {
                    $vybrane[] = (int)$hodnota;
                }
            }
        }

        $errors = $this->validuj($kategoria);
        if ($errors !== []) {
            return $this->formular($kategoria, $vybrane, $errors);
        }

        $kategoria->save();

        // Úprava prepojení: vybrané položky patria do tejto kategórie, z ostatných sa kategória odoberie
        foreach (Polozka::getAll() as $polozka) {
            $patri = ((string)$polozka->kategoria_id === (string)$kategoria->id);
            $ma = in_array((int)$polozka->id, $vybrane, true);
            if ($ma && !$patri) {
                $polozka->kategoria_id = $kategoria->id;
                $polozka->save();
            } elseif (!$ma && $patri) {
                $polozka->kategoria_id = null;
                $polozka->save();
            }
        }

        return $this->presmerujNaZoznam($jeNova ? 'vytvorene' : 'upravene');
    }

    /**
     * Zmaže kategóriu. Cudzí kľúč s ON DELETE SET NULL zabezpečí, že položky ostanú bez kategórie.
     */
    public function delete(Request $request): Response
    {
        $this->vyzadujPost($request);

        $this->nacitajKategoriu($request->post('id'))->delete();

        return $this->presmerujNaZoznam('zmazane');
    }

    // ------------------------------------------------------------------
    // Pomocné metódy
    // ------------------------------------------------------------------

    private function formular(Kategoria $kategoria, array $vybrane, array $errors): Response
    {
        return $this->html([
            'kategoria' => $kategoria,
            'polozky' => Polozka::getAll(orderBy: '`nazov` ASC'),
            'vybrane' => $vybrane,
            'errors' => $errors,
        ], 'form');
    }

    private function nacitajKategoriu(mixed $id): Kategoria
    {
        if (!is_string($id) || !ctype_digit($id)) {
            throw new HttpException(404, 'Kategória nebola nájdená.');
        }

        $kategoria = Kategoria::getOne((int)$id);
        if ($kategoria === null) {
            throw new HttpException(404, 'Kategória nebola nájdená.');
        }

        return $kategoria;
    }

    private function vyzadujPost(Request $request): void
    {
        if (!$request->isPost()) {
            throw new HttpException(405);
        }
    }

    private function presmerujNaZoznam(string $stav): Response
    {
        return $this->redirect($this->url('kategoria.index', ['stav' => $stav]))->setStatusCode(303);
    }

    /**
     * @return string[]
     */
    private function validuj(Kategoria $kategoria): array
    {
        $errors = [];

        if ($kategoria->nazov === '') {
            $errors[] = 'Názov je povinný.';
        } elseif (mb_strlen($kategoria->nazov) > 100) {
            $errors[] = 'Názov môže mať najviac 100 znakov.';
        }

        if (mb_strlen((string)$kategoria->popis) > 500) {
            $errors[] = 'Popis môže mať najviac 500 znakov.';
        }

        return $errors;
    }
}
