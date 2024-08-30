<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantPortalApplication\Communication\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;

/**
 * @method \Spryker\Zed\MerchantPortalApplication\MerchantPortalApplicationConfig getConfig()
 * @method \Spryker\Zed\MerchantPortalApplication\Communication\MerchantPortalApplicationCommunicationFactory getFactory()
 */
class MerchantNavigationTypeTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * @var string
     */
    protected const TWIG_GLOBAL_VARIABLE_MAIN_MERCHANT_NAVIGATION_TYPE = 'mainMerchantNavigationType';

    /**
     * @var string
     */
    protected const TWIG_GLOBAL_VARIABLE_SECONDARY_MERCHANT_NAVIGATION_TYPE = 'secondaryMerchantNavigationType';

    /**
     * @var string
     */
    protected const NAVIGATION_TYPE_MAIN_MERCHANT_PORTAL = 'main-merchant-portal';

    /**
     * @var string
     */
    protected const NAVIGATION_TYPE_SECONDARY_MERCHANT_PORTAL = 'secondary-merchant-portal';

    /**
     * {@inheritDoc}
     * - Adds `mainMerchantNavigationType` and `secondaryMerchantNavigationType` Twig global variables.
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
        $twig = $this->addTwigGlobalVariables($twig);

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function addTwigGlobalVariables(Environment $twig): Environment
    {
        $twig->addGlobal(
            static::TWIG_GLOBAL_VARIABLE_MAIN_MERCHANT_NAVIGATION_TYPE,
            static::NAVIGATION_TYPE_MAIN_MERCHANT_PORTAL,
        );
        $twig->addGlobal(
            static::TWIG_GLOBAL_VARIABLE_SECONDARY_MERCHANT_NAVIGATION_TYPE,
            static::NAVIGATION_TYPE_SECONDARY_MERCHANT_PORTAL,
        );

        return $twig;
    }
}
