<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\Translator;

use Spryker\Zed\Translator\Business\Finder\TranslationFinderInterface;
use Symfony\Component\Translation\TranslatorInterface as SymfonyTranslatorInterface;

interface TranslatorInterface extends SymfonyTranslatorInterface
{
    /**
     * @param \Spryker\Zed\Translator\Business\Finder\TranslationFinderInterface $translationFinder
     *
     * @return void
     */
    public function setLazyLoadResources(TranslationFinderInterface $translationFinder): void;

    /**
     * @param array $locales
     *
     * @return void
     */
    public function generateCache(array $locales): void;
}
