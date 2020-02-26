<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesReturn\Business\Expander\OrderRemunerationTotalExpander;
use Spryker\Zed\SalesReturn\Business\Expander\OrderRemunerationTotalExpanderInterface;
use Spryker\Zed\SalesReturn\Business\Reader\ReturnReasonReader;
use Spryker\Zed\SalesReturn\Business\Reader\ReturnReasonReaderInterface;
use Spryker\Zed\SalesReturn\Business\Setter\ItemRemunerationAmountSetter;
use Spryker\Zed\SalesReturn\Business\Setter\ItemRemunerationAmountSetterInterface;

/**
 * @method \Spryker\Zed\SalesReturn\Persistence\SalesReturnEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesReturn\SalesReturnConfig getConfig()
 */
class SalesReturnBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesReturn\Business\Setter\ItemRemunerationAmountSetterInterface
     */
    public function createItemRemunerationAmountSetter(): ItemRemunerationAmountSetterInterface
    {
        return new ItemRemunerationAmountSetter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Business\Reader\ReturnReasonReaderInterface
     */
    public function createReturnReasonReader(): ReturnReasonReaderInterface
    {
        return new ReturnReasonReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Business\Expander\OrderRemunerationTotalExpanderInterface
     */
    public function createOrderRemunerationTotalExpander(): OrderRemunerationTotalExpanderInterface
    {
        return new OrderRemunerationTotalExpander();
    }
}
