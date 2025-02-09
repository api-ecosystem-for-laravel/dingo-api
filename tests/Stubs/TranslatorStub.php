<?php

namespace Dingo\Api\Tests\Stubs;

use Illuminate\Contracts\Translation\Translator;

class TranslatorStub implements Translator
{

    public function get($key, array $replace = [], $locale = null)
    {
        return $key;
    }

    public function choice($key, $number, array $replace = [], $locale = null)
    {
        return $key;
    }

    public function getLocale()
    {
        return 'en';
    }

    public function setLocale($locale)
    {
    }
}
