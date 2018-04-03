<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntry\Business\Model\OrderSource;

use Generated\Shared\Transfer\OrderSourceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Spryker\Zed\ManualOrderEntry\Persistence\ManualOrderEntryQueryContainerInterface;

class OrderSourceManager implements OrderSourceManagerInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntry\Persistence\ManualOrderEntryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\ManualOrderEntry\Persistence\ManualOrderEntryQueryContainerInterface $queryContainer
     */
    public function __construct(
        ManualOrderEntryQueryContainerInterface $queryContainer
    ) {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idOrderSource
     *
     * @return \Generated\Shared\Transfer\OrderSourceTransfer
     */
    public function findOrderSourceByIdOrderSource($idOrderSource)
    {
        $orderSource = $this->queryContainer
            ->queryOrderSourceById($idOrderSource)
            ->findOne();
        $orderSourceTransfer = new OrderSourceTransfer();
        $orderSourceTransfer->setIdOrderSource($orderSource->getIdOrderSource());
        $orderSourceTransfer->setName($orderSource->getName());

        return $orderSourceTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderSourceTransfer[]
     */
    public function findAllOrderSources()
    {
        $orderSources = $this->queryContainer
            ->queryOrderSource()
            ->find();

        $orderSourceTransfers = [];

        /** @var \Orm\Zed\ManualOrderEntry\Persistence\SpyOrderSource $orderSource */
        foreach ($orderSources as $orderSource) {
            $orderSourceTransfer = new OrderSourceTransfer();
            $orderSourceTransfer->fromArray($orderSource->toArray(), true);

            $orderSourceTransfers[] = $orderSourceTransfer;
        }

        return $orderSourceTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $spySalesOrderEntityTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function hydrateOrderSource(SpySalesOrderEntityTransfer $spySalesOrderEntityTransfer, QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getOrderSource()) {
            $spySalesOrderEntityTransfer->setFkOrderSource($quoteTransfer->getOrderSource()->getIdOrderSource() ?? null);
        }

        return $spySalesOrderEntityTransfer;
    }
}
