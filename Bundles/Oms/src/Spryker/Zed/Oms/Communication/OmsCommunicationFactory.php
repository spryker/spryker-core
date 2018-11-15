<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Oms\Communication\Table\TransitionLogTable;

/**
 * @method \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Oms\OmsConfig getConfig()
 * @method \Spryker\Zed\Oms\Business\OmsFacadeInterface getFacade()
 */
class OmsCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Oms\Communication\Table\TransitionLogTable
     */
    public function createTransitionLogTable()
    {
        $queryContainer = $this->getQueryContainer();

        return new TransitionLogTable($queryContainer);
    }
}
