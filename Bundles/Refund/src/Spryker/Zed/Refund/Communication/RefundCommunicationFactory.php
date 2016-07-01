<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Communication;

use Spryker\Shared\Library\Context;
use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Shared\Library\DateFormatter;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Refund\Communication\Table\RefundTable;

/**
 * @method \Spryker\Zed\Refund\RefundConfig getConfig()
 * @method \Spryker\Zed\Refund\Persistence\RefundQueryContainer getQueryContainer()
 */
class RefundCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Refund\Communication\Table\RefundTable
     */
    public function createRefundTable()
    {
        $refundTable = new RefundTable(
            $this->getQueryContainer(),
            $this->getDateFormatter(),
            $this->getCurrencyManager()
        );

        return $refundTable;
    }

    /**
     * @return \Spryker\Shared\Library\DateFormatterInterface
     */
    protected function getDateFormatter()
    {
        return new DateFormatter(Context::getInstance(Context::CONTEXT_ZED));
    }

    /**
     * @return \Spryker\Shared\Library\Currency\CurrencyManager
     */
    protected function getCurrencyManager()
    {
        return CurrencyManager::getInstance();
    }

}
