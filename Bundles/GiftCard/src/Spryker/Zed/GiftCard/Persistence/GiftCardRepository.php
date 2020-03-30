<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardPersistenceFactory getFactory()
 */
class GiftCardRepository extends AbstractRepository implements GiftCardRepositoryInterface
{
    /**
     * @module Product
     *
     * @param string[] $abstractSkus
     *
     * @return \Generated\Shared\Transfer\GiftCardAbstractProductConfigurationForProductAbstractTransfer[]
     */
    public function getGiftCardAbstractConfigurationsForProductAbstractByAbstractSkus(array $abstractSkus): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\GiftCard\Persistence\SpyGiftCardProductAbstractConfiguration[] $giftCardProductAbstractConfigurationEntities */
        $giftCardProductAbstractConfigurationEntities = $this->getFactory()
            ->createSpyGiftCardProductAbstractConfigurationQuery()
            ->joinWithSpyGiftCardProductAbstractConfigurationLink()
            ->useSpyGiftCardProductAbstractConfigurationLinkQuery()
                ->joinWithSpyProductAbstract()
                ->useSpyProductAbstractQuery()
                    ->filterBySku_In($abstractSkus)
                ->endUse()
            ->endUse()
            ->find();

        return $this->getFactory()
            ->createGiftCardMapper()
            ->mapGiftCardProductAbstractConfigurationEntitiesToGiftCardAbstractProductConfigurationForProductAbstractTransfers($giftCardProductAbstractConfigurationEntities);
    }

    /**
     * @module Product
     *
     * @param string[] $concreteSkus
     *
     * @return \Generated\Shared\Transfer\GiftCardProductConfigurationForProductTransfer[]
     */
    public function getGiftCardConcreteConfigurationsForProductByConcreteSkus(array $concreteSkus): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\GiftCard\Persistence\SpyGiftCardProductConfiguration[] $giftCardProductConfigurationEntities */
        $giftCardProductConfigurationEntities = $this->getFactory()
            ->createSpyGiftCardProductConfigurationQuery()
            ->joinWithSpyGiftCardProductConfigurationLink()
            ->useSpyGiftCardProductConfigurationLinkQuery()
                ->joinWithSpyProduct()
                ->useSpyProductQuery()
                    ->filterBySku_In($concreteSkus)
                ->endUse()
            ->endUse()
            ->find();

        return $this->getFactory()
            ->createGiftCardMapper()
            ->mapGiftCardProductConfigurationEntitiesToGiftCardProductConfigurationForProductTransfers($giftCardProductConfigurationEntities);
    }
}
