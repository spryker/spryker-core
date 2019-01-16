<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Plugin;

use Generated\Shared\Transfer\RestCompanyBusinessUnitAddressAttributesTransfer;
use Spryker\Glue\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiConfig;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiFactory getFactory()
 */
class CompanyBusinessUnitAddressesResourcePlugin extends AbstractPlugin implements ResourceRoutePluginInterface
{
    /**
     * {@inheritdoc}
     * - Configures available actions for company-unit-addresses resource.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function configure(ResourceRouteCollectionInterface $resourceRouteCollection): ResourceRouteCollectionInterface
    {
        $resourceRouteCollection
            ->addGet(CompanyBusinessUnitAddressesRestApiConfig::ACTION_COMPANY_BUSINESS_UNIT_ADDRESSES_GET);

        return $resourceRouteCollection;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceType(): string
    {
        return CompanyBusinessUnitAddressesRestApiConfig::RESOURCE_COMPANY_BUSINESS_UNIT_ADDRESSES;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getController(): string
    {
        return CompanyBusinessUnitAddressesRestApiConfig::CONTROLLER_RESOURCE_COMPANY_BUSINESS_UNIT_ADDRESSES;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceAttributesClassName(): string
    {
        return RestCompanyBusinessUnitAddressAttributesTransfer::class;
    }
}
