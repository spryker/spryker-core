<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Translator\Business\TranslatorBusinessFactory getFactory()
 */
class TranslatorFacade extends AbstractFacade implements TranslatorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function generateTranslationCache(): void
    {
        $this->getFactory()->createTranslationCacheGenerator()->generateTranslationCache();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function cleanTranslationCache(): void
    {
        $this->getFactory()->createTranslationCacheCleaner()->cleanTranslationCache();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function prepareTranslatorService(): void
    {
        $this->getFactory()->createTranslatorPreparator()->prepareTranslatorService();
    }
}
