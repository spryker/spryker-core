<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOffersRestApi\Plugin\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductOffersRestApi\ProductOffersRestApiConfig;

/**
 * @method \Spryker\Glue\ProductOffersRestApi\ProductOffersRestApiFactory getFactory()
 */
class ProductOffersByProductOfferReferenceResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritDoc}
     * - Finds corresponding product offer by product offer reference.
     * - Adds product offers relation to resource.
     *
     * @api
     *
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $this->getFactory()
            ->createProductOfferExpander()
            ->addProductOffersRelationshipsByReference($resources);
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
        return ProductOffersRestApiConfig::RESOURCE_PRODUCT_OFFERS;
    }
}
