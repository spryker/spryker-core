<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\RefundBusiness;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Refund\Business\Model\Refund;

/**
 * @method Factory|RefundBusiness getFactory()
 */
class RefundDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Refund
     */
    public function createRefundModel()
    {
        return $this->getFactory()->createModelRefund();
    }

}
