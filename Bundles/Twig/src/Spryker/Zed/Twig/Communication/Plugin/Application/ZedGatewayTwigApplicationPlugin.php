<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication\Plugin\Application;


use Spryker\Service\Container\ContainerInterface;
use Twig\Environment;

class ZedGatewayTwigApplicationPlugin extends TwigApplicationPlugin
{
    protected const PLUGIN_TO_IGNORE = 'Spryker\Zed\ZedNavigation\Communication\Plugin\Twig\ZedNavigationTwigPlugin';

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
            if ($twigPlugin::class === self::PLUGIN_TO_IGNORE) {
                continue;
            }

            $twig = $twigPlugin->extend($twig, $container);
        }

        return $twig;
    }
}