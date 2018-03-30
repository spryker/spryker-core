<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model;

use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Orm\Zed\Offer\Persistence\SpyOffer;
use Spryker\Zed\Offer\OfferConfig;
use Spryker\Zed\Offer\Persistence\OfferEntityManagerInterface;
use Spryker\Zed\Offer\Persistence\OfferRepositoryInterface;

class OfferWriter implements OfferWriterInterface
{
    /**
     * @var \Spryker\Zed\Offer\Persistence\OfferEntityManagerInterface
     */
    protected $offerEntityManager;

    /**
     * @var \Spryker\Zed\Offer\Persistence\OfferRepositoryInterface
     */
    protected $offerRepository;

    /**
     * @var \Spryker\Zed\Offer\OfferConfig
     */
    protected $offerConfig;

    /**
     * @param \Spryker\Zed\Offer\Persistence\OfferEntityManagerInterface $offerEntityManager
     */
    public function __construct(
        OfferEntityManagerInterface $offerEntityManager,
        OfferRepositoryInterface $offerRepository,
        OfferConfig $offerConfig
    ) {
        $this->offerEntityManager = $offerEntityManager;
        $this->offerRepository = $offerRepository;
        $this->offerConfig = $offerConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferResponseTransfer
     */
    public function placeOffer(OfferTransfer $offerTransfer): OfferResponseTransfer
    {
        $offerTransfer->requireQuote();
        $offerTransfer->getQuote()->requireCustomer();

        //TODO: drop Customer object here and fill it from session on order creation

        $offerTransfer->setStatus($this->offerConfig->getStatusInProgress());
        $offerTransfer->setCustomerReference($offerTransfer->getQuote()->getCustomer()->getCustomerReference());

        $offerTransfer = $this->offerEntityManager->saveOffer($offerTransfer);

        $offerTransfer->getQuote()->setCheckoutConfirmed(true);

        return (new OfferResponseTransfer())
            ->setIsSuccessful(true)
            ->setOffer($offerTransfer);
    }

    /**
     * @param int $idOffer
     * @param string $status
     *
     * @return \Generated\Shared\Transfer\OfferResponseTransfer
     */
    public function updateOfferStatus(int $idOffer, string $status): OfferResponseTransfer
    {
        $offerTransfer = $this->offerRepository->getOfferById($idOffer);
        $offerTransfer->setStatus($status);
        $offerTransfer = $this->offerEntityManager->saveOffer($offerTransfer);

        return (new OfferResponseTransfer())
            ->setIsSuccessful(true)
            ->setOffer($offerTransfer);
    }
}
