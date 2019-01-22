<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator\Translator;

use Symfony\Component\Translation\TranslatorBagInterface as SymfonyTranslatorBagInterface;
use Symfony\Component\Translation\TranslatorInterface as SymfonyTranslatorInterface;

interface TranslatorInterface extends SymfonyTranslatorInterface, SymfonyTranslatorBagInterface
{
    /**
     * @param string $localeName
     *
     * @return void
     */
    public function generateCacheForLocale(string $localeName): void;
}
