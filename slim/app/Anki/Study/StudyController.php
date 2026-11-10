<?php
declare(strict_types=1);

namespace App\Anki\Study;

use App\Utilities\View;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class StudyController
{
    private const TEMPLATE_SHOW = 'anki/study/show.latte';

    public function __construct(
        private readonly View $view,
    ) {}

    public function show(Request $req, Response $res): Response
    {
        return $this->view->page($req, $res, self::TEMPLATE_SHOW);
    }
}