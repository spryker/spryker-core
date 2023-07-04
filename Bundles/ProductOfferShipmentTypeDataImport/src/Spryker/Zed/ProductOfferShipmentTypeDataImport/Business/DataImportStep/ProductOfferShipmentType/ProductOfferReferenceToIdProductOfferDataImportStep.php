<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataImportStep\ProductOfferShipmentType;

use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataSet\ProductOfferShipmentTypeDataSetInterface;

class ProductOfferReferenceToIdProductOfferDataImportStep implements DataImportStepInterface
{
    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER
     *
     * @var string
     */
    public const COL_ID_PRODUCT_OFFER = 'spy_product_offer.id_product_offer';

    /**
     * @var array<string, int>
     */
    protected static array $productOfferIdsIndexedByProductOfferReference = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /** @var string $productOfferReference */
        $productOfferReference = $dataSet[ProductOfferShipmentTypeDataSetInterface::COLUMN_PRODUCT_OFFER_REFERENCE];

        if (!isset(static::$productOfferIdsIndexedByProductOfferReference[$productOfferReference])) {
            static::$productOfferIdsIndexedByProductOfferReference[$productOfferReference] = $this->getIdProductOfferByProductOfferReference(
                $productOfferReference,
            );
        }

        $dataSet[ProductOfferShipmentTypeDataSetInterface::ID_PRODUCT_OFFER] = static::$productOfferIdsIndexedByProductOfferReference[$productOfferReference];
    }

    /**
     * @param string $productOfferReference
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdProductOfferByProductOfferReference(string $productOfferReference): int
    {
        /** @var int $idProductOffer */
        $idProductOffer = $this->getProductOfferQuery()
            ->select(static::COL_ID_PRODUCT_OFFER)
            ->findOneByProductOfferReference($productOfferReference);

        if (!$idProductOffer) {
            throw new EntityNotFoundException(
                sprintf('Could not find product offer by reference "%s"', $productOfferReference),
            );
        }

        return $idProductOffer;
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function getProductOfferQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }
}
