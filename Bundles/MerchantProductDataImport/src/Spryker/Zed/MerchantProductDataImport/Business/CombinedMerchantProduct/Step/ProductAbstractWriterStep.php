<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\Url\Dependency\UrlEvents;

class ProductAbstractWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    use AssignedProductTypeSupportTrait;

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_DRAFT
     *
     * @var string
     */
    protected const DEFAULT_APPROVAL_STATUS = 'draft';

    /**
     * @var string
     */
    protected const COLUMN_APPROVAL_STATUS = 'approval_status';

    /**
     * @var array<string, bool> Keys are product column names
     */
    protected static array $isProductAbstractColumnExists;

    /**
     * @param \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface $merchantCombinedProductRepository
     */
    public function __construct(protected MerchantCombinedProductRepositoryInterface $merchantCombinedProductRepository)
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

        $productAbstractEntity = $this->createOrUpdateProductAbstract($dataSet);

        $this->merchantCombinedProductRepository->addProductAbstract($productAbstractEntity);

        $this->createOrUpdateProductAbstractLocalizedAbstract($dataSet, $productAbstractEntity->getIdProductAbstract());
        $this->createOrUpdateProductCategories($dataSet, $productAbstractEntity->getIdProductAbstract());
        $this->createOrUpdateProductUrls($dataSet, $productAbstractEntity->getIdProductAbstract());

        DataImporterPublisher::addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $productAbstractEntity->getIdProductAbstract());
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function createOrUpdateProductAbstract(DataSetInterface $dataSet): SpyProductAbstract
    {
        $productAbstractEntityTransfer = $this->getProductAbstractTransfer($dataSet);

        $productAbstractEntity = SpyProductAbstractQuery::create()
            ->filterBySku($productAbstractEntityTransfer->getSku())
            ->findOneOrCreate();

        if ($productAbstractEntity->isNew() && $this->productAbstractColumnExists(static::COLUMN_APPROVAL_STATUS)) {
            $productAbstractEntity->setApprovalStatus(static::DEFAULT_APPROVAL_STATUS);
        }

        $productAbstractEntity->fromArray($productAbstractEntityTransfer->modifiedToArray());

        if ($productAbstractEntity->isNew() || $productAbstractEntity->isModified()) {
            $productAbstractEntity->save();

            DataImporterPublisher::addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $productAbstractEntity->getIdProductAbstract());
        }

        return $productAbstractEntity;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function createOrUpdateProductAbstractLocalizedAbstract(
        DataSetInterface $dataSet,
        int $idProductAbstract
    ): void {
        $productAbstractLocalizedTransfers = $this->getProductAbstractLocalizedTransfers($dataSet);

        foreach ($productAbstractLocalizedTransfers as $productAbstractLocalizedArray) {
            $productAbstractLocalizedTransfer = $productAbstractLocalizedArray[MerchantCombinedProductAbstractHydratorStep::KEY_PRODUCT_ABSTRACT_LOCALIZED_TRANSFER];

            $idLocale = $productAbstractLocalizedTransfer->getFkLocale();

            $productAbstractLocalizedAttributesEntity = SpyProductAbstractLocalizedAttributesQuery::create()
                ->filterByFkProductAbstract($idProductAbstract)
                ->filterByFkLocale($idLocale)
                ->findOneOrCreate();

            $productAbstractLocalizedAttributesEntity->fromArray($productAbstractLocalizedTransfer->modifiedToArray());

            if (!$productAbstractLocalizedAttributesEntity->isNew() && !$productAbstractLocalizedAttributesEntity->isModified()) {
                continue;
            }

            $productAbstractLocalizedAttributesEntity->save();
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function createOrUpdateProductCategories(DataSetInterface $dataSet, int $idProductAbstract): void
    {
        $productCategoryTransfers = $this->getProductCategoryTransfers($dataSet);

        foreach ($productCategoryTransfers as $productCategoryArray) {
            $productCategoryTransfer = $productCategoryArray[MerchantCombinedProductAbstractHydratorStep::KEY_PRODUCT_CATEGORY_TRANSFER];

            $productCategoryEntity = SpyProductCategoryQuery::create()
                ->filterByFkProductAbstract($idProductAbstract)
                ->filterByFkCategory($productCategoryTransfer->getFkCategory())
                ->findOneOrCreate();

            $productCategoryEntity->fromArray($productCategoryTransfer->modifiedToArray());

            if (!$productCategoryEntity->isNew() && !$productCategoryEntity->isModified()) {
                continue;
            }

            $productCategoryEntity->save();

            DataImporterPublisher::addEvent(ProductCategoryEvents::PRODUCT_CATEGORY_PUBLISH, $idProductAbstract);
            DataImporterPublisher::addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $idProductAbstract);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function createOrUpdateProductUrls(DataSetInterface $dataSet, int $idProductAbstract): void
    {
        $productUrlTransfers = $this->getProductUrlTransfers($dataSet);

        foreach ($productUrlTransfers as $productUrlArray) {
            $productUrlTransfer = $productUrlArray[MerchantCombinedProductAbstractHydratorStep::KEY_PRODUCT_URL_TRANSFER];

            $productUrl = $productUrlTransfer->getUrl();
            $idLocale = $productUrlTransfer->getFkLocale();

            $this->cleanupRedirectUrls($productUrl);

            $urlEntity = SpyUrlQuery::create()
                ->filterByFkLocale($idLocale)
                ->filterByFkResourceProductAbstract($idProductAbstract)
                ->findOneOrCreate();

            $urlEntity->fromArray($productUrlTransfer->modifiedToArray());

            if (!$urlEntity->isNew() && !$urlEntity->isModified()) {
                continue;
            }

            $this->assertUrlIsNotTaken($productUrl);
            $urlEntity->save();

            $this->merchantCombinedProductRepository->markProductUrlUnavailable($productUrl);

            DataImporterPublisher::addEvent(UrlEvents::URL_PUBLISH, $urlEntity->getIdUrl());
        }
    }

    /**
     * @param string $productUrl
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function assertUrlIsNotTaken(string $productUrl): void
    {
        $isProductUrlAvailable = $this->merchantCombinedProductRepository->isProductUrlAvailable($productUrl);

        if (!$isProductUrlAvailable) {
            throw MerchantCombinedProductException::createWithError(
                (new ErrorTransfer())
                    ->setMessage('Product URL "%s%" is already taken.')
                    ->setParameters(['%s%' => $productUrl]),
            );
        }
    }

    /**
     * @param string $abstractProductUrl
     *
     * @return void
     */
    protected function cleanupRedirectUrls(string $abstractProductUrl): void
    {
        SpyUrlQuery::create()
            ->filterByUrl($abstractProductUrl)
            ->filterByFkResourceRedirect(null, Criteria::ISNOTNULL)
            ->delete();
    }

    /**
     * @param string $columnName
     *
     * @return bool
     */
    protected function productAbstractColumnExists(string $columnName): bool
    {
        if (isset(static::$isProductAbstractColumnExists[$columnName])) {
            return static::$isProductAbstractColumnExists[$columnName];
        }
        $isColumnExists = SpyProductAbstractTableMap::getTableMap()->hasColumn($columnName);
        static::$isProductAbstractColumnExists[$columnName] = $isColumnExists;

        return $isColumnExists;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractEntityTransfer
     */
    protected function getProductAbstractTransfer(DataSetInterface $dataSet): SpyProductAbstractEntityTransfer
    {
        return $dataSet[MerchantCombinedProductAbstractHydratorStep::DATA_PRODUCT_ABSTRACT_TRANSFER];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string, mixed>
     */
    protected function getProductAbstractLocalizedTransfers(DataSetInterface $dataSet): array
    {
        return $dataSet[MerchantCombinedProductAbstractHydratorStep::DATA_PRODUCT_ABSTRACT_LOCALIZED_TRANSFER];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string, mixed>
     */
    protected function getProductCategoryTransfers(DataSetInterface $dataSet): array
    {
        return $dataSet[MerchantCombinedProductAbstractHydratorStep::DATA_PRODUCT_CATEGORY_TRANSFER];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string, mixed>
     */
    protected function getProductUrlTransfers(DataSetInterface $dataSet): array
    {
        return $dataSet[MerchantCombinedProductAbstractHydratorStep::DATA_PRODUCT_URL_TRANSFER];
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
