<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Communication\Plugin;

use Spryker\Zed\ApplicationExtension\Dependency\Plugin\TwigTranslatorExtensionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig_Environment;

/**
 * @method \Spryker\Zed\Translator\Communication\TranslatorCommunicationFactory getFactory()
 * @method \Spryker\Zed\Translator\Business\TranslatorFacadeInterface getFacade()
 */
class TranslatorTwigExtensionPlugin extends AbstractPlugin implements TwigTranslatorExtensionPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Twig_Environment $twig
     *
     * @return void
     */
    public function addTranslatorExtension(Twig_Environment $twig): void
    {
        $this->getFacade()->registerTwigTranslator($twig);
    }
}
