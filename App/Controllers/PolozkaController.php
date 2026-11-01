<?php

namespace App\Controllers;

use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

/**
 * Class PolozkaController
 * Stránky súvisiace s vybavením na požičanie (entita Polozka).
 *
 * Pozn.: súbor bol vygenerovaný pomocou AI (Claude) a upravený podľa zadania.
 *
 * @package App\Controllers
 */
class PolozkaController extends BaseController
{
    /**
     * Katalóg zatiaľ nie je implementovaný, preto presmeruje na úvodnú stránku.
     */
    public function index(Request $request): Response
    {
        return $this->redirect($this->url('home.index'));
    }

    /**
     * Detail vybavenia s rezerváciou (pohľad App/Views/Polozka/detail.view.php).
     * Obsah je zatiaľ statický, dáta z databázy sa pridajú v ďalších úlohách.
     */
    public function detail(Request $request): Response
    {
        return $this->html();
    }
}
