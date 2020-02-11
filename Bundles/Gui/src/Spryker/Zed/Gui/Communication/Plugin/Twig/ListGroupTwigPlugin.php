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
class ListGroupTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    public const FUNCTION_NAME_LIST_GROUP = 'listGroup';

    /**
     * {@inheritDoc}
     * - Extends twig with "listGroup" function to generate custom list group for displaying a series of content.
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
        $twig->addFunction($this->getZedListGroupFunction($twig));

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\TwigFunction
     */
    protected function getZedListGroupFunction(Environment $twig): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_LIST_GROUP, function (array $items) use ($twig) {
            if (is_array(array_values($items)[0])) {
                return $twig->render(
                    $this->getConfig()->getDefaultMultiListGroupTemplatePath(),
                    [
                        'items' => $items,
                    ]
                );
            }

            return $twig->render(
                $this->getConfig()->getDefaultListGroupTemplatePath(),
                [
                    'items' => $items,
                ]
            );
        }, ['is_safe' => ['html']]);
    }
}
