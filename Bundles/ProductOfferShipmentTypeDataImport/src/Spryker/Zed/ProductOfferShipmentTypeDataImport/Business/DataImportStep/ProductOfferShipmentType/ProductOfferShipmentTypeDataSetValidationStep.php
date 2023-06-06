<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataImportStep\ProductOfferShipmentType;

use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataSet\ProductOfferShipmentTypeDataSetInterface;

class ProductOfferShipmentTypeDataSetValidationStep implements DataImportStepInterface
{
    /**
     * @var array<string, string>
     */
    protected static array $productOfferReferenceCache = [];

    /**
     * @var list<string>
     */
    protected const REQUIRED_COLUMNS = [
        ProductOfferShipmentTypeDataSetInterface::COLUMN_PRODUCT_OFFER_REFERENCE,
        ProductOfferShipmentTypeDataSetInterface::COLUMN_SHIPMENT_TYPE_KEY,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateRequiredColumns($dataSet);
        $this->validateProductOfferExistence($dataSet[ProductOfferShipmentTypeDataSetInterface::COLUMN_PRODUCT_OFFER_REFERENCE]);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function validateRequiredColumns(DataSetInterface $dataSet): void
    {
        foreach ($dataSet as $column => $value) {
            if (in_array($column, static::REQUIRED_COLUMNS) && $value === '') {
                throw new InvalidDataException(
                    sprintf('Missing required "%s" field.', $column),
                );
            }
        }
    }

    /**
     * @param string $productOfferReference
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    protected function validateProductOfferExistence(string $productOfferReference): void
    {
        if (isset(static::$productOfferReferenceCache[$productOfferReference])) {
            return;
        }
        $productOfferEntity = $this->getProductOfferQuery()
            ->filterByProductOfferReference($productOfferReference)
            ->findOne();

        if (!$productOfferEntity) {
            throw new EntityNotFoundException(
                sprintf('Could not find product offer by product offer reference "%s"', $productOfferReference),
            );
        }

        static::$productOfferReferenceCache[$productOfferReference] = $productOfferReference;
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function getProductOfferQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }
}
