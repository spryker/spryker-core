<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductsProductsResourceRelationship\Plugin;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\ContentProductsProductsResourceRelationship\ContentProductsProductsResourceRelationshipFactory getFactory()
 */
class ContentProductsProductsResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    protected const RELATIONSHIP_RESOURCE_TYPE = 'abstract-products';

    /**
     * @param array $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $this->getFactory()
            ->createContentProductsProductsResourceRelationshipExpander()
            ->addResourceRelationships($resources, $restRequest);
    }

    /**
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return static::RELATIONSHIP_RESOURCE_TYPE;
    }
}
