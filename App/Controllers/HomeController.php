<?php

namespace App\Controllers;

use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

/**
 * Class HomeController
 * Úvodná stránka aplikácie SportRent (zatiaľ placeholder katalógu).
 *
 * Pozn.: súbor bol vygenerovaný pomocou AI (Claude) a upravený podľa zadania.
 *
 * @package App\Controllers
 */
class HomeController extends BaseController
{
    /**
     * Úvodná stránka je prístupná všetkým návštevníkom.
     */
    public function authorize(Request $request, string $action): bool
    {
        return true;
    }

    /**
     * Zobrazí úvodnú stránku (pohľad App/Views/Home/index.view.php).
     */
    public function index(Request $request): Response
    {
        return $this->html();
    }
}
