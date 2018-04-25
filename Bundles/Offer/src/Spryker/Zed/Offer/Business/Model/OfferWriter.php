<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model;

use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;
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
     * @var \Spryker\Zed\Offer\Business\Model\OfferPluginExecutorInterface
     */
    protected $offerPluginExecutor;

    /**
     * @param \Spryker\Zed\Offer\Persistence\OfferEntityManagerInterface $offerEntityManager
     * @param \Spryker\Zed\Offer\Persistence\OfferRepositoryInterface $offerRepository
     * @param \Spryker\Zed\Offer\OfferConfig $offerConfig
     * @param \Spryker\Zed\Offer\Business\Model\OfferPluginExecutorInterface $offerPluginExecutor
     */
    public function __construct(
        OfferEntityManagerInterface $offerEntityManager,
        OfferRepositoryInterface $offerRepository,
        OfferConfig $offerConfig,
        OfferPluginExecutorInterface $offerPluginExecutor
    ) {
        $this->offerEntityManager = $offerEntityManager;
        $this->offerRepository = $offerRepository;
        $this->offerConfig = $offerConfig;
        $this->offerPluginExecutor = $offerPluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferResponseTransfer
     */
    public function createOffer(OfferTransfer $offerTransfer): OfferResponseTransfer
    {
        $offerTransfer->requireQuote();

        $offerTransfer = $this->executeCreateOffer($offerTransfer);

        return (new OfferResponseTransfer())
            ->setIsSuccessful(true)
            ->setOffer($offerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferResponseTransfer
     */
    public function updateOffer(OfferTransfer $offerTransfer): OfferResponseTransfer
    {
        $offerTransfer->requireIdOffer();
        $offerTransfer = $this->offerEntityManager->updateOffer($offerTransfer);
        $offerResponseTransfer = new OfferResponseTransfer();
        $offerResponseTransfer->setIsSuccessful(true);

        $pluginOfferResponseTransfer = $this->offerPluginExecutor->updateOffer($offerTransfer);
        $offerResponseTransfer->fromArray($pluginOfferResponseTransfer->toArray(), true);

        return $offerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferResponseTransfer
     */
    public function placeOffer(OfferTransfer $offerTransfer): OfferResponseTransfer
    {
        $offerTransfer->requireQuote();
        $offerTransfer->requireCustomerReference();

        $offerTransfer = $this->executeCreateOffer($offerTransfer);

        return (new OfferResponseTransfer())
            ->setIsSuccessful(true)
            ->setOffer($offerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    protected function executeCreateOffer(OfferTransfer $offerTransfer)
    {
        $offerTransfer->setStatus($this->offerConfig->getInitialStatus());
        $offerTransfer = $this->offerEntityManager->createOffer($offerTransfer);
        $offerTransfer->getQuote()->setCheckoutConfirmed(true);

        return $offerTransfer;
    }
}
