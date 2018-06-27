<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipToCompanyBusinessUnitTableMap;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\Map\SpyPriceProductMerchantRelationshipTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\PriceProductMerchantRelationshipStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\PriceProductMerchantRelationshipStorageCommunicationFactory getFactory()
 */
class PriceProductMerchantRelationshipAbstractListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName): void
    {
        $businessUnitProducts = $this->getMerchantRelationshipAbstractProducts($eventTransfers);
        $this->getFacade()->publishAbstractPriceProduct($businessUnitProducts);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     *
     * @return array
     */
    protected function getMerchantRelationshipAbstractProducts(array $eventTransfers): array
    {
        $businessUnitProducts = [];

        foreach ($eventTransfers as $eventTransfer) {
            $foreignKeys = $eventTransfer->getForeignKeys();
            $idProductAbstract = $foreignKeys[SpyPriceProductMerchantRelationshipTableMap::COL_FK_PRODUCT_ABSTRACT];
            if (!$idProductAbstract) {
                continue;
            }
            $idMerchantRelationship = $foreignKeys[SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP];

            $businessUnits = SpyMerchantRelationshipToCompanyBusinessUnitQuery::create()
                ->filterByFkMerchantRelationship($idMerchantRelationship)

                ->find()
                ->toArray();

            $businessUnitIds = array_column($businessUnits, SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT);
            foreach ($businessUnitIds as $businessUnitId) {
                $businessUnitProducts[$businessUnitId][$idProductAbstract] = $idProductAbstract;
            }
        }

        return $businessUnitProducts;
    }
}
