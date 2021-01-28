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
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 */
class UrlTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    public const FUNCTION_NAME_URL = 'url';

    protected const DEFAULT_ENCODING = 'UTF-8';

    /**
     * @uses \Spryker\Zed\Router\Communication\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     */
    protected const SERVICE_ROUTER = 'routers';

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
        $twig->addFunction($this->getUrlFunction($container));

        return $twig;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\TwigFunction
     */
    protected function getUrlFunction(ContainerInterface $container): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_URL, function (string $url, array $query = [], array $options = []) use ($container) {
            try {
                if ($container->has(static::SERVICE_ROUTER)) {
                    /** @var \Symfony\Cmf\Component\Routing\ChainRouter $router */
                    $router = $container->get(static::SERVICE_ROUTER);
                    $url = $router->generate($url, $query);

                    $charset = mb_internal_encoding() ?: static::DEFAULT_ENCODING;

                    return htmlspecialchars($url, ENT_QUOTES | ENT_SUBSTITUTE, $charset);
                }
            } catch (RouteNotFoundException $exception) {
            }

            $url = Url::generate($url, $query, $options);

            return $url->buildEscaped();
        }, ['is_safe' => ['html']]);
    }
}
