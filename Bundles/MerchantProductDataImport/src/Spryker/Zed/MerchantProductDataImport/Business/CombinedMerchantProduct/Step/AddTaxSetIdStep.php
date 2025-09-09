<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\ErrorTransfer;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

class AddTaxSetIdStep implements DataImportStepInterface
{
    use AssignedProductTypeSupportTrait;

    /**
     * @var string
     */
    public const KEY_ID_TAX_SET = 'ID_TAX_SET';

    /**
     * @var array<string, int> Keys are tax set names, values are tax set ids
     */
    protected array $resolved = [];

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

        $this->addTaxSetId($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function addTaxSetId(DataSetInterface $dataSet): void
    {
        if (!$this->isAssignedProductTypeSupported($dataSet)) {
            return;
        }

        if (
            !isset($dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_TAX_SET_NAME])
            || empty($dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_TAX_SET_NAME])
        ) {
            /** @var string $sku */
            $sku = $dataSet[MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU];
            $idProductAbstract = $this->productRepository->findIdProductAbstractByAbstractSku($sku);
            if ($idProductAbstract) {
                return;
            }

            throw MerchantCombinedProductException::createWithError(
                (new ErrorTransfer())
                    ->setMessage('Required key "%s%" is missing in data set.')
                    ->setParameters(['%s%' => MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_TAX_SET_NAME]),
            );
        }

        /** @var string $taxSetName */
        $taxSetName = $dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_TAX_SET_NAME];

        $dataSet[static::KEY_ID_TAX_SET] = $this->getIdTaxSet($taxSetName);
    }

    /**
     * @param string $taxSetName
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return int
     */
    protected function getIdTaxSet(string $taxSetName): int
    {
        if (!isset($this->resolved[$taxSetName])) {
            $taxSetEntity = SpyTaxSetQuery::create()
                ->filterByName($taxSetName)
                ->findOne();

            if (!$taxSetEntity) {
                throw MerchantCombinedProductException::createWithError(
                    (new ErrorTransfer())
                        ->setMessage('Tax set with name "%s%" not found.')
                        ->setParameters(['%s%' => $taxSetName]),
                );
            }

            $this->resolved[$taxSetName] = $taxSetEntity->getIdTaxSet();
        }

        return $this->resolved[$taxSetName];
    }

    /**
     * @return array<string>
     */
    protected function getSupportedAssignedProductTypes(): array
    {
        return [
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_ABSTRACT,
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_BOTH,
        ];
    }
}
