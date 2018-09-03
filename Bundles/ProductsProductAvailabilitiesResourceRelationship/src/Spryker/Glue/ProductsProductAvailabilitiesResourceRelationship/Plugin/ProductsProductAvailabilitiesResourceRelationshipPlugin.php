<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\Plugin;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\ProductsProductAvailabilitiesResourceRelationshipFactory getFactory()
 */
class ProductsProductAvailabilitiesResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $this->getFactory()
            ->createAbstractProductsProductAvailabilitiesResourceRelationshipExpander()
            ->addResourceRelationships($resources, $restRequest);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return 'abstract-product-availabilities';
    }
}
