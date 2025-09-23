<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\ErrorTransfer;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface;

class ProductAbstractMerchantOwnerCheckStep implements DataImportStepInterface
{
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
        $this->checkProductAbstractOwnedByMerchant($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function checkProductAbstractOwnedByMerchant(DataSetInterface $dataSet): void
    {
        /** @var string $abstractSku */
        $abstractSku = $dataSet[MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU];

        /** @var int $merchantId */
        $merchantId = $dataSet[AddMerchantIdKeyStep::KEY_ID_MERCHANT];

        $idProductAbstract = $this->productRepository->findIdProductAbstractByAbstractSku($abstractSku);
        if (!$idProductAbstract) {
            return;
        }

        $productOwnedByMerchant = SpyMerchantProductAbstractQuery::create()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkMerchant($merchantId)
            ->exists();

        if (!$productOwnedByMerchant) {
            throw MerchantCombinedProductException::createWithError(
                (new ErrorTransfer())
                    ->setMessage('Product abstract with SKU "%s%" can only be updated by the merchant who owns it.')
                    ->setParameters(['%s%' => $abstractSku]),
            );
        }
    }
}
