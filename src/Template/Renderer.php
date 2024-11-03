<?php declare(strict_types = 1);

namespace NTeste\Template;

interface Renderer
{
    public function render($template, $data = []) : string;
}