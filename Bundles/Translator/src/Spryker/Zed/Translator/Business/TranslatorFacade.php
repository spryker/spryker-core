<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business;

use Generated\Shared\Transfer\LocaleTransfer;
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
        $factory = $this->getFactory();

        $translator = $factory->createTranslator();
        $locales = $factory->getStore()->getLocales();

        $translator->generateCache($locales);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function clearTranslationCache(): void
    {
        $this->getFactory()->createCacheClearer()->clearCache();
    }

    /**
     * @api
     *
     * @param string $keyName
     *
     * @return bool
     */
    public function hasTranslation($keyName)
    {
        return $this->getFactory()->createKeyManager()->hasKey($keyName);
    }

    /**
     * @api
     *
     * @param string $keyName
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return string
     */
    public function translate($keyName, array $data = [], ?LocaleTransfer $localeTransfer = null)
    {
         $translator = $this->getFactory()->createTranslator();
         $locale = $localeTransfer ? $localeTransfer->getLocaleName() : null;

         return $translator->trans($keyName, $data, $locale);
    }
}
