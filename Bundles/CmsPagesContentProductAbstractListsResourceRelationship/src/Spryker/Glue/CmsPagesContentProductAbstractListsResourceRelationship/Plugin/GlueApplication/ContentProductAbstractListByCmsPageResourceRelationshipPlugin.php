<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Plugin\GlueApplication;

use Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\CmsPagesContentProductAbstractListsResourceRelationshipConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\CmsPagesContentProductAbstractListsResourceRelationshipFactory getFactory()
 */
class ContentProductAbstractListByCmsPageResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds content-product-abstract-lists resource as a relationship by cms-page UUID.
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
            ->createContentProductAbstractListByCmsPageUuidResourceRelationshipExpander()
            ->addResourceRelationships($resources, $restRequest);
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
        return CmsPagesContentProductAbstractListsResourceRelationshipConfig::RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS;
    }
}
