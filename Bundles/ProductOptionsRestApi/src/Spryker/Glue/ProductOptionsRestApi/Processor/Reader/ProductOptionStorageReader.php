<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Reader;

use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductOptionStorageClientInterface;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\RestResponseBuilder\ProductOptionRestResponseBuilderInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Translator\ProductOptionTranslatorInterface;
use Spryker\Glue\ProductOptionsRestApi\ProductOptionsRestApiConfig;

class ProductOptionStorageReader implements ProductOptionStorageReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';

    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    protected const KEY_PRODUCT_ABSTRACT_SKU = 'sku';

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
        $productAbstractIds = $this->getProductAbstractIdsByProductAbstractSkus($productAbstractSkus, $localeName);
        $productAbstractOptionStorageTransfers = $this->productOptionStorageClient->getBulkProductOptions(
            $productAbstractIds
        );
        $productAbstractOptionStorageTransfers = $this->productOptionTranslator
            ->translateProductAbstractOptionStorageTransfers($productAbstractOptionStorageTransfers, $localeName);
        $productOptionRestResources = [];
        foreach ($productAbstractIds as $productAbstractSku => $idProductAbstract) {
            if (!isset($productAbstractOptionStorageTransfers[$idProductAbstract])) {
                continue;
            }

            $productOptionRestResources[$productAbstractSku] = $this->productOptionRestResponseBuilder
                ->createProductOptionRestResources(
                    $productAbstractOptionStorageTransfers[$idProductAbstract],
                    ProductOptionsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
                    $productAbstractSku,
                    $sorts
                );
        }

        return $productOptionRestResources;
    }

    /**
     * @param string[] $productAbstractSkus
     * @param string $localeName
     *
     * @return int[]
     */
    protected function getProductAbstractIdsByProductAbstractSkus(array $productAbstractSkus, string $localeName): array
    {
        $productAbstractIdsByProductAbstractSkus = [];
        $productAbstractStorageDataItems = $this->productStorageClient->findBulkProductAbstractStorageDataByMapping(
            static::PRODUCT_ABSTRACT_MAPPING_TYPE,
            $productAbstractSkus,
            $localeName
        );
        foreach ($productAbstractStorageDataItems as $productAbstractStorageDataItem) {
            $productAbstractIdsByProductAbstractSkus[$productAbstractStorageDataItem[static::KEY_PRODUCT_ABSTRACT_SKU]] =
                $productAbstractStorageDataItem[static::KEY_ID_PRODUCT_ABSTRACT];
        }

        return $productAbstractIdsByProductAbstractSkus;
    }
}
