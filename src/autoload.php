<?php

namespace FluxMarkdownToHtmlConverterRestApi;

require_once __DIR__ . "/../../commonmark/vendor/autoload.php";

require_once __DIR__ . "/../../flux-rest-api/autoload.php";

spl_autoload_register(function (string $class) : void {
    if (str_starts_with($class, __NAMESPACE__ . "\\")) {
        require_once __DIR__ . str_replace("\\", "/", substr($class, strlen(__NAMESPACE__))) . ".php";
    }
});
