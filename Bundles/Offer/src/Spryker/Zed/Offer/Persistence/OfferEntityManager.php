<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Persistence;

use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Offer\Persistence\OfferPersistenceFactory getFactory()
 */
class OfferEntityManager extends AbstractEntityManager implements OfferEntityManagerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function createOffer(OfferTransfer $offerTransfer): OfferTransfer
    {
        $offerEntityTransfer = $this->getFactory()
            ->createOfferMapper()
            ->mapOfferToOfferEntity($offerTransfer);

        $offerEntityTransfer->setIdOffer(null);

        /** @var \Generated\Shared\Transfer\SpyOfferEntityTransfer $offerEntityTransfer */
        $offerEntityTransfer = $this->save($offerEntityTransfer);

        $offerTransfer->setIdOffer(
            $offerEntityTransfer->getIdOffer()
        );

        return $offerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function updateOffer(OfferTransfer $offerTransfer): OfferTransfer
    {
        $offerEntityTransfer = $this->getFactory()
            ->createOfferMapper()
            ->mapOfferToOfferEntity($offerTransfer);

        /** @var \Generated\Shared\Transfer\SpyOfferEntityTransfer $offerEntityTransfer */
        $this->save($offerEntityTransfer);

        return $offerTransfer;
    }
}
