<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface;

class DefineIsNewProductStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    public const DATA_KEY_IS_NEW_PRODUCT_ABSTRACT = 'DATA_IS_NEW_PRODUCT_ABSTRACT';

    /**
     * @var string
     */
    public const DATA_KEY_IS_NEW_PRODUCT = 'DATA_IS_NEW_PRODUCT';

    /**
     * @param \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface $merchantCombinedProductRepository
     */
    public function __construct(protected MerchantCombinedProductRepositoryInterface $merchantCombinedProductRepository)
    {
    }

    /**
     * @inheritDoc
     */
    public function execute(DataSetInterface $dataSet)
    {
        $this->defineIsNewProductAbstract($dataSet);
        $this->defineIsNewProduct($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function defineIsNewProductAbstract(DataSetInterface $dataSet): void
    {
        $abstractSku = $dataSet[MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU];
        $idProductAbstract = $this->merchantCombinedProductRepository->findIdProductAbstractByAbstractSku($abstractSku);

        $dataSet[static::DATA_KEY_IS_NEW_PRODUCT_ABSTRACT] = $idProductAbstract === null;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function defineIsNewProduct(DataSetInterface $dataSet): void
    {
        if (
            !isset($dataSet[MerchantCombinedProductDataSetInterface::KEY_CONCRETE_SKU])
            || empty($dataSet[MerchantCombinedProductDataSetInterface::KEY_CONCRETE_SKU])
        ) {
            $dataSet[static::DATA_KEY_IS_NEW_PRODUCT] = false;

            return;
        }

        $concreteSku = $dataSet[MerchantCombinedProductDataSetInterface::KEY_CONCRETE_SKU];
        $idProduct = $this->merchantCombinedProductRepository->findIdProductBySku($concreteSku);

        $dataSet[static::DATA_KEY_IS_NEW_PRODUCT] = $idProduct === null;
    }
}
