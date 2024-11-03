<?php declare(strict_types = 1);

namespace NTeste\Page;

interface PageReader
{
    public function readBySlug(string $slug) : string;
}