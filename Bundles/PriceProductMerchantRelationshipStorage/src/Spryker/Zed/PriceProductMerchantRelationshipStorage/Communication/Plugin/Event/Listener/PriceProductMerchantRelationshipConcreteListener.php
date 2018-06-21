<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\PriceProductMerchantRelationship\Persistence\Map\SpyPriceProductMerchantRelationshipTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductMerchantRelationship\Dependency\PriceProductMerchantRelationshipEvents;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\PriceProductMerchantRelationshipStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\PriceProductMerchantRelationshipStorageCommunicationFactory getFactory()
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

        if ($eventName === PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_DELETE) {
            $merchantRelationshipProducts = $this->getMerchantRelationshipConcreteProducts($eventTransfers);
            if ($merchantRelationshipProducts) {
                $this->getFacade()->unpublishConcretePriceProduct($merchantRelationshipProducts);
            }

            return;
        }

        $priceProductStoreIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys(
                $eventTransfers,
                SpyPriceProductMerchantRelationshipTableMap::COL_FK_PRICE_PRODUCT_STORE
            );

        $this->getFacade()->publishConcretePriceProduct($priceProductStoreIds);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     *
     * @return array
     */
    protected function getMerchantRelationshipConcreteProducts(array $eventTransfers): array
    {
        $merchantRelationshipProducts = [];

        foreach ($eventTransfers as $eventTransfer) {
            $foreignKeys = $eventTransfer->getForeignKeys();
            $idProduct = $foreignKeys[SpyPriceProductMerchantRelationshipTableMap::COL_FK_PRODUCT];
            if (!$idProduct) {
                continue;
            }
            $idMerchantRelationship = $foreignKeys[SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP];
            if (!isset($merchantRelationshipProducts[$idMerchantRelationship][$idProduct])) {
                $merchantRelationshipProducts[$idMerchantRelationship][$idProduct] = $idProduct;
            }
        }

        return $merchantRelationshipProducts;
    }
}
