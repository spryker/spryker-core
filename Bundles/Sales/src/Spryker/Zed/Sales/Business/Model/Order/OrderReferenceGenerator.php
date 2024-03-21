<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToStoreInterface;
use Spryker\Zed\Sales\SalesConfig;

class OrderReferenceGenerator implements OrderReferenceGeneratorInterface
{
    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberInterface
     */
    protected $sequenceNumberFacade;

    /**
     * @var \Spryker\Zed\Sales\SalesConfig
     */
    protected SalesConfig $salesConfig;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToStoreInterface
     */
    protected SalesToStoreInterface $salesFacade;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberInterface $sequenceNumberFacade
     * @param \Spryker\Zed\Sales\SalesConfig $salesConfig
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToStoreInterface $salesFacade
     */
    public function __construct(
        SalesToSequenceNumberInterface $sequenceNumberFacade,
        SalesConfig $salesConfig,
        SalesToStoreInterface $salesFacade
    ) {
        $this->sequenceNumberFacade = $sequenceNumberFacade;
        $this->salesConfig = $salesConfig;
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function generateOrderReference(QuoteTransfer $quoteTransfer)
    {
        $storeName = $quoteTransfer->getStore() ? $quoteTransfer->getStore()->getName() : $this->salesFacade->getCurrentStore()->getName();
        $sequenceNumberSetting = $this->salesConfig->getOrderReferenceDefaults($storeName);

        return $this->sequenceNumberFacade->generate($sequenceNumberSetting);
    }
}
