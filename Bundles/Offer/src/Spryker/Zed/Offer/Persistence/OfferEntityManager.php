<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Persistence;

use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\SpyOfferEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Offer\Persistence\OfferPersistenceFactory getFactory()
 */
class OfferEntityManager extends AbstractEntityManager implements OfferEntityManagerInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function createOffer(OfferTransfer $offerTransfer): OfferTransfer
    {
        $offerEntityTransfer = $this->getFactory()
            ->createOffermapper()
            ->mapOfferToOfferEntity($offerTransfer);

        $offerEntityTransfer->setIdOffer(null);

        /** @var SpyOfferEntityTransfer $offerEntityTransfer */
        $offerEntityTransfer = $this->save($offerEntityTransfer);

        $offerTransfer->setIdOffer(
            $offerEntityTransfer->getIdOffer()
        );

        return $offerTransfer;
    }
}
