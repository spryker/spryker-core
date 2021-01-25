<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesRestApi\Processor\Reader;

use Generated\Shared\Transfer\ProductBundleStorageCriteriaTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductBundlesRestApi\Dependency\Client\ProductBundlesRestApiToProductBundleStorageClientInterface;
use Spryker\Glue\ProductBundlesRestApi\Dependency\Client\ProductBundlesRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductBundlesRestApi\Processor\RestResponseBuilder\BundledProductRestResponseBuilderInterface;
use Spryker\Glue\ProductBundlesRestApi\ProductBundlesRestApiConfig;

class BundledProductReader implements BundledProductReaderInterface
{
    protected const PRODUCT_CONCRETE_MAPPING_TYPE_SKU = 'sku';

    /**
     * @var \Spryker\Glue\ProductBundlesRestApi\Dependency\Client\ProductBundlesRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\ProductBundlesRestApi\Dependency\Client\ProductBundlesRestApiToProductBundleStorageClientInterface
     */
    protected $productBundleStorageClient;

    /**
     * @var \Spryker\Glue\ProductBundlesRestApi\Processor\RestResponseBuilder\BundledProductRestResponseBuilderInterface
     */
    protected $bundledProductRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ProductBundlesRestApi\Dependency\Client\ProductBundlesRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductBundlesRestApi\Dependency\Client\ProductBundlesRestApiToProductBundleStorageClientInterface $productBundleStorageClient
     * @param \Spryker\Glue\ProductBundlesRestApi\Processor\RestResponseBuilder\BundledProductRestResponseBuilderInterface $bundledProductRestResponseBuilder
     */
    public function __construct(
        ProductBundlesRestApiToProductStorageClientInterface $productStorageClient,
        ProductBundlesRestApiToProductBundleStorageClientInterface $productBundleStorageClient,
        BundledProductRestResponseBuilderInterface $bundledProductRestResponseBuilder
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->productBundleStorageClient = $productBundleStorageClient;
        $this->bundledProductRestResponseBuilder = $bundledProductRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getBundledProducts(RestRequestInterface $restRequest): RestResponseInterface
    {
        $productConcreteResource = $restRequest->findParentResourceByType(
            ProductBundlesRestApiConfig::RESOURCE_CONCRETE_PRODUCTS
        );

        if (!$productConcreteResource || !$productConcreteResource->getId()) {
            return $this->bundledProductRestResponseBuilder->createProductConcreteSkuNotSpecifiedErrorResponse();
        }
        $productConcreteSku = (string)$productConcreteResource->getId();

        $bundledProductRestResources = $this->getBundledProductRestResourcesByProductConcreteSkus(
            [$productConcreteSku],
            $restRequest
        );

        if (!isset($bundledProductRestResources[$productConcreteSku])) {
            return $this->bundledProductRestResponseBuilder->createBundledProductEmptyRestResponse();
        }

        return $this->bundledProductRestResponseBuilder
            ->createBundledProductCollectionRestResponse($bundledProductRestResources[$productConcreteSku]);
    }

    /**
     * @phpstan-return array<string, array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>>
     *
     * @param string[] $productConcreteSkus
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    public function getBundledProductRestResourcesByProductConcreteSkus(
        array $productConcreteSkus,
        RestRequestInterface $restRequest
    ): array {
        $productBundleStorageTransfers = $this->getProductBundlesFromStorage(
            $productConcreteSkus,
            $restRequest->getMetadata()->getLocale()
        );

        if (!$productBundleStorageTransfers) {
            return [];
        }

        $bundledProductRestResources = [];
        foreach ($productBundleStorageTransfers as $productConcreteSku => $productBundleStorageTransfer) {
            $bundledProductRestResources[$productConcreteSku] = $this->bundledProductRestResponseBuilder
                ->createBundledProductRestResources($productConcreteSku, $productBundleStorageTransfer);
        }

        return $bundledProductRestResources;
    }

    /**
     * @phpstan-return array<string, \Generated\Shared\Transfer\ProductBundleStorageTransfer>
     *
     * @param string[] $productConcreteSkus
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductBundleStorageTransfer[]
     */
    protected function getProductBundlesFromStorage(array $productConcreteSkus, string $localeName): array
    {
        $productConcreteIds = $this->productStorageClient->getProductConcreteIdsByMapping(
            static::PRODUCT_CONCRETE_MAPPING_TYPE_SKU,
            $productConcreteSkus,
            $localeName
        );

        if (!$productConcreteIds) {
            return [];
        }

        $productBundleStorageCriteriaTransfer = (new ProductBundleStorageCriteriaTransfer())
            ->setProductConcreteIds($productConcreteIds);
        $productBundleStorageTransfers = $this->productBundleStorageClient->getProductBundles($productBundleStorageCriteriaTransfer);
        $productBundleStorageTransfersIndexedBySku = [];
        foreach ($productBundleStorageTransfers as $idProductConcrete => $productBundleStorageTransfer) {
            /** @var string $productConcreteSku */
            $productConcreteSku = array_search($idProductConcrete, $productConcreteIds, true);
            $productBundleStorageTransfersIndexedBySku[$productConcreteSku] = $productBundleStorageTransfer;
        }

        return $productBundleStorageTransfersIndexedBySku;
    }
}
