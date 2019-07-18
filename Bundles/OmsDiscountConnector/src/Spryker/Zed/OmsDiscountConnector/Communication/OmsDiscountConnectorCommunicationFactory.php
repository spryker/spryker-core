<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsDiscountConnector\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\OmsDiscountConnector\OmsDiscountConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\OmsDiscountConnector\OmsDiscountConnectorConfig getConfig()
 * @method \Spryker\Zed\OmsDiscountConnector\Business\OmsDiscountConnectorFacadeInterface getFacade()
 */
class OmsDiscountConnectorCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\OmsDiscountConnector\Dependency\Facade\OmsDiscountConnectorToDiscountInterface
     */
    public function getDiscountFacade()
    {
        return $this->getProvidedDependency(OmsDiscountConnectorDependencyProvider::FACADE_DISCOUNT);
    }
}
