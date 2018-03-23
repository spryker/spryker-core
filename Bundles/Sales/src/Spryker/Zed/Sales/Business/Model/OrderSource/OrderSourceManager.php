<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\OrderSource;

use Generated\Shared\Transfer\OrderSourceTransfer;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class OrderSourceManager implements OrderSourceManagerInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     */
    public function __construct(
        SalesQueryContainerInterface $queryContainer
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
        $orderSource = $this->queryContainer->queryOrderSourceById($idOrderSource)->findOne();
        $orderSourceTransfer = new OrderSourceTransfer();
        $orderSourceTransfer->setIdOrderSource($orderSource->getIdOrderSource());
        $orderSourceTransfer->setOrderSourceName($orderSource->getOrderSourceName());

        return $orderSourceTransfer;
    }
}
