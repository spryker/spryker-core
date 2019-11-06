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
class AssetsPathTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    public const FUNCTION_NAME_ASSETS_PATH = 'assetsPath';

    /**
     * {@inheritDoc}
     * - Extends twig with "assetsPath" function to generate assets absolute url.
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
        $twig->addFunction($this->getZedAssetsPathFunction());

        return $twig;
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function getZedAssetsPathFunction(): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_ASSETS_PATH, function (string $path) {
            return $this->getZedAssetsPathByName($path);
        }, ['is_safe' => ['html']]);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getZedAssetsPathByName(string $path): string
    {
        return rtrim($this->getConfig()->getZedAssetsPath(), '/') . '/' . $path;
    }
}
