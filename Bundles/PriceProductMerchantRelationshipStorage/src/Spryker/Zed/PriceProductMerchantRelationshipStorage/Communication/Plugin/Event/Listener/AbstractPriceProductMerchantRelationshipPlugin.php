<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConfig;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\PriceProductMerchantRelationshipStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\PriceProductMerchantRelationshipStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface getRepository()
 */
abstract class AbstractPriceProductMerchantRelationshipPlugin extends AbstractPlugin
{
    protected const PRICE_KEY_SEPARATOR = ':';

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipPriceKeyTransfer[]
     */
    protected function getPriceKeyTransfers(array $eventTransfers): array
    {
        $merchantRelationshipIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventTransfers, PriceProductMerchantRelationshipStorageConfig::COL_FK_MERCHANT_RELATIONSHIP);

        $companyBusinessUnitIdsAndMerchantRelationshipIdsMap = $this->getRepository()
            ->findCompanyBusinessUnitIdsByMerchantRelationshipIds($merchantRelationshipIds);

        $priceProductStoreIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventTransfers, PriceProductMerchantRelationshipStorageConfig::COL_FK_PRICE_PRODUCT_STORE);

        $priceProductStoreIdsAndStoreNameMap = $this->getRepository()
            ->findStoreNamesByPriceProductStoreIds($priceProductStoreIds);

        $priceKeys = $this->mapPriceKeyTransfers(
            $eventTransfers,
            $companyBusinessUnitIdsAndMerchantRelationshipIdsMap,
            $priceProductStoreIdsAndStoreNameMap
        );

        return $priceKeys;
    }

    /**
     * @param string $storeName
     * @param int $idProduct
     * @param int $idCompanyBusinessUnit
     *
     * @return string
     */
    protected function createPriceUniqueKey(string $storeName, int $idProduct, int $idCompanyBusinessUnit): string
    {
        return implode(static::PRICE_KEY_SEPARATOR, [
            $storeName,
            $idProduct,
            $idCompanyBusinessUnit,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param int[] $companyBusinessUnitIdsAndMerchantRelationshipIdsMap
     * @param string[] $priceProductStoreIdsAndStoreNameMap
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipPriceKeyTransfer[]
     */
    abstract protected function mapPriceKeyTransfers(
        array $eventTransfers,
        $companyBusinessUnitIdsAndMerchantRelationshipIdsMap,
        $priceProductStoreIdsAndStoreNameMap
    ): array;

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return array
     */
    abstract protected function filterEventTransfers(array $eventTransfers): array;
}
