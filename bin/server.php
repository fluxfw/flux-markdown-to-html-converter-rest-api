#!/usr/bin/env php
<?php

require_once __DIR__ . "/../autoload.php";

use FluxMarkdownToHtmlConverterRestApi\Adapter\Server\MarkdownToHtmlConverterRestApiServer;

MarkdownToHtmlConverterRestApiServer::new()
    ->init();
