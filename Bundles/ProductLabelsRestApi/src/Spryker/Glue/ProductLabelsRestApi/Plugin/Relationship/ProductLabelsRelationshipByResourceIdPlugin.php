<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi\Plugin\Relationship;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;

/**
 * @method \Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiFactory getFactory()
 */
class ProductLabelsRelationshipByResourceIdPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * - Adds relationship to the abstract product by sku.
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
            ->createProductLabelsResourceRelationshipExpander()
            ->addRelationshipsByAbstractSku($resources, $restRequest);
    }

    /**
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS;
    }
}
