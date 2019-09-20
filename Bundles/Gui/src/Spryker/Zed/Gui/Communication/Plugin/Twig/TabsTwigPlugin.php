<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 */
class TabsTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    public const FUNCTION_NAME_TABS = 'tabs';

    /**
     * Specification:
     * - Allows to extend Twig with additional function to get Zed assets base url.
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
        $twig->addFunction($this->getTabsFunction($twig));

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\TwigFunction
     */
    protected function getTabsFunction(Environment $twig): TwigFunction
    {
        $options['needs_environment'] = true;

        return new TwigFunction(static::FUNCTION_NAME_TABS, function (TabsViewTransfer $tabsViewTransfer, array $context) use ($twig) {
            $context['tabsViewTransfer'] = $tabsViewTransfer;

            return $twig->render($this->getConfig()->getTabsDefaultTemplatePath(), $context);
        }, $options);
    }
}
