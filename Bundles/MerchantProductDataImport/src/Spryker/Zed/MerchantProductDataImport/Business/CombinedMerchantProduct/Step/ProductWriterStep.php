<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generated\Shared\Transfer\SpyProductSearchEntityTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchTableMap;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearch;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductSearch\Dependency\ProductSearchEvents;

class ProductWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    use AssignedProductTypeSupportTrait;

    /**
     * @param \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface $productRepository
     */
    public function __construct(protected MerchantCombinedProductRepositoryInterface $productRepository)
    {
    }

    /**
     * @inheritDoc
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!$this->isAssignedProductTypeSupported($dataSet)) {
            return;
        }

        $spyProductEntity = $this->createOrUpdateProductConcrete($dataSet);

        $this->productRepository->addProductConcrete($spyProductEntity);

        $this->createOrUpdateProductConcreteLocalizedAttributesEntities($dataSet, $spyProductEntity->getIdProduct());
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function createOrUpdateProductConcrete(DataSetInterface $dataSet): SpyProduct
    {
        $idAbstract = $this->productRepository
            ->getIdProductAbstractByAbstractSku($dataSet[MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU]);

        $productConcreteEntityTransfer = $this->getProductConcreteTransfer($dataSet);
        $productConcreteEntityTransfer->setFkProductAbstract($idAbstract);

        $productConcreteEntity = SpyProductQuery::create()
            ->filterBySku($productConcreteEntityTransfer->getSku())
            ->findOneOrCreate();

        $fkProductAbstract = $productConcreteEntity->getFkProductAbstract();

        $productConcreteEntity->fromArray($productConcreteEntityTransfer->modifiedToArray());

        if ($productConcreteEntity->isNew() || $productConcreteEntity->isModified()) {
            $productConcreteEntity->save();
            DataImporterPublisher::addEvent(ProductEvents::PRODUCT_CONCRETE_PUBLISH, $productConcreteEntity->getIdProduct());

            if ($fkProductAbstract !== $idAbstract) {
                DataImporterPublisher::addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $fkProductAbstract);
                DataImporterPublisher::addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $idAbstract);
            }
        }

        return $productConcreteEntity;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param int $idProduct
     *
     * @return void
     */
    protected function createOrUpdateProductConcreteLocalizedAttributesEntities(
        DataSetInterface $dataSet,
        int $idProduct
    ): void {
        $productConcreteLocalizedTransfers = $this->getProductConcreteLocalizedTransfers($dataSet);

        foreach ($productConcreteLocalizedTransfers as $productConcreteLocalizedArray) {
            $productConcreteLocalizedTransfer = $productConcreteLocalizedArray[MerchantCombinedProductConcreteHydratorStep::KEY_LOCALIZED_ATTRIBUTE_TRANSFER];
            $productSearchEntityTransfer = $productConcreteLocalizedArray[MerchantCombinedProductConcreteHydratorStep::KEY_PRODUCT_SEARCH_TRANSFER];

            $productConcreteLocalizedAttributesEntity = SpyProductLocalizedAttributesQuery::create()
                ->filterByFkProduct($idProduct)
                ->filterByFkLocale($productConcreteLocalizedTransfer->getFkLocale())
                ->findOneOrCreate();
            $productConcreteLocalizedAttributesEntity->fromArray($productConcreteLocalizedTransfer->modifiedToArray());

            if ($productConcreteLocalizedAttributesEntity->isNew() || $productConcreteLocalizedAttributesEntity->isModified()) {
                $productConcreteLocalizedAttributesEntity->save();
            }

            $this->createOrUpdateProductConcreteSearchEntities($idProduct, $productSearchEntityTransfer);
        }
    }

    /**
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\SpyProductSearchEntityTransfer $productSearchEntityTransfer
     *
     * @return void
     */
    protected function createOrUpdateProductConcreteSearchEntities(
        int $idProduct,
        SpyProductSearchEntityTransfer $productSearchEntityTransfer
    ): void {
        $productSearchEntity = SpyProductSearchQuery::create()
            ->filterByFkProduct($idProduct)
            ->filterByFkLocale($productSearchEntityTransfer->getFkLocale())
            ->findOneOrCreate();
        $productSearchEntity->fromArray($productSearchEntityTransfer->modifiedToArray());

        $isNewProductSearchEntity = $productSearchEntity->isNew();
        if (!$isNewProductSearchEntity && !$productSearchEntity->isModified()) {
            return;
        }

        $productSearchEntity->save();
        $eventEntityTransfer = $this->mapProductSearchEntityToEventEntityTransfer(
            $productSearchEntity,
            $isNewProductSearchEntity,
            new EventEntityTransfer(),
        );

        DataImporterPublisher::addEvent($eventEntityTransfer->getEventOrFail(), $eventEntityTransfer->getIdOrFail(), $eventEntityTransfer);
    }

    /**
     * @param \Orm\Zed\ProductSearch\Persistence\SpyProductSearch $productSearchEntity
     * @param bool $isNewProductSearchEntity
     * @param \Generated\Shared\Transfer\EventEntityTransfer $eventEntityTransfer
     *
     * @return \Generated\Shared\Transfer\EventEntityTransfer
     */
    protected function mapProductSearchEntityToEventEntityTransfer(
        SpyProductSearch $productSearchEntity,
        bool $isNewProductSearchEntity,
        EventEntityTransfer $eventEntityTransfer
    ): EventEntityTransfer {
        return $eventEntityTransfer
            ->setId($productSearchEntity->getIdProductSearch())
            ->setEvent(
                $isNewProductSearchEntity
                    ? ProductSearchEvents::ENTITY_SPY_PRODUCT_SEARCH_CREATE
                    : ProductSearchEvents::ENTITY_SPY_PRODUCT_SEARCH_UPDATE,
            )
            ->setName(SpyProductSearchTableMap::TABLE_NAME)
            ->setForeignKeys([
                SpyProductSearchTableMap::COL_FK_PRODUCT => $productSearchEntity->getFkProduct(),
                SpyProductSearchTableMap::COL_FK_LOCALE => $productSearchEntity->getFkLocale(),
            ])
            ->setModifiedColumns([
                SpyProductSearchTableMap::COL_IS_SEARCHABLE,
            ]);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string, mixed>
     */
    protected function getProductConcreteLocalizedTransfers(DataSetInterface $dataSet): array
    {
        return $dataSet[MerchantCombinedProductConcreteHydratorStep::DATA_PRODUCT_LOCALIZED_ATTRIBUTE_TRANSFER] ?? [];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer
     */
    protected function getProductConcreteTransfer(DataSetInterface $dataSet): SpyProductEntityTransfer
    {
        return $dataSet[MerchantCombinedProductConcreteHydratorStep::DATA_PRODUCT_CONCRETE_TRANSFER];
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
