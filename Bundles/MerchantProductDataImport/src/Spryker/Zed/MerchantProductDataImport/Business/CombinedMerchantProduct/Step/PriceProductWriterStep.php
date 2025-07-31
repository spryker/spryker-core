<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\Product\Dependency\ProductEvents;

class PriceProductWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    use AssignedProductTypeSupportTrait;

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

        /** @var array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers */
        $priceProductTransfers = $dataSet[PriceProductHydratorStep::KEY_PRICE_PRODUCT_TRANSFERS] ?? [];

        $this->assertNewProductAbstractHasPrice($dataSet, $priceProductTransfers);

        if (!$priceProductTransfers) {
            return;
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $this->savePriceProduct($priceProductTransfer);
            $this->savePriceProductStore($priceProductTransfer);
            $this->savePriceProductDefault($priceProductTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    protected function savePriceProductStore(PriceProductTransfer $priceProductTransfer): void
    {
        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();
        $spyPriceProductStore = SpyPriceProductStoreQuery::create()
            ->filterByFkStore($moneyValueTransfer->getFkStore())
            ->filterByFkCurrency($moneyValueTransfer->getFkCurrency())
            ->filterByFkPriceProduct($priceProductTransfer->getIdPriceProduct())
            ->findOneOrCreate();

        $spyPriceProductStore
            ->setGrossPrice($moneyValueTransfer->getGrossAmount())
            ->setNetPrice($moneyValueTransfer->getNetAmount());
        $spyPriceProductStore->save();

        $moneyValueTransfer->setIdEntity((int)$spyPriceProductStore->getIdPriceProductStore());
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    protected function savePriceProductDefault(PriceProductTransfer $priceProductTransfer): void
    {
        $idPriceProductStore = $priceProductTransfer->getMoneyValueOrFail()->getIdEntityOrFail();
        $spyPriceProductDefault = SpyPriceProductDefaultQuery::create()
            ->filterByFkPriceProductStore($idPriceProductStore)
            ->findOneOrCreate();

        $spyPriceProductDefault->save();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    protected function savePriceProduct(PriceProductTransfer $priceProductTransfer): void
    {
        $idPriceType = $priceProductTransfer->getFkPriceType();
        $priceProductQuery = SpyPriceProductQuery::create()
            ->filterByFkPriceType($idPriceType);

        if ($priceProductTransfer->getIdProductAbstract()) {
            $idProductAbstract = $priceProductTransfer->getIdProductAbstractOrFail();
            $priceProductQuery->filterByFkProductAbstract($idProductAbstract);

            DataImporterPublisher::addEvent(PriceProductEvents::PRICE_ABSTRACT_PUBLISH, $idProductAbstract);
            DataImporterPublisher::addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $idProductAbstract);
        } else {
            $idProduct = $priceProductTransfer->getIdProductOrFail();
            $priceProductQuery->filterByFkProduct($idProduct);

            DataImporterPublisher::addEvent(PriceProductEvents::PRICE_CONCRETE_PUBLISH, $idProduct);
        }

        $spyPriceProduct = $priceProductQuery->findOneOrCreate();
        $spyPriceProduct->save();

        $priceProductTransfer->setIdPriceProduct($spyPriceProduct->getIdPriceProduct());
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function assertNewProductAbstractHasPrice(DataSetInterface $dataSet, array $priceProductTransfers): void
    {
        if ($this->isNewProductAbstract($dataSet) && !count($priceProductTransfers)) {
            throw new MerchantCombinedProductException(
                'New product abstract must have at least one price defined.',
            );
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    protected function isNewProductAbstract(DataSetInterface $dataSet): bool
    {
        return $dataSet[DefineIsNewProductStep::DATA_KEY_IS_NEW_PRODUCT_ABSTRACT];
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
