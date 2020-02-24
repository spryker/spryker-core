<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesReturn\Business\Expander\RemunerationTotalExpander;
use Spryker\Zed\SalesReturn\Business\Expander\RemunerationTotalExpanderInterface;
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
     * @return \Spryker\Zed\SalesReturn\Business\Expander\RemunerationTotalExpanderInterface
     */
    public function createRemunerationTotalExpander(): RemunerationTotalExpanderInterface
    {
        return new RemunerationTotalExpander();
    }
}
