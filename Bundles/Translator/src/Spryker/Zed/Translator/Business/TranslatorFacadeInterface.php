<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business;

interface TranslatorFacadeInterface
{
    /**
     * Specification:
     *  - Extends Application with Translator instance.
     *
     * @api
     *
     * @return void
     */
    public function prepareTranslatorService(): void;

    /**
     * Specification:
     * - Generates translation cache for all locales of the current store.
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
    public function cleanTranslationCache(): void;
}
