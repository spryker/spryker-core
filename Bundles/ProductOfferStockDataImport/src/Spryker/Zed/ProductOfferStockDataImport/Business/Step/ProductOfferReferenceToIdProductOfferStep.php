<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferStockDataImport\Business\Step;

use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferStockDataImport\Business\DataSet\ProductOfferStockDataSetInterface;
use Spryker\Zed\ProductOfferStockDataImport\Dependency\Facade\ProductOfferStockDataImportToProductOfferFacadeInterface;

class ProductOfferReferenceToIdProductOfferStep implements DataImportStepInterface
{
    protected const PRODUCT_OFFER_REFERENCE = ProductOfferStockDataSetInterface::PRODUCT_OFFER_REFERENCE;

    /**
     * @var \Spryker\Zed\ProductOfferStockDataImport\Dependency\Facade\ProductOfferStockDataImportToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOfferStockDataImport\Dependency\Facade\ProductOfferStockDataImportToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(ProductOfferStockDataImportToProductOfferFacadeInterface $productOfferFacade)
    {
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productOfferReference = $dataSet[static::PRODUCT_OFFER_REFERENCE];

        if (!$productOfferReference) {
            throw new DataKeyNotFoundInDataSetException(sprintf(
                '"%s" key must be in the data set. Given: "%s"',
                static::PRODUCT_OFFER_REFERENCE,
                implode(', ', array_keys($dataSet->getArrayCopy())),
            ));
        }

        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())
            ->setProductOfferReference($productOfferReference);

        $productOfferTransfer = $this->productOfferFacade->findOne($productOfferCriteriaTransfer);

        if ($productOfferTransfer === null) {
            throw new EntityNotFoundException(sprintf('Product offer not found for product offer reference %s', $productOfferReference));
        }

        $dataSet[ProductOfferStockDataSetInterface::FK_PRODUCT_OFFER] = $productOfferTransfer->getIdProductOffer();
    }
}
