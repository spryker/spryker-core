<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Twig\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\Loader\ChainLoader;

/**
 * @method \Spryker\Yves\Twig\TwigFactory getFactory()
 * @method \Spryker\Yves\Twig\TwigConfig getConfig()
 */
class TwigApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    public const SERVICE_TWIG = 'twig';

    public const SERVICE_DEBUG = 'debug';

    public const SERVICE_CHARSET = 'charset';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addTwigService($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addTwigService(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_TWIG, function (ContainerInterface $container) {
            /** @var \Twig\Loader\LoaderInterface $twigLoader */
            $twigLoader = $this->getChainLoader();
            $twigOptions = $this->getTwigOptions($container);
            $twig = new Environment($twigLoader, $twigOptions);
            $twig->addGlobal('app', $container);

            $twig = $this->extendTwig($twig, $container);

            return $twig;
        });

        return $container;
    }

    /**
     * @return \Twig\Loader\ChainLoader
     */
    protected function getChainLoader(): ChainLoader
    {
        $chainLoader = $this->getFactory()->createChainLoader();

        foreach ($this->getFactory()->getTwigLoaderPlugins() as $twigLoaderPlugin) {
            $chainLoader->addLoader($twigLoaderPlugin->getLoader());
        }

        return $chainLoader;
    }

    /**
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    protected function extendTwig(Environment $twig, ContainerInterface $container): Environment
    {
        $twigPlugins = $this->getFactory()->getTwigPlugins();
        foreach ($twigPlugins as $twigPlugin) {
            $twig = $twigPlugin->extend($twig, $container);
        }

        return $twig;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return array
     */
    protected function getTwigOptions(ContainerInterface $container): array
    {
        $isDebugOn = $container->get(static::SERVICE_DEBUG);
        $twigOptions = $this->getConfig()->getTwigOptions();
        $globalOptions = [
            'charset' => $container->get(static::SERVICE_CHARSET),
            'debug' => $isDebugOn,
            'strict_variables' => $isDebugOn,
        ];

        return array_replace($globalOptions, $twigOptions);
    }
}
