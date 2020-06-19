<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferAvailabilitiesRestApi\Plugin\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\ProductOfferAvailabilitiesRestApiConfig;

/**
 * @method \Spryker\Glue\ProductOfferAvailabilitiesRestApi\ProductOfferAvailabilitiesRestApiFactory getFactory()
 */
class ProductOfferAvailabilitiesByProductOfferReferenceResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds product offer availabilities as a relationship to product offers.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $this->getFactory()
            ->createProductOfferAvailabilityExpander()
            ->addProductOfferAvailabilitiesRelationships($resources);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return ProductOfferAvailabilitiesRestApiConfig::RESOURCE_PRODUCT_OFFER_AVAILABILITIES;
    }
}
