<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

class CombinedMerchantProductAbstractWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    use AssignedProductTypeSupportTrait;

    /**
     * @uses \Spryker\Shared\MerchantProductSearch\MerchantProductSearchConfig::MERCHANT_PRODUCT_ABSTRACT_PUBLISH
     *
     * @var string
     */
    protected const MERCHANT_PRODUCT_ABSTRACT_PUBLISH = 'MerchantProduct.merchant_product_abstract.publish';

    /**
     * @uses \Spryker\Shared\MerchantProductStorage\MerchantProductStorageConfig::MERCHANT_PRODUCT_ABSTRACT_PUBLISH
     *
     * @var string
     */
    protected const EVENT_MERCHANT_PRODUCT_ABSTRACT_PUBLISH = 'MerchantProductAbstract.publish';

    /**
     * @var array
     */
    protected const REQUIRED_DATA_SET_KEYS = [
        AddMerchantIdKeyStep::KEY_ID_MERCHANT,
        MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU,
    ];

    /**
     * @param \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface $merchantCombinedProductRepository
     */
    public function __construct(
        protected readonly MerchantCombinedProductRepositoryInterface $merchantCombinedProductRepository
    ) {
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

        $this->saveMerchantProductAbstract($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function saveMerchantProductAbstract(DataSetInterface $dataSet): void
    {
        $idMerchant = $this->getMerchantId($dataSet);
        $idProductAbstract = $this->getProductAbstractId($dataSet);
        $merchantProductAbstractEntity = $this->createMerchantProductAbstractQuery()
            ->filterByFkMerchant($idMerchant)
            ->filterByFkProductAbstract($idProductAbstract)
            ->findOneOrCreate();

        $merchantProductAbstractEntity->save();

        $this->addPublishEvents(static::MERCHANT_PRODUCT_ABSTRACT_PUBLISH, $merchantProductAbstractEntity->getIdMerchantProductAbstract());
        $this->addPublishEvents(static::EVENT_MERCHANT_PRODUCT_ABSTRACT_PUBLISH, $merchantProductAbstractEntity->getIdMerchantProductAbstract());
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return int
     */
    protected function getProductAbstractId(DataSetInterface $dataSet): int
    {
        $abstractSku = $dataSet[MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU];

        return $this->merchantCombinedProductRepository->getIdProductAbstractByAbstractSku($abstractSku);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return int
     */
    protected function getMerchantId(DataSetInterface $dataSet): int
    {
        return $dataSet[AddMerchantIdKeyStep::KEY_ID_MERCHANT];
    }

    /**
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
     */
    protected function createMerchantProductAbstractQuery(): SpyMerchantProductAbstractQuery
    {
        return SpyMerchantProductAbstractQuery::create();
    }

    /**
     * @return array<string>
     */
    protected function getSupportedAssignedProductTypes(): array
    {
        return [
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_BOTH,
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_ABSTRACT,
        ];
    }
}
