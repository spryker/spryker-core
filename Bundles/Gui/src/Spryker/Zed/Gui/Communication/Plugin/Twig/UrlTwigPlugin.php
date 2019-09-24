<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 */
class UrlTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    public const FUNCTION_NAME_URL = 'url';

    /**
     * {@inheritDoc}
     * - Extends twig with "url" function to parse and generate URLs based on URL parts.
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
        $twig->addFunction($this->getUrlFunction());

        return $twig;
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function getUrlFunction(): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_URL, function ($url, array $query = [], array $options = []) {
            $url = Url::generate($url, $query, $options);
            $html = $url->buildEscaped();

            return $html;
        }, ['is_safe' => ['html']]);
    }
}
