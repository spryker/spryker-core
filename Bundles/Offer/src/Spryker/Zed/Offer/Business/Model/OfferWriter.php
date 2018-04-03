<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model;

use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Orm\Zed\Offer\Persistence\SpyOffer;
use Orm\Zed\Offer\Persistence\SpyOfferQuery;
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
     * @var OfferPluginExecutorInterface
     */
    protected $offerPluginExecutor;

    /**
     * @param \Spryker\Zed\Offer\Persistence\OfferEntityManagerInterface $offerEntityManager
     * @param \Spryker\Zed\Offer\Persistence\OfferRepositoryInterface $offerRepository
     * @param \Spryker\Zed\Offer\OfferConfig $offerConfig
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
        $offerTransfer->getQuote()->requireCustomer();

        //TODO: drop Customer object here and fill it from session on order creation

        $offerTransfer->setStatus($this->offerConfig->getStatusInProgress());
        $offerTransfer->setCustomerReference($offerTransfer->getQuote()->getCustomer()->getCustomerReference());

        $offerTransfer = $this->offerEntityManager->createOffer($offerTransfer);

        $offerTransfer->getQuote()->setCheckoutConfirmed(true);

        return (new OfferResponseTransfer())
            ->setIsSuccessful(true)
            ->setOffer($offerTransfer);
    }

    /**
     * @throws \Exception
     *
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferResponseTransfer
     */
    public function updateOffer(OfferTransfer $offerTransfer): OfferResponseTransfer
    {
        $offerTransfer->requireIdOffer();

        /** @var SpyOffer $offerEntity */
        $offerEntity = SpyOfferQuery::create()
            ->filterByIdOffer($offerTransfer->getIdOffer())
            ->findOne();

        if (!$offerEntity) {
            throw new \Exception();
        }

        $offerEntity->fromArray($offerTransfer->toArray());

        //todo: move to a mapper
        $fieldsToPersist = [
            'store',
            'items',
            'totals',
            'expenses',
            'price_mode',
            'currency',
            'billing_address',
            'shipping_address',
            'billing_same_as_shipping',
            'voucher_discounts',
            'cart_rule_discounts',
            'gift_cards',
            'payments',
            'shipment',
            'bundle_items',
        ];

        $quoteTransfer = $offerTransfer->getQuote();
        $quoteArray = array_intersect_key(
            $quoteTransfer->toArray(),
            array_flip($fieldsToPersist)
        );

        $offerEntity->setQuoteData(json_encode($quoteArray));

        $offerEntity->save();

        $offerResponseTransfer = $this->offerPluginExecutor->updateOffer($offerTransfer);

        return $offerResponseTransfer;
    }
}
