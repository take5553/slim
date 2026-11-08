<?php
declare(strict_types=1);

namespace App\Anki\Deck;

use App\Utilities\View;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class DeckController
{
    private const TEMPLATE_INDEX = 'anki/deck/index.latte';
    private const TEMPLATE_SHOW = 'anki/deck/show.latte';

    public function __construct(
        private readonly View $view,
    ) { }

    public function index(Request $req, Response $res): Response
    {
        return $this->view->page($req, $res, self::TEMPLATE_INDEX);
    }

    public function show(Request $req, Response $res): Response
    {
        return $this->view->page($req, $res, self::TEMPLATE_SHOW);
    }

}