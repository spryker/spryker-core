<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Translator\Communication\TranslatorCommunicationFactory getFactory()
 * @method \Spryker\Zed\Translator\Business\TranslatorFacadeInterface getFacade()
 * @method \Spryker\Zed\Translator\TranslatorConfig getConfig()
 */
class TranslatorApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    protected const SERVICE_TRANSLATOR = 'translator';

    /**
     * Added for BC reason only.
     */
    protected const BC_FEATURE_FLAG_TWIG_TRANSLATOR = 'BC_FEATURE_FLAG_TWIG_TRANSLATOR';

    /**
     * {@inheritDoc}
     * - Adds `translator` service.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::BC_FEATURE_FLAG_TWIG_TRANSLATOR, false);
        $container->set(static::SERVICE_TRANSLATOR, function () {
            return $this->getFactory()->getTranslatorPlugin();
        });

        return $container;
    }
}
