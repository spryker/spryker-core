<?php

namespace Spryker\Glue\NavigationsRestApi\Processor\Expander;

use ArrayObject;
use Generated\Shared\Transfer\RestNavigationAttributesTransfer;
use Generated\Shared\Transfer\RestNavigationNodeTransfer;
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
     * @param \ArrayObject $restNavigationNodeTransfers
     *
     * @return \ArrayObject
     */
    protected function expandNavigationNodeTransfers(ArrayObject $restNavigationNodeTransfers): ArrayObject
    {
        foreach ($restNavigationNodeTransfers as $restNavigationNodeTransfer) {
            $urlStorageTransfer = $this->urlStorageClient->findUrlStorageTransferByUrl($restNavigationNodeTransfer->getUrl());
            if ($urlStorageTransfer) {
                $restNavigationNodeTransfer->setResourceId($this->findResourceId($restNavigationNodeTransfer));
            }

            if ($restNavigationNodeTransfer->getChildren()->count() > 0) {
                $navigationNodeChildren = $this->expandNavigationNodeTransfers($restNavigationNodeTransfer->getChildren());
                $restNavigationNodeTransfer->setChildren($navigationNodeChildren);
            }
        }

        return $restNavigationNodeTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\RestNavigationNodeTransfer $restNavigationNodeTransfer
     *
     * @return int|null
     */
    protected function findResourceId(RestNavigationNodeTransfer $restNavigationNodeTransfer): ?int
    {
        $urlStorageTransfer = $this->urlStorageClient->findUrlStorageTransferByUrl($restNavigationNodeTransfer->getUrl());
        if ($urlStorageTransfer) {
            return $this->findResourceIdByNodeType($urlStorageTransfer, $restNavigationNodeTransfer->getNodeType());
        }

        return null;
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
