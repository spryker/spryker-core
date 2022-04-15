<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Mapper;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Reader\MerchantRelationshipReaderInterface;

class MerchantRelationshipPriceProductMapper implements MerchantRelationshipPriceProductMapperInterface
{
    /**
     * @uses \Spryker\Shared\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig::PRICE_DIMENSION_MERCHANT_RELATIONSHIP
     *
     * @var string
     */
    protected const PRICE_DIMENSION_MERCHANT_RELATIONSHIP = 'PRICE_DIMENSION_MERCHANT_RELATIONSHIP';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DIMENSION_DEFAULT
     *
     * @var string
     */
    protected const PRICE_DIMENSION_TYPE_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

    /**
     * @var string
     */
    protected const PRICE_DIMENSION_NAME_DEFAULT = 'Default';

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Reader\MerchantRelationshipReaderInterface
     */
    protected $merchantRelationshipReader;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Reader\MerchantRelationshipReaderInterface $merchantRelationshipReader
     */
    public function __construct(MerchantRelationshipReaderInterface $merchantRelationshipReader)
    {
        $this->merchantRelationshipReader = $merchantRelationshipReader;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTableViewTransfer $priceProductTableViewTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTableViewTransfer
     */
    public function mapPriceProductTransferToPriceProductTableViewTransfer(
        PriceProductTransfer $priceProductTransfer,
        PriceProductTableViewTransfer $priceProductTableViewTransfer
    ): PriceProductTableViewTransfer {
        $idMerchantRelationship = $priceProductTransfer->getPriceDimensionOrFail()->getIdMerchantRelationship();
        if (!$idMerchantRelationship) {
            return $priceProductTableViewTransfer;
        }

        $priceProductTableViewTransfer
            ->setIdMerchantRelationship($idMerchantRelationship)
            ->setMerchantRelationshipName(
                $this->merchantRelationshipReader->findMerchantRelationshipNameByIdMerchantRelationship($idMerchantRelationship),
            );

        return $priceProductTableViewTransfer;
    }

    /**
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapRequestDataToPriceProductTransfer(
        array $data,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        if (!isset($data[PriceProductDimensionTransfer::ID_MERCHANT_RELATIONSHIP])) {
            return $priceProductTransfer;
        }

        if (!$data[PriceProductTableViewTransfer::ID_MERCHANT_RELATIONSHIP]) {
            $priceProductTransfer->getPriceDimensionOrFail()
                ->setType(static::PRICE_DIMENSION_TYPE_DEFAULT)
                ->setName(static::PRICE_DIMENSION_NAME_DEFAULT)
                ->setIdMerchantRelationship(null);

            return $priceProductTransfer;
        }

        $priceProductTransfer->getPriceDimensionOrFail()
            ->setType(static::PRICE_DIMENSION_MERCHANT_RELATIONSHIP)
            ->setIdMerchantRelationship($data[PriceProductTableViewTransfer::ID_MERCHANT_RELATIONSHIP]);

        return $priceProductTransfer;
    }

    /**
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapTableDataToPriceProductTransfer(
        array $data,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        $priceProductTransfer->setPriceDimension(
            (new PriceProductDimensionTransfer())
                ->setIdMerchantRelationship($data[PriceProductTableViewTransfer::ID_MERCHANT_RELATIONSHIP])
                ->setType(
                    $data[PriceProductTableViewTransfer::ID_MERCHANT_RELATIONSHIP]
                        ? static::PRICE_DIMENSION_MERCHANT_RELATIONSHIP
                        : static::PRICE_DIMENSION_TYPE_DEFAULT,
                ),
        );

        return $priceProductTransfer;
    }

    /**
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    public function mapRequestDataToPriceProductCriteriaTransfer(
        array $data,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): PriceProductCriteriaTransfer {
        if (isset($data[MerchantRelationshipTransfer::ID_MERCHANT_RELATIONSHIP]) && $data[MerchantRelationshipTransfer::ID_MERCHANT_RELATIONSHIP]) {
            return $priceProductCriteriaTransfer->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setIdMerchantRelationship((int)$data[MerchantRelationshipTransfer::ID_MERCHANT_RELATIONSHIP])
                    ->setType(static::PRICE_DIMENSION_MERCHANT_RELATIONSHIP),
            );
        }

        return $priceProductCriteriaTransfer->setPriceDimension(
            (new PriceProductDimensionTransfer())->setType(static::PRICE_DIMENSION_TYPE_DEFAULT),
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer
     */
    public function mapPriceProductTransfersToPriceProductCollectionDeleteCriteriaTransfer(
        array $priceProductTransfers,
        PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
    ): PriceProductCollectionDeleteCriteriaTransfer {
        foreach ($priceProductTransfers as $priceProductTransfer) {
            if (!$priceProductTransfer->getPriceDimensionOrFail()->getIdMerchantRelationship()) {
                continue;
            }
            $priceProductCollectionDeleteCriteriaTransfer->addIdMerchantRelationship(
                $priceProductTransfer->getPriceDimensionOrFail()->getIdMerchantRelationshipOrFail(),
            );
            $priceProductCollectionDeleteCriteriaTransfer->addIdPriceProductStore(
                $priceProductTransfer->getMoneyValueOrFail()->getIdEntityOrFail(),
            );
        }

        return $priceProductCollectionDeleteCriteriaTransfer;
    }
}
