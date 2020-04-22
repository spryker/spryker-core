<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsRestApi\Processor\Expander;

use ArrayObject;
use Generated\Shared\Transfer\RestNavigationAttributesTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Glue\NavigationsRestApi\Dependency\Client\NavigationsRestApiToUrlStorageClientInterface;
use Spryker\Glue\NavigationsRestApi\NavigationsRestApiConfig;

class NavigationNodeExpander implements NavigationNodeExpanderInterface
{
    /**
     * @var \Spryker\Glue\NavigationsRestApi\Dependency\Client\NavigationsRestApiToUrlStorageClientInterface
     */
    protected $urlStorageClient;

    /**
     * @var \Spryker\Glue\NavigationsRestApi\NavigationsRestApiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Glue\NavigationsRestApi\Dependency\Client\NavigationsRestApiToUrlStorageClientInterface $urlStorageClient
     * @param \Spryker\Glue\NavigationsRestApi\NavigationsRestApiConfig $config
     */
    public function __construct(
        NavigationsRestApiToUrlStorageClientInterface $urlStorageClient,
        NavigationsRestApiConfig $config
    ) {
        $this->urlStorageClient = $urlStorageClient;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\RestNavigationAttributesTransfer $restNavigationAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestNavigationAttributesTransfer
     */
    public function expand(RestNavigationAttributesTransfer $restNavigationAttributesTransfer): RestNavigationAttributesTransfer
    {
        $nodes = $this->expandNavigationNodeTransfers($restNavigationAttributesTransfer->getNodes());
        $restNavigationAttributesTransfer->setNodes($nodes);

        return $restNavigationAttributesTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\RestNavigationNodeTransfer[] $restNavigationNodeTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\RestNavigationNodeTransfer[]
     */
    protected function expandNavigationNodeTransfers(ArrayObject $restNavigationNodeTransfers): ArrayObject
    {
        $urlCollection = [];
        $this->getUrlCollection($restNavigationNodeTransfers, $urlCollection);
        $urlStorageTransfers = $this->urlStorageClient->getUrlStorageTransferByUrls($urlCollection);

        return $this->mapUrlStorageTransfersToRestNavigationNodeTransfers(
            $urlStorageTransfers,
            $restNavigationNodeTransfers
        );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\RestNavigationNodeTransfer[] $restNavigationNodeTransfers
     * @param string[] $urlCollection
     *
     * @return void
     */
    protected function getUrlCollection(ArrayObject $restNavigationNodeTransfers, array &$urlCollection = []): void
    {
        foreach ($restNavigationNodeTransfers as $restNavigationNodeTransfer) {
            if ($restNavigationNodeTransfer->getUrl()) {
                $urlCollection[] = $restNavigationNodeTransfer->getUrl();
            }

            if ($restNavigationNodeTransfer->getChildren()->count() > 0) {
                $this->getUrlCollection($restNavigationNodeTransfer->getChildren(), $urlCollection);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer[] $urlStorageTransfers
     * @param \ArrayObject|\Generated\Shared\Transfer\RestNavigationNodeTransfer[] $restNavigationNodeTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\RestNavigationNodeTransfer[]
     */
    protected function mapUrlStorageTransfersToRestNavigationNodeTransfers(
        array $urlStorageTransfers,
        ArrayObject $restNavigationNodeTransfers
    ): ArrayObject {
        foreach ($restNavigationNodeTransfers as $restNavigationNodeTransfer) {
            if (array_key_exists($restNavigationNodeTransfer->getUrl(), $urlStorageTransfers)) {
                $resourceId = $this->findResourceIdByNodeType(
                    $urlStorageTransfers[$restNavigationNodeTransfer->getUrl()],
                    $restNavigationNodeTransfer->getNodeType()
                );
                $restNavigationNodeTransfer->setResourceId($resourceId);
            }

            if ($restNavigationNodeTransfer->getChildren()->count() > 0) {
                $navigationNodeChildren = $this->mapUrlStorageTransfersToRestNavigationNodeTransfers(
                    $urlStorageTransfers,
                    $restNavigationNodeTransfer->getChildren()
                );
                $restNavigationNodeTransfer->setChildren($navigationNodeChildren);
            }
        }

        return $restNavigationNodeTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     * @param string $nodeType
     *
     * @return int|null
     */
    protected function findResourceIdByNodeType(UrlStorageTransfer $urlStorageTransfer, string $nodeType): ?int
    {
        if (!isset($this->config->getNavigationTypeToUrlResourceIdFieldMapping()[$nodeType])) {
            return null;
        }

        return $urlStorageTransfer[$this->config->getNavigationTypeToUrlResourceIdFieldMapping()[$nodeType]]
            ?? null;
    }
}
