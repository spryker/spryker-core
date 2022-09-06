<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesPayment\Communication\Mapper\EventPaymentMapper;
use Spryker\Zed\SalesPayment\Communication\Mapper\EventPaymentMapperInterface;

/**
 * @method \Spryker\Zed\SalesPayment\Persistence\PaymentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\SalesPayment\SalesPaymentConfig getConfig()
 * @method \Spryker\Zed\SalesPayment\Business\SalesPaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesPayment\Persistence\SalesPaymentRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesPayment\Persistence\SalesPaymentEntityManagerInterface getEntityManager()
 */
class SalesPaymentCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SalesPayment\Communication\Mapper\EventPaymentMapperInterface
     */
    public function createEventPaymentMapper(): EventPaymentMapperInterface
    {
        return new EventPaymentMapper();
    }
}
