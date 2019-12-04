<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Reader;

use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductOptionStorageClientInterface;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\RestResponseBuilder\ProductOptionRestResponseBuilderInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Translator\ProductOptionTranslatorInterface;
use Spryker\Glue\ProductOptionsRestApi\ProductOptionsRestApiConfig;

class ProductOptionStorageReader implements ProductOptionStorageReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';

    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    protected const KEY_PRODUCT_ABSTRACT_SKU = 'sku';
    protected const KEY_PRODUCT_CONCRETE_SKU = 'sku';

    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductOptionStorageClientInterface
     */
    protected $productOptionStorageClient;

    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Processor\Translator\ProductOptionTranslatorInterface
     */
    protected $productOptionTranslator;

    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Processor\RestResponseBuilder\ProductOptionRestResponseBuilderInterface
     */
    protected $productOptionRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductOptionStorageClientInterface $productOptionStorageClient
     * @param \Spryker\Glue\ProductOptionsRestApi\Processor\Translator\ProductOptionTranslatorInterface $productOptionTranslator
     * @param \Spryker\Glue\ProductOptionsRestApi\Processor\RestResponseBuilder\ProductOptionRestResponseBuilderInterface $productOptionRestResponseBuilder
     */
    public function __construct(
        ProductOptionsRestApiToProductStorageClientInterface $productStorageClient,
        ProductOptionsRestApiToProductOptionStorageClientInterface $productOptionStorageClient,
        ProductOptionTranslatorInterface $productOptionTranslator,
        ProductOptionRestResponseBuilderInterface $productOptionRestResponseBuilder
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->productOptionStorageClient = $productOptionStorageClient;
        $this->productOptionTranslator = $productOptionTranslator;
        $this->productOptionRestResponseBuilder = $productOptionRestResponseBuilder;
    }

    /**
     * @param string[] $productAbstractSkus
     * @param string $localeName
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[] $sorts
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    public function getProductOptionsByProductAbstractSkus(
        array $productAbstractSkus,
        string $localeName,
        array $sorts
    ): array {
        $productAbstractIds = $this->productStorageClient->getBulkProductAbstractIdsByMapping(
            static::PRODUCT_ABSTRACT_MAPPING_TYPE,
            $productAbstractSkus,
            $localeName
        );
        $productAbstractOptionStorageTransfers = $this->productOptionStorageClient->getBulkProductOptions(
            $productAbstractIds
        );
        $productAbstractOptionStorageTransfers = $this->productOptionTranslator
            ->translateProductAbstractOptionStorageTransfers($productAbstractOptionStorageTransfers, $localeName);

        return $this->productOptionRestResponseBuilder
            ->createProductOptionRestResources(
                $productAbstractOptionStorageTransfers,
                $productAbstractIds,
                ProductOptionsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
                $sorts
            );
    }

    /**
     * @param string[] $productConcreteSkus
     * @param string $localeName
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[] $sorts
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    public function getProductOptionsByProductConcreteSkus(
        array $productConcreteSkus,
        string $localeName,
        array $sorts
    ): array {
        $productAbstractIds = $this->getProductAbstractIdsByProductConcreteSkus($productConcreteSkus, $localeName);
        $productAbstractOptionStorageTransfers = $this->productOptionStorageClient->getBulkProductOptions(
            array_unique($productAbstractIds)
        );
        $productAbstractOptionStorageTransfers = $this->productOptionTranslator
            ->translateProductAbstractOptionStorageTransfers($productAbstractOptionStorageTransfers, $localeName);

        return $this->productOptionRestResponseBuilder
            ->createProductOptionRestResources(
                $productAbstractOptionStorageTransfers,
                $productAbstractIds,
                ProductOptionsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $sorts
            );
    }

    /**
     * @param string $productConcreteSku
     *
     * @return int[]
     */
    public function getProductOptionIdsByProductConcreteSku(string $productConcreteSku): array
    {
        $idProductAbstract = $this->findIdProductAbstractByProductConcreteSku($productConcreteSku);
        if (!$idProductAbstract) {
            return [];
        }

        $productAbstractOptionStorageTransfer = $this->productOptionStorageClient
            ->getProductOptionsForCurrentStore($idProductAbstract);

        if (!$productAbstractOptionStorageTransfer) {
            return [];
        }

        return $this->getProductOptionIdsGroupedByProductOptionSku($productAbstractOptionStorageTransfer);
    }

    /**
     * @param string $productConcreteSku
     *
     * @return int|null
     */
    protected function findIdProductAbstractByProductConcreteSku(string $productConcreteSku): ?int
    {
        $productConcreteStorageDataItem = $this->productStorageClient->findProductConcreteStorageDataByMappingForCurrentLocale(
            static::PRODUCT_CONCRETE_MAPPING_TYPE,
            $productConcreteSku
        );

        return $productConcreteStorageDataItem[static::KEY_ID_PRODUCT_ABSTRACT] ?? null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer
     *
     * @return int[]
     */
    protected function getProductOptionIdsGroupedByProductOptionSku(
        ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer
    ): array {
        $productOptionIds = [];
        foreach ($productAbstractOptionStorageTransfer->getProductOptionGroups() as $productOptionGroupStorageTransfer) {
            foreach ($productOptionGroupStorageTransfer->getProductOptionValues() as $productOptionValueStorageTransfer) {
                $productOptionIds[$productOptionValueStorageTransfer->getSku()] =
                    $productOptionValueStorageTransfer->getIdProductOptionValue();
            }
        }

        return $productOptionIds;
    }

    /**
     * @param string[] $productConcreteSkus
     * @param string $localeName
     *
     * @return int[]
     */
    protected function getProductAbstractIdsByProductConcreteSkus(array $productConcreteSkus, string $localeName): array
    {
        $productAbstractIdsByProductConcreteSkus = [];
        $productConcreteStorageDataItems = $this->productStorageClient->getBulkProductConcreteStorageDataByMapping(
            static::PRODUCT_CONCRETE_MAPPING_TYPE,
            $productConcreteSkus,
            $localeName
        );
        foreach ($productConcreteStorageDataItems as $productConcreteStorageDataItem) {
            $productAbstractIdsByProductConcreteSkus[$productConcreteStorageDataItem[static::KEY_PRODUCT_CONCRETE_SKU]] =
                $productConcreteStorageDataItem[static::KEY_ID_PRODUCT_ABSTRACT];
        }

        return $productAbstractIdsByProductConcreteSkus;
    }
}
