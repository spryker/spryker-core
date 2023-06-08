<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferServicePointDataImport\Business\DataImportStep\ProductOfferService;

use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferServicePointDataImport\Business\DataSet\ProductOfferServiceDataSetInterface;

class ProductOfferReferenceValidationDataImportStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productOfferReference = $dataSet[ProductOfferServiceDataSetInterface::COLUMN_PRODUCT_OFFER_REFERENCE];
        $productOfferExists = $this->getProductOfferQuery()
            ->filterByProductOfferReference($productOfferReference)
            ->exists();

        if (!$productOfferExists) {
            throw new EntityNotFoundException(
                sprintf('Product offer with reference "%s" not found.', $productOfferReference),
            );
        }
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function getProductOfferQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }
}
