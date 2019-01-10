<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Sales\Shipment;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\Kernel\AbstractService;
use Spryker\Service\Sales\Model\SplitDeliveryEnabledCheckerInterface;

/**
 * @method \Spryker\Service\Sales\SalesServiceFactory getFactory()
 */
class SalesService extends AbstractService implements SalesServiceInterface
{
    /**
     * @var \Spryker\Service\Sales\Model\SplitDeliveryEnabledCheckerInterface
     */
    protected $splitDeliveryEnabledChecker;

    /**
     * @return \Spryker\Service\Sales\Model\SplitDeliveryEnabledCheckerInterface
     */
    protected function getSplitDeliveryEnabledChecker(): SplitDeliveryEnabledCheckerInterface
    {
        if ($this->splitDeliveryEnabledChecker === null) {
            $this->splitDeliveryEnabledChecker = $this->getFactory()->createSplitDeliveryEnabledChecker();
        }

        return $this->splitDeliveryEnabledChecker;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkSplitDeliveryEnabledByQuote(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getSplitDeliveryEnabledChecker()->checkByQuote($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function checkSplitDeliveryEnabledByOrder(OrderTransfer $orderTransfer): bool
    {
        return $this->getSplitDeliveryEnabledChecker()->checkByOrder($orderTransfer);
    }
}
