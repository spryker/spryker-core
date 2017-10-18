<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Price\Plugin;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig_Environment;
use Twig_SimpleFunction;

/**
 * @method \Spryker\Yves\Price\PriceFactory getFactory()
 * @method \Spryker\Client\Price\PriceClient getClient()
 * @method \Spryker\Yves\Price\PriceConfig getConfig()
 */
class PriceModeSwitcherServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    protected static $functionName = 'spyPriceModeSwitch';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (Twig_Environment $twig) {
                $twig->addFunction(
                    static::$functionName,
                    $this->getPriceModeSwitcher($twig)
                );

                return $twig;
            })
        );
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

    /**
     * @param \Twig_Environment $twig
     *
     * @return \Twig_SimpleFunction
     */
    protected function getPriceModeSwitcher(Twig_Environment $twig)
    {
        $options = ['is_safe' => ['html']];

        return new Twig_SimpleFunction(static::$functionName, function () use ($twig) {
            return $twig->render(
                $this->getTemplatePath(),
                [
                    'price_modes' => $this->getPriceModes(),
                    'current_price_mode' => $this->getCurrentPriceMode()
                ]
            );
        }, $options);
    }

    /**
     * @return string
     */
    protected function getCurrentPriceMode()
    {
        return $this->getClient()->getCurrentPriceMode();
    }

    /**
     * @return string
     */
    protected function getTemplatePath()
    {
        return '@Price/partial/price_mode_switcher.twig';
    }

    /**
     * @return string[]
     */
    protected function getPriceModes()
    {
        return $this
            ->getConfig()
            ->createSharedConfig()
            ->getPriceModes();
    }

}
