<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Plugin;

use Spryker\Glue\ContentProductAbstractListsRestApi\ContentProductAbstractListsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\ContentProductAbstractListsRestApi\ContentProductAbstractListsRestApiFactory getFactory()
 */
class ContentProductAbstractListByCmsPageResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds `content-product-abstract-lists` resource as a relationship by cms-page reference.
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
            ->createContentProductAbstractListByCmsPageReferenceResourceRelationshipExpander()
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
        return ContentProductAbstractListsRestApiConfig::RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS;
    }
}
