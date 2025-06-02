<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 */
class NavigationLinkTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * @var string
     */
    public const FUNCTION_NAME_LAYOUT_NAVIGATION_ITEMS = 'layout_navigation_items';

    /**
     * {@inheritDoc}
     * - Extends twig with `layout_navigation_items` function to generate navigation items from plugins.
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
        $twig->addFunction($this->getNavigationItemsFunction($twig));

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\TwigFunction
     */
    protected function getNavigationItemsFunction(Environment $twig): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_LAYOUT_NAVIGATION_ITEMS, function () use ($twig) {
            return $this->getFactory()->createNavigationLinkGenerator()->generateNavigationItems($twig);
        }, ['is_safe' => ['html']]);
    }
}
