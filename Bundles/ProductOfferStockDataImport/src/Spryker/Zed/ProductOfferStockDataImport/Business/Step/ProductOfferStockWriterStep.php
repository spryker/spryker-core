<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferStockDataImport\Business\Step;

use Orm\Zed\ProductOfferStock\Persistence\Base\SpyProductOfferStockQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferStock\Dependency\ProductOfferStockEvents;
use Spryker\Zed\ProductOfferStockDataImport\Business\DataSet\ProductOfferStockDataSetInterface;

class ProductOfferStockWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    protected const REQUIRED_DATA_SET_KEYS = [
        ProductOfferStockDataSetInterface::FK_STOCK,
        ProductOfferStockDataSetInterface::FK_PRODUCT_OFFER,
        ProductOfferStockDataSetInterface::QUANTITY,
        ProductOfferStockDataSetInterface::IS_NEVER_OUT_OF_STOCK,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $productOfferStockEntity = SpyProductOfferStockQuery::create()
            ->filterByFkProductOffer($dataSet[ProductOfferStockDataSetInterface::FK_PRODUCT_OFFER])
            ->filterByFkStock($dataSet[ProductOfferStockDataSetInterface::FK_STOCK])
            ->findOneOrCreate();

        $productOfferStockEntity
            ->setQuantity($dataSet[ProductOfferStockDataSetInterface::QUANTITY])
            ->setIsNeverOutOfStock($dataSet[ProductOfferStockDataSetInterface::IS_NEVER_OUT_OF_STOCK])
            ->save();

        $this->addPublishEvents(
            ProductOfferStockEvents::ENTITY_SPY_PRODUCT_OFFER_STOCK_PUBLISH,
            $productOfferStockEntity->getIdProductOfferStock()
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function validateDataSet(DataSetInterface $dataSet): void
    {
        foreach (static::REQUIRED_DATA_SET_KEYS as $requiredDataSetKey) {
            if (!isset($dataSet[$requiredDataSetKey])) {
                throw new InvalidDataException(sprintf('"%s" is required.', $requiredDataSetKey));
            }
        }
    }
}
