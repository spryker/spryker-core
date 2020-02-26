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
     * @return \Generated\Shared\Transfer\GiftCardAbstractProductConfigurationTransfer[]
     */
    public function getGiftCartAbstractConfigurationsByAbstractSkus(array $abstractSkus): array
    {
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
            ->mapGiftCardProductAbstractConfigurationEntitiesToGiftCardAbstractProductConfigurationTransfers($giftCardProductAbstractConfigurationEntities);
    }

    /**
     * @module Product
     *
     * @param string[] $concreteSkus
     *
     * @return \Generated\Shared\Transfer\GiftCardProductConfigurationTransfer[]
     */
    public function getGiftCardConcreteConfigurationsByConcreteSkus(array $concreteSkus): array
    {
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
            ->mapGiftCardProductConfigurationEntitiesToGiftCardProductConfigurationTransfers($giftCardProductConfigurationEntities);
    }
}
