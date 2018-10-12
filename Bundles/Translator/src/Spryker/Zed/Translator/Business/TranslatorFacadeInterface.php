<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business;

use Twig_Environment;

interface TranslatorFacadeInterface
{
    /**
     * Specification:
     * - Generates translation cache for Zed for all store's locales.
     *
     * @api
     *
     * @return void
     */
    public function generateTranslationCache(): void;

    /**
     * Specification:
     * - Clears Zed's translation cache.
     *
     * @api
     *
     * @return void
     */
    public function clearTranslationCache(): void;

    /**
     * Specification:
     * - Add Translator as Twig Translation extension
     *
     * @api
     *
     * @param \Twig_Environment $twig
     *
     * @return void
     */
    public function registerTwigTranslator(Twig_Environment $twig): void;
}
