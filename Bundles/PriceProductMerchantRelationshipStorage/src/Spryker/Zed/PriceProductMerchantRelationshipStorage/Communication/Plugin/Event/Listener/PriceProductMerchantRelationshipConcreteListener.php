<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\PriceProductMerchantRelationship\Persistence\Map\SpyPriceProductMerchantRelationshipTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\PriceProductMerchantRelationshipStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\PriceProductMerchantRelationshipStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface getRepository()
 */
class PriceProductMerchantRelationshipConcreteListener extends AbstractPlugin implements EventBulkHandlerInterface
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
        $businessUnitProducts = $this->getBusinessUnitConcreteProducts($eventTransfers);
        $this->getFacade()->publishConcretePriceProductByBusinessUnitProducts($businessUnitProducts);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     *
     * @return array
     */
    protected function getBusinessUnitConcreteProducts(array $eventTransfers): array
    {
        $businessUnitProducts = [];

        foreach ($eventTransfers as $eventTransfer) {
            $foreignKeys = $eventTransfer->getForeignKeys();
            $idProduct = null;
            $idMerchantRelationship = null;
            if ($foreignKeys) {
                $idProduct = $foreignKeys[SpyPriceProductMerchantRelationshipTableMap::COL_FK_PRODUCT];
                if (!$idProduct) {
                    continue;
                }
                $idMerchantRelationship = $foreignKeys[SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP];
            } else {
                $priceProductMerchantRelationship = $this->getRepository()
                    ->findPriceProductMerchantRelationship($eventTransfer->getId());

                if (!$priceProductMerchantRelationship || !$priceProductMerchantRelationship->getFkProduct()) {
                    continue;
                }

                $idProduct = $priceProductMerchantRelationship->getFkProduct();
                $idMerchantRelationship = $priceProductMerchantRelationship->getFkMerchantRelationship();
            }
            $companyBusinessUnitIds = $this->getRepository()
                ->findCompanyBusinessUnitIdsByMerchantRelationship($idMerchantRelationship);

            foreach ($companyBusinessUnitIds as $businessUnitId) {
                $businessUnitProducts[$businessUnitId][$idProduct] = $idProduct;
            }
        }

        return $businessUnitProducts;
    }
}
