<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Twig_Environment;

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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Twig_Environment $twig
     *
     * @return void
     */
    public function registerTwigTranslator(Twig_Environment $twig): void
    {
        $this->getFactory()->createTranslator()->addAsTwigExtension($twig);
    }
}
