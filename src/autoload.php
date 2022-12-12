<?php

namespace FluxMarkdownToHtmlConverterRestApi;

require_once __DIR__ . "/../libs/flux-autoload-api/autoload.php";
require_once __DIR__ . "/../libs/flux-markdown-to-html-converter-api/autoload.php";
require_once __DIR__ . "/../libs/flux-rest-api/autoload.php";

use FluxMarkdownToHtmlConverterRestApi\Libs\FluxAutoloadApi\Adapter\Autoload\Psr4Autoload;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxAutoloadApi\Adapter\Checker\PhpExtChecker;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxAutoloadApi\Adapter\Checker\PhpVersionChecker;

PhpVersionChecker::new(
    ">=8.2"
)
    ->checkAndDie(
        __NAMESPACE__
    );
PhpExtChecker::new(
    [
        "swoole"
    ]
)
    ->checkAndDie(
        __NAMESPACE__
    );

Psr4Autoload::new(
    [
        __NAMESPACE__ => __DIR__
    ]
)
    ->autoload();
