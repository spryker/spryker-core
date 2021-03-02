<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferStockDataImport\Business\Step;

use Orm\Zed\ProductOfferStock\Persistence\Base\SpyProductOfferStockQuery;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferStock\Dependency\ProductOfferStockEvents;
use Spryker\Zed\ProductOfferStockDataImport\Business\DataSet\ProductOfferStockDataSetInterface;

class ProductOfferStockWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    protected const FK_STOCK = ProductOfferStockDataSetInterface::FK_STOCK;
    protected const FK_PRODUCT_OFFER = ProductOfferStockDataSetInterface::FK_PRODUCT_OFFER;
    protected const QUANTITY = ProductOfferStockDataSetInterface::QUANTITY;
    protected const IS_NEVER_OUT_OF_STOCK = ProductOfferStockDataSetInterface::IS_NEVER_OUT_OF_STOCK;

    protected const REQUIRED_DATA_SET_KEYS = [
        self::FK_STOCK,
        self::FK_PRODUCT_OFFER,
        self::QUANTITY,
        self::IS_NEVER_OUT_OF_STOCK,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $productOfferStockEntity = $this->findOrCreateProductOfferStock($dataSet);

        $productOfferStockEntity
            ->setQuantity($dataSet[static::QUANTITY])
            ->setIsNeverOutOfStock($dataSet[static::IS_NEVER_OUT_OF_STOCK])
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

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock
     */
    protected function findOrCreateProductOfferStock(DataSetInterface $dataSet): SpyProductOfferStock
    {
        /** @var \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock|null $productOfferStockEntity */
        $productOfferStockEntity = SpyProductOfferStockQuery::create()
            ->filterByFkProductOffer($dataSet[static::FK_PRODUCT_OFFER])
            ->filterByFkStock($dataSet[static::FK_STOCK])
            ->findOneOrCreate();

        return $productOfferStockEntity;
    }
}
