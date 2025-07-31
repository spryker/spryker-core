<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

class ProductMerchantOwnerCheckStep implements DataImportStepInterface
{
    use AssignedProductTypeSupportTrait;

    /**
     * @param \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface $productRepository
     */
    public function __construct(protected MerchantCombinedProductRepositoryInterface $productRepository)
    {
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!$this->isAssignedProductTypeSupported($dataSet)) {
            return;
        }

        $this->checkProductOwnedByMerchant($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function checkProductOwnedByMerchant(DataSetInterface $dataSet): void
    {
        /** @var string $sku */
        $sku = $dataSet[MerchantCombinedProductDataSetInterface::KEY_CONCRETE_SKU];

        /** @var int $merchantId */
        $merchantId = $dataSet[AddMerchantIdKeyStep::KEY_ID_MERCHANT];

        $idProduct = $this->productRepository->findIdProductBySku($sku);
        if (!$idProduct) {
            return;
        }

        /** @var \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery $spyMerchantProductAbstractQuery */
        $spyMerchantProductAbstractQuery = SpyMerchantProductAbstractQuery::create()
            ->useProductAbstractQuery()
                ->useSpyProductQuery()
                    ->filterBySku($sku)
                ->endUse()
            ->endUse();

        $productOwnedByMerchant = $spyMerchantProductAbstractQuery
            ->filterByFkMerchant($merchantId)
            ->exists();

        if (!$productOwnedByMerchant) {
            throw new MerchantCombinedProductException(sprintf(
                'Product with SKU "%s" can only be updated by the merchant who owns it.',
                $sku,
            ));
        }
    }

    /**
     * @return array<string>
     */
    protected function getSupportedAssignedProductTypes(): array
    {
        return [
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_CONCRETE,
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_BOTH,
        ];
    }
}
