<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductTableDataMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Reader\PriceProductReaderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface;
use Symfony\Component\Form\DataTransformerInterface;

class PriceProductTransformer implements DataTransformerInterface
{
    /**
     * @var int|null
     */
    protected ?int $idProductAbstract = null;

    /**
     * @var int|null
     */
    protected ?int $idProductConcrete = null;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Reader\PriceProductReaderInterface
     */
    protected PriceProductReaderInterface $priceProductReader;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapperInterface
     */
    protected PriceProductMapperInterface $priceProductMapper;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductTableDataMapperInterface
     */
    protected PriceProductTableDataMapperInterface $priceProductTableDataMapper;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Reader\PriceProductReaderInterface $priceProductReader
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapperInterface $priceProductMapper
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductTableDataMapperInterface $priceProductTableDataMapper
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        PriceProductReaderInterface $priceProductReader,
        PriceProductMapperInterface $priceProductMapper,
        PriceProductTableDataMapperInterface $priceProductTableDataMapper,
        ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->priceProductReader = $priceProductReader;
        $this->priceProductMapper = $priceProductMapper;
        $this->priceProductTableDataMapper = $priceProductTableDataMapper;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return $this
     */
    public function setIdProductAbstract(int $idProductAbstract)
    {
        $this->idProductAbstract = $idProductAbstract;

        return $this;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return $this
     */
    public function setIdProductConcrete(int $idProductConcrete)
    {
        $this->idProductConcrete = $idProductConcrete;

        return $this;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>|mixed $value
     *
     * @return string|null
     */
    public function transform($value): ?string
    {
        $priceProductTableViewCollection = $this->priceProductTableDataMapper
            ->mapPriceProductTransfersToPriceProductTableViewCollectionTransfer(
                $value->getArrayCopy(),
                new PriceProductTableViewCollectionTransfer(),
            )->toArrayRecursiveCamelCased();
        $prices = $priceProductTableViewCollection[PriceProductTableViewCollectionTransfer::PRICE_PRODUCT_TABLE_VIEWS];

        return $this->utilEncodingService->encodeJson($prices);
    }

    /**
     * @param mixed $value
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function reverseTransform($value): ArrayObject
    {
        $newPriceProducts = $this->utilEncodingService->decodeJson($value, true);
        if (!$newPriceProducts) {
            return new ArrayObject();
        }

        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->setIdProductConcrete($this->idProductConcrete)
            ->setIdProductAbstract($this->idProductAbstract);

        $priceProductTransfers = $this->priceProductReader->getPriceProductsWithoutPriceExtraction(
            $priceProductCriteriaTransfer,
        );

        return $this->priceProductMapper
            ->mapTableRowsToPriceProductTransfers($newPriceProducts, new ArrayObject($priceProductTransfers));
    }
}
