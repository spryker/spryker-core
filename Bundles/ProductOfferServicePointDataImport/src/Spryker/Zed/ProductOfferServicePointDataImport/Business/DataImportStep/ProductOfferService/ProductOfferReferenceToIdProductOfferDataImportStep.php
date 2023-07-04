<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferServicePointDataImport\Business\DataImportStep\ProductOfferService;

use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferServicePointDataImport\Business\DataSet\ProductOfferServiceDataSetInterface;

class ProductOfferReferenceToIdProductOfferDataImportStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[ProductOfferServiceDataSetInterface::COLUMN_ID_PRODUCT_OFFER] = $this->getIdProductOfferByProductOfferReference(
            $dataSet[ProductOfferServiceDataSetInterface::COLUMN_PRODUCT_OFFER_REFERENCE],
        );
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
        /** @var string|null $idProductOffer */
        $idProductOffer = $this->getProductOfferQuery()
            ->select(SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER)
            ->findOneByProductOfferReference($productOfferReference);

        if (!$idProductOffer) {
            throw new EntityNotFoundException(
                sprintf('Could not find product offer by reference "%s".', $productOfferReference),
            );
        }

        return (int)$idProductOffer;
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function getProductOfferQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }
}
