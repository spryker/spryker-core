<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event;

use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\Map\SpyPriceProductConcreteMerchantRelationshipStorageTableMap;
use Spryker\Shared\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConstants;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceRepositoryPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductMerchantRelationship\Dependency\PriceProductMerchantRelationshipEvents;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\PriceProductMerchantRelationshipStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\PriceProductMerchantRelationshipStorageCommunicationFactory getFactory()
 */
class ProductConcreteMeasurementUnitEventResourceRepositoryPlugin extends AbstractPlugin implements EventResourceRepositoryPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return PriceProductMerchantRelationshipStorageConstants::PRICE_PRODUCT_CONCRETE_MERCHANT_RELATIONSHIP_RESOURCE_NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function getData(array $ids = []): array
    {
        if (!empty($ids)) {
            return $this->getRepository()->findPriceProductMerchantRelationshipByIds($ids);
        }

        return $this->getRepository()->findAllPriceProductMerchantRelationshipByIds();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return PriceProductMerchantRelationshipEvents::PRICE_CONCRETE_PUBLISH;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return SpyPriceProductConcreteMerchantRelationshipStorageTableMap::COL_FK_COMPANY_BUSINESS_UNIT;
    }
}
