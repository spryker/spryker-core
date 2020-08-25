<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferValidityDataImport\Business\Step;

use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferValidityDataImport\Business\DataSet\ProductOfferValidityDataSetInterface;
use Spryker\Zed\ProductOfferValidityDataImport\Dependency\Facade\ProductOfferValidityDataImportToProductOfferFacadeInterface;

class ProductOfferReferenceToIdProductOfferStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferValidityDataImport\Dependency\Facade\ProductOfferValidityDataImportToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOfferValidityDataImport\Dependency\Facade\ProductOfferValidityDataImportToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(ProductOfferValidityDataImportToProductOfferFacadeInterface $productOfferFacade)
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
        $productOfferReference = $dataSet[ProductOfferValidityDataSetInterface::PRODUCT_OFFER_REFERENCE];

        if (!$productOfferReference) {
            throw new DataKeyNotFoundInDataSetException(sprintf(
                '"%s" key must be in the data set. Given: "%s"',
                ProductOfferValidityDataSetInterface::PRODUCT_OFFER_REFERENCE,
                implode(', ', array_keys($dataSet->getArrayCopy()))
            ));
        }

        $productOfferCriteriaFilterTransfer = (new ProductOfferCriteriaFilterTransfer())
            ->setProductOfferReference($productOfferReference);

        $productOfferTransfer = $this->productOfferFacade->findOne($productOfferCriteriaFilterTransfer);

        if ($productOfferTransfer === null) {
            throw new EntityNotFoundException(sprintf('Product offer not found for product offer reference %s', $productOfferReference));
        }

        $dataSet[ProductOfferValidityDataSetInterface::FK_PRODUCT_OFFER] = $productOfferTransfer->getIdProductOffer();
    }
}
