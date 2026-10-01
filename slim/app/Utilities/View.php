<?php

namespace App\Utilities;

use Latte\Engine;
use Latte\Loaders\FileLoader;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class View
{
    private Engine $latte;

    public function __construct(string $templateDir, string $cacheDir, bool $debug = false)
    {
        if (!is_dir($cacheDir)) {
            mkdir ($cacheDir, 0775, true);
        }

        $this->latte = new Engine();
        $this->latte->setLoader(new FileLoader($templateDir));
        $this->latte->setCacheDirectory($cacheDir);
        $this->latte->setAutoRefresh($debug);
    }

    public function page(Request $req, Response $res, string $template, array $params = []): Response
    {
        $isHtmx = $req->getHeaderLine('Hx-Request') === 'true' && $req->getHeaderLine('Hx-History-Restore-Request') !== 'true';
        $html = $this->latte->renderToString($template, $params, $isHtmx ? 'content' : null);
        return $this->write($res->withHeader('Vary', 'Hx-Request'), $html);
    }

    public function fragments(Response $res, string $template, array $blocks): Response
    {
        $html = '';
        foreach($blocks as $block => $params) {
            $html .= $this->latte->renderToString($template, $params, $block);
        }
        return $this->write($res, $html);
    }

    public function write(Response $res, string $html): Response
    {
        $res->getBody()->write($html);
        return $res->withHeader('Content-Type', 'text/html; charset=UTF-8');
    }

}