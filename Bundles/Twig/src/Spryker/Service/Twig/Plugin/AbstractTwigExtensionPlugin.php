<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Twig\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Shared\Twig\TwigExtensionInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Twig\Environment;

abstract class AbstractTwigExtensionPlugin extends AbstractPlugin implements TwigPluginInterface, TwigExtensionInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addExtension($this);

        return $twig;
    }

    /**
     * @deprecated since 1.23 (to be removed in 2.0), implement \Twig\Extension\InitRuntimeInterface instead
     *
     * @param \Twig\Environment $environment
     *
     * @return void
     */
    public function initRuntime(Environment $environment)
    {
    }

    /**
     * @return \Twig\TokenParser\TokenParserInterface[]
     */
    public function getTokenParsers(): array
    {
        return [];
    }

    /**
     * @return \Twig\NodeVisitor\NodeVisitorInterface[]
     */
    public function getNodeVisitors(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Shared\Twig\TwigFilter[]
     */
    public function getFilters(): array
    {
        return [];
    }

    /**
     * @return \Twig\TwigTest[]
     */
    public function getTests(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Shared\Twig\TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getOperators(): array
    {
        return [];
    }

    /**
     * @deprecated since 1.23 (to be removed in 2.0), implement Twig\Extension\GlobalsInterface instead
     *
     * @return array
     */
    public function getGlobals(): array
    {
        return [];
    }

    /**
     * @deprecated since 1.26 (to be removed in 2.0), not used anymore internally
     *
     * @return string
     */
    public function getName()
    {
        return static::class;
    }
}
