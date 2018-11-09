<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\PriceProductMerchantRelationshipPriceKeyTransfer;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConfig;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\PriceProductMerchantRelationshipStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\PriceProductMerchantRelationshipStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface getRepository()
 */
class PriceProductMerchantRelationshipAbstractUpdateListener extends AbstractPriceProductMerchantRelationshipPlugin implements EventBulkHandlerInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName): void
    {
        $eventTransfers = $this->filterEventTransfers($eventTransfers);

        if (empty($eventTransfers)) {
            return;
        }

        $priceKeyTransfers = $this->getPriceKeyTransfers($eventTransfers);

        $this->getFacade()
            ->updateAbstractPriceProductByPriceKeys($priceKeyTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param int[] $companyBusinessUnitIdsAndMerchantRelationshipIdsMap
     * @param string[] $priceProductStoreIdsAndStoreNameMap
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipPriceKeyTransfer[]
     */
    protected function mapPriceKeyTransfers(
        array $eventTransfers,
        $companyBusinessUnitIdsAndMerchantRelationshipIdsMap,
        $priceProductStoreIdsAndStoreNameMap
    ): array {
        $priceKeys = [];
        foreach ($eventTransfers as $eventTransfer) {
            $idProductAbstract = $eventTransfer->getForeignKeys()[PriceProductMerchantRelationshipStorageConfig::COL_FK_PRODUCT_ABSTRACT];
            $idMerchantRelationship = $eventTransfer->getForeignKeys()[PriceProductMerchantRelationshipStorageConfig::COL_FK_MERCHANT_RELATIONSHIP];
            $idPriceProductStore = $eventTransfer->getForeignKeys()[PriceProductMerchantRelationshipStorageConfig::COL_FK_PRICE_PRODUCT_STORE];
            $idCompanyBusinessUnit = $companyBusinessUnitIdsAndMerchantRelationshipIdsMap[$idMerchantRelationship];
            $storeName = $priceProductStoreIdsAndStoreNameMap[$idPriceProductStore];
            $priceKey = $this->createPriceUniqueKey($storeName, $idProductAbstract, $idCompanyBusinessUnit);

            $priceKeys[$priceKey] = (new PriceProductMerchantRelationshipPriceKeyTransfer())
                ->setStoreName($storeName)
                ->setIdProduct($idProductAbstract)
                ->setIdCompanyBusinessUnit($idCompanyBusinessUnit);
        }

        return $priceKeys;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return array
     */
    protected function filterEventTransfers(array $eventTransfers): array
    {
        return array_filter($eventTransfers, function (EventEntityTransfer $eventTransfer) {
            $idProductAbstract = $eventTransfer->getForeignKeys()[PriceProductMerchantRelationshipStorageConfig::COL_FK_PRODUCT_ABSTRACT];

            return $idProductAbstract !== null;
        });
    }
}
