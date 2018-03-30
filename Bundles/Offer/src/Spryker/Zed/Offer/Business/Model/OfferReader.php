<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model;

use Generated\Shared\Transfer\OfferListTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Offer\Persistence\OfferRepositoryInterface;

class OfferReader implements OfferReaderInterface
{
    /**
     * @var \Spryker\Zed\Offer\Persistence\OfferRepositoryInterface
     */
    protected $offerRepository;

    /**
     * @var \Spryker\Zed\Offer\Business\Model\OfferPluginExecutorInterface
     */
    protected $offerPluginExecutor;

    /**
     * @param \Spryker\Zed\Offer\Persistence\OfferRepositoryInterface $offerRepository
     * @param \Spryker\Zed\Offer\Business\Model\OfferPluginExecutorInterface $offerPluginExecutor
     */
    public function __construct(
        OfferRepositoryInterface $offerRepository,
        OfferPluginExecutorInterface $offerPluginExecutor
    ) {
        $this->offerRepository = $offerRepository;
        $this->offerPluginExecutor = $offerPluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function getOfferById(OfferTransfer $offerTransfer): OfferTransfer
    {
        $offerTransfer = $this->offerRepository->getOfferById($offerTransfer->getIdOffer());
        $offerTransfer = $this->offerPluginExecutor->hydrateOffer($offerTransfer);

        return $offerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferListTransfer $offerListTransfer
     *
     * @return \Generated\Shared\Transfer\OfferListTransfer
     */
    public function getOfferList(OfferListTransfer $offerListTransfer): OfferListTransfer
    {
        return $this->offerRepository->getOffers($offerListTransfer);
    }
}
