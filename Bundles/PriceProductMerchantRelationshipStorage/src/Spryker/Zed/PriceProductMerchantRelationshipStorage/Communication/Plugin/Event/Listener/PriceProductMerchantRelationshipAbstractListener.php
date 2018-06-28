<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipToCompanyBusinessUnitTableMap;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\Map\SpyPriceProductMerchantRelationshipTableMap;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery;
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
        $businessUnitProducts = $this->getBusinessUnitAbstractProducts($eventTransfers);
        $this->getFacade()->publishAbstractPriceProductByBusinessUnitProducts($businessUnitProducts);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     *
     * @return array
     */
    protected function getBusinessUnitAbstractProducts(array $eventTransfers): array
    {
        $businessUnitProducts = [];

        foreach ($eventTransfers as $eventTransfer) {
            $foreignKeys = $eventTransfer->getForeignKeys();
            $idProductAbstract = null;
            $idMerchantRelationship = null;
            if ($foreignKeys) {
                $idProductAbstract = $foreignKeys[SpyPriceProductMerchantRelationshipTableMap::COL_FK_PRODUCT_ABSTRACT];
                if (!$idProductAbstract) {
                    continue;
                }
                $idMerchantRelationship = $foreignKeys[SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP];
            } else {
                $priceProductMerchantRelationship = SpyPriceProductMerchantRelationshipQuery::create()
                    ->findOneByIdPriceProductMerchantRelationship($eventTransfer->getId());

                if (!$priceProductMerchantRelationship || !$priceProductMerchantRelationship->getFkProductAbstract()) {
                    continue;
                }

                $idProductAbstract = $priceProductMerchantRelationship->getFkProductAbstract();
                $idMerchantRelationship = $priceProductMerchantRelationship->getFkMerchantRelationship();
            }

            $businessUnitIds = SpyMerchantRelationshipToCompanyBusinessUnitQuery::create()
                ->select([
                    SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT,
                ])
                ->filterByFkMerchantRelationship($idMerchantRelationship)
                ->find()
                ->toArray();

            foreach ($businessUnitIds as $businessUnitId) {
                $businessUnitProducts[$businessUnitId][$idProductAbstract] = $idProductAbstract;
            }
        }

        return $businessUnitProducts;
    }
}
