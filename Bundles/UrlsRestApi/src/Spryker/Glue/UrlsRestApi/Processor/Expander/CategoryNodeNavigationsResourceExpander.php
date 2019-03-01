<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlsRestApi\Processor\Expander;

use ArrayObject;
use Generated\Shared\Transfer\RestNavigationAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\UrlsRestApi\Dependency\Client\UrlsRestApiToUrlStorageClientInterface;

class CategoryNodeNavigationsResourceExpander implements CategoryNodeNavigationsResourceExpanderInterface
{
    /**
     * @var \Spryker\Glue\UrlsRestApi\Dependency\Client\UrlsRestApiToUrlStorageClientInterface
     */
    protected $urlStorageClient;

    /**
     * @param \Spryker\Glue\UrlsRestApi\Dependency\Client\UrlsRestApiToUrlStorageClientInterface $urlStorageClient
     */
    public function __construct(UrlsRestApiToUrlStorageClientInterface $urlStorageClient)
    {
        $this->urlStorageClient = $urlStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\RestNavigationAttributesTransfer $restNavigationAttributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestNavigationAttributesTransfer
     */
    public function expand(
        RestNavigationAttributesTransfer $restNavigationAttributesTransfer,
        RestRequestInterface $restRequest
    ): RestNavigationAttributesTransfer {
        $nodes = $this->expandNavigationNodeTransfers($restNavigationAttributesTransfer->getNodes());
        $restNavigationAttributesTransfer->setNodes($nodes);

        return $restNavigationAttributesTransfer;
    }

    /**
     * @param \ArrayObject $restNavigationNodeTransfers
     *
     * @return \ArrayObject
     */
    protected function expandNavigationNodeTransfers(ArrayObject $restNavigationNodeTransfers): ArrayObject
    {
        foreach ($restNavigationNodeTransfers as $restNavigationNodeTransfer) {
            $urlStorageTransfer = $this->urlStorageClient->findUrlStorageTransferByUrl($restNavigationNodeTransfer->getUrl());
            if ($urlStorageTransfer) {
                $restNavigationNodeTransfer->setAssignedEntityId($urlStorageTransfer->getFkResourceCategorynode());
            }

            if ($restNavigationNodeTransfer->getChildren()->count() > 0) {
                $navigationNodeChildren = $this->expandNavigationNodeTransfers($restNavigationNodeTransfer->getChildren());
                $restNavigationNodeTransfer->setChildren($navigationNodeChildren);
            }
        }

        return $restNavigationNodeTransfers;
    }
}
