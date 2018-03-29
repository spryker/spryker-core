<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model;

use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Spryker\Zed\Offer\Dependency\Facade\OfferToSalesFacadeInterface;
use Spryker\Zed\Offer\OfferConfig;
use Spryker\Zed\Offer\Persistence\OfferRepositoryInterface;

class OfferReader implements OfferReaderInterface
{

    /**
     * @var OfferRepositoryInterface
     */
    protected $offerRepository;

    /**
     * @var OfferPluginExecutorInterface
     */
    protected $offerPluginExecutor;

    /**
     * @param OfferRepositoryInterface $offerRepository
     * @param OfferPluginExecutorInterface $offerPluginExecutor
     */
    public function __construct(
        OfferRepositoryInterface $offerRepository,
        OfferPluginExecutorInterface $offerPluginExecutor
    ) {
        $this->offerRepository = $offerRepository;
        $this->offerPluginExecutor = $offerPluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOfferList(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
//        $orderListTransfer->setType(
//            $this->offerConfig->getOrderTypeOffer()
//        );
//
//        return $this->salesFacade
//            ->getCustomerOrders($orderListTransfer, $orderListTransfer->getIdCustomer());
    }

    /**
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferTransfer
     */
    public function getOfferById(OfferTransfer $offerTransfer): OfferTransfer
    {
        $offerTransfer = $this->offerRepository->getOfferById($offerTransfer->getIdOffer());
        $offerTransfer = $this->offerPluginExecutor->hydrateOffer($offerTransfer);

        return $offerTransfer;
    }

}
