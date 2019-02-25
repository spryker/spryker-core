<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\Translator;

use Symfony\Component\Translation\Translator as SymfonyTranslator;

class Translator extends SymfonyTranslator implements TranslatorInterface
{
    /**
     * @var string
     */
    protected static $locale;

    /**
     * @param string $locale
     *
     * @return void
     */
    public function setLocale($locale): void
    {
        $this->assertValidLocale($locale);
        static::$locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return static::$locale;
    }
}
