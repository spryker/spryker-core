<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabelGui;

use Spryker\Zed\CompanyUnitAddressLabelGui\Dependency\Facade\CompanyUnitAddressLabelGuiToCompanyUnitAddressLabelFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CompanyUnitAddressLabelGui\CompanyUnitAddressLabelGuiConfig getConfig()
 */
class CompanyUnitAddressLabelGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_COMPANY_UNIT_ADDRESS_LABEL = 'FACADE_COMPANY_UNIT_ADDRESS_LABEL';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container->set(static::FACADE_COMPANY_UNIT_ADDRESS_LABEL, function (Container $container) {
            return new CompanyUnitAddressLabelGuiToCompanyUnitAddressLabelFacadeBridge(
                $container->getLocator()->companyUnitAddressLabel()->facade(),
            );
        });

        return $container;
    }
}
