<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface;

class MerchantCombinedProductUniqueCheckStep implements DataImportStepInterface
{
    /**
     * @var array<string> Keys are concrete product sku values.
     */
    protected $skuProductConcreteList = [];

    /**
     * @var array<string, true> Keys are abstract product sku values. Values are set to "true" when abstract product added.
     */
    protected $skuProductAbstractList = [];

    /**
     * @param \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface $productRepository
     */
    public function __construct(
        protected MerchantCombinedProductRepositoryInterface $productRepository
    ) {
        $this->skuProductConcreteList = array_flip($productRepository->getSkuProductConcreteList());
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->checkSkuProductAlreadyExists($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function checkSkuProductAlreadyExists(DataSetInterface $dataSet): void
    {
        /** @var string $sku */
        $sku = $dataSet[MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU];

        if (isset($this->skuProductConcreteList[$sku])) {
            throw MerchantCombinedProductException::createWithError(
                (new ErrorTransfer())
                    ->setMessage('Concrete product with SKU "%s%" already imported.')
                    ->setParameters(['%s%' => $sku]),
            );
        }

        if (isset($this->skuProductAbstractList[$sku])) {
            throw MerchantCombinedProductException::createWithError(
                (new ErrorTransfer())
                    ->setMessage('Abstract product with SKU "%s%" has been already imported.')
                    ->setParameters(['%s%' => $sku]),
            );
        }

        $this->skuProductAbstractList[$sku] = true;
    }
}
