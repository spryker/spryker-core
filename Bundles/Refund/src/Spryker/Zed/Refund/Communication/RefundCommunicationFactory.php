<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Refund\Communication\Table\RefundTable;
use Spryker\Zed\Refund\RefundDependencyProvider;

/**
 * @method \Spryker\Zed\Refund\RefundConfig getConfig()
 * @method \Spryker\Zed\Refund\Persistence\RefundQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Refund\Business\RefundFacadeInterface getFacade()
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
            $this->getMoneyFacade()
        );

        return $refundTable;
    }

    /**
     * @return \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    protected function getDateFormatter()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::SERVICE_DATE_TIME);
    }

    /**
     * @return \Spryker\Zed\Refund\Dependency\Facade\RefundToMoneyInterface
     */
    protected function getMoneyFacade()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::FACADE_MONEY);
    }
}
