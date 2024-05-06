<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Persistence;

use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Orm\Zed\TaxApp\Persistence\SpyTaxAppConfig;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Propel\Persistence\BatchProcessor\ActiveRecordBatchProcessorTrait;

/**
 * @method \Spryker\Zed\TaxApp\Persistence\TaxAppPersistenceFactory getFactory()
 */
class TaxAppEntityManager extends AbstractEntityManager implements TaxAppEntityManagerInterface
{
    use ActiveRecordBatchProcessorTrait;

    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return void
     */
    public function saveTaxAppConfig(
        TaxAppConfigTransfer $taxAppConfigTransfer,
        array $storeTransfers
    ): void {
        foreach ($storeTransfers as $storeTransfer) {
            $taxAppConfigTransfer->requireApiUrls()->requireApplicationId()->requireVendorCode();
            $taxAppConfigEntityCollection = $this->getTaxAppConfigEntityCollectionByTaxAppConfigAndStore($taxAppConfigTransfer, $storeTransfer);

            if ($taxAppConfigEntityCollection->count() === 0) {
                $taxAppConfigEntity = new SpyTaxAppConfig();
                $taxAppConfigEntity = $this->getFactory()
                    ->createTaxAppConfigMapper()
                    ->mapTaxAppConfigTransferToTaxAppConfigEntity($taxAppConfigTransfer, $taxAppConfigEntity);

                $taxAppConfigEntity->setFkStore($storeTransfer->getIdStore());

                $taxAppConfigEntity->save();

                continue;
            }

            foreach ($taxAppConfigEntityCollection as $taxAppConfigEntity) {
                $taxAppConfigEntity = $this->getFactory()
                    ->createTaxAppConfigMapper()
                    ->mapTaxAppConfigTransferToTaxAppConfigEntity($taxAppConfigTransfer, $taxAppConfigEntity);

                $taxAppConfigEntity->setFkStore($storeTransfer->getIdStore());

                $this->persist($taxAppConfigEntity);
            }

            $this->commit();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer
     *
     * @return void
     */
    public function deleteTaxAppConfig(TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer): void
    {
        $taxAppConfigCriteriaTransfer->getTaxAppConfigConditionsOrFail()->requireVendorCodes();

        $taxAppConfigEntityCollection = $this->getTaxAppConfigEntityCollectionByTaxAppConfigCriteria($taxAppConfigCriteriaTransfer);

        foreach ($taxAppConfigEntityCollection as $taxAppConfigEntity) {
            $taxAppConfigEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Propel\Runtime\Collection\Collection
     */
    protected function getTaxAppConfigEntityCollectionByTaxAppConfigAndStore(
        TaxAppConfigTransfer $taxAppConfigTransfer,
        StoreTransfer $storeTransfer
    ): Collection {
        if ($storeTransfer->getIdStore() === null) {
            return $this->getFactory()
                ->createTaxAppConfigQuery()
                ->filterByVendorCode($taxAppConfigTransfer->getVendorCode())
                ->find();
        }

        return $this->getFactory()
            ->createTaxAppConfigQuery()
            ->filterByFkStore($storeTransfer->getIdStoreOrFail())
            ->filterByVendorCode($taxAppConfigTransfer->getVendorCode())
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer
     *
     * @return \Propel\Runtime\Collection\Collection
     */
    protected function getTaxAppConfigEntityCollectionByTaxAppConfigCriteria(TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer): Collection
    {
        $taxAppConfigConditionTransfer = $taxAppConfigCriteriaTransfer->getTaxAppConfigConditionsOrFail();

        return $this->getFactory()
            ->createTaxAppConfigQuery()
            ->filterByVendorCode_In($taxAppConfigConditionTransfer->getVendorCodes())
            ->find();
    }
}
