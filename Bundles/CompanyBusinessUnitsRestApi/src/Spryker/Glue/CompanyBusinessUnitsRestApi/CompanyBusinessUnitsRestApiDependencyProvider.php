<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class CompanyBusinessUnitsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_COMPANY_BUSINESS_UNIT_ATTRIBUTES_MAPPER = 'PLUGINS_COMPANY_BUSINESS_UNIT_ATTRIBUTES_MAPPER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCompanyBusinessUnitAttributesMapperPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCompanyBusinessUnitAttributesMapperPlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_BUSINESS_UNIT_ATTRIBUTES_MAPPER] = function () {
            return $this->getCompanyBusinessUnitAttributesMapperPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Glue\CompanyBusinessUnitsRestApiExtension\Dependency\Plugin\CompanyBusinessUnitAttributesMapperPluginInterface[]
     */
    protected function getCompanyBusinessUnitAttributesMapperPlugins(): array
    {
        return [];
    }
}
