<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Persistence;

use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\TaxApp\Persistence\TaxAppPersistenceFactory getFactory()
 */
class TaxAppEntityManager extends AbstractEntityManager implements TaxAppEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function saveTaxAppConfig(
        TaxAppConfigTransfer $taxAppConfigTransfer,
        StoreTransfer $storeTransfer
    ): void {
        $taxAppConfigTransfer->requireApiUrl()->requireApplicationId()->requireVendorCode();
        $storeTransfer->requireIdStore();

        $taxAppConfigEntity = $this->getFactory()
            ->createTaxAppConfigQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterByApplicationId($taxAppConfigTransfer->getApplicationId())
            ->findOneOrCreate();

        $taxAppConfigEntity = $this->getFactory()
            ->createTaxAppConfigMapper()
            ->mapTaxAppConfigTransferToTaxAppConfigEntity($taxAppConfigTransfer, $taxAppConfigEntity);

        $taxAppConfigEntity->setFkStore($storeTransfer->getIdStoreOrFail());

        $taxAppConfigEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer
     *
     * @return void
     */
    public function deleteTaxAppConfig(TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer): void
    {
        $taxAppConfigCriteriaTransfer->requireTaxAppConfigConditions();

        $taxAppConfigEntity = $this->getFactory()
            ->createTaxAppConfigQuery()
            ->filterByFkStore_In($taxAppConfigCriteriaTransfer->getTaxAppConfigConditionsOrFail()->getFkStores())
            ->filterByVendorCode_In($taxAppConfigCriteriaTransfer->getTaxAppConfigConditionsOrFail()->getVendorCodes())
            ->findOne();

        if ($taxAppConfigEntity) {
            $taxAppConfigEntity->delete();
        }
    }
}
