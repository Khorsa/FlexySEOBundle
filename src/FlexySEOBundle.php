<?php
namespace flexycms\FlexySEOBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FlexySEOBundle extends Bundle
{
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}