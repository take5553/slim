<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Latte\Engine;

$latte = new Engine();
$latte->setCacheDirectory(__DIR__ . '/../var/cache/latte');
$latte->setAutoRefresh(true);

$render = function (Request $req, Response $res, string $tpl, array $data = []) use ($latte): Response {
    // $isHtmx = $req->getHeaderLine('Hx-Request') === 'true'
    //         && $req->getHeaderLine('Hx-History-Resotre-Request') !== 'true';
    $html = $latte->renderToString(
        __DIR__ . '/../templates' . $tpl,
        $data,
        // $isHtmx ? 'content' : null
    );
    $res->getBody()->write($html);
    return $res->withHeader('Vary', 'Hx-Request');
};
