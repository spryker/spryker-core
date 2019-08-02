<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Translator\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Translator\TranslatorFactory getFactory()
 * @method \Spryker\Yves\Translator\TranslatorConfig getConfig()
 */
class TranslatorApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    protected const SERVICE_TRANSLATOR = 'translator';

    /**
     * Added for BC reason only.
     */
    protected const BC_FEATURE_FLAG_TWIG_TRANSLATOR = 'BC_FEATURE_FLAG_TWIG_TRANSLATOR';

    /**
     * {@inheritdoc}
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
            return $this->getFactory()->createTranslator();
        });

        return $container;
    }
}
