<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAlternativesRestApi\Plugin\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductAlternativesRestApi\ProductAlternativesRestApiConfig;

/**
 * @method \Spryker\Glue\ProductAlternativesRestApi\ProductAlternativesRestApiFactory getFactory()
 */
class AlternativeProductRelationshipByResourceIdPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritdoc}
     *  - Adds alternative-products relationship by concrete product sku.
     *
     * @api
     *
     * @param array $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $this->getFactory()
            ->createProductAlternativesResourceRelationshipExpander()
            ->addRelationshipsByConcreteSku($resources, $restRequest);
    }

    /**
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return ProductAlternativesRestApiConfig::RESOURCE_ALTERNATIVE_PRODUCTS;
    }
}
