<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Business;

use Bundles\Refund\src\SprykerFeature\Zed\Refund\Business\Model\RefundComment;
use Generated\Zed\Ide\FactoryAutoCompletion\RefundBusiness;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;

/**
 * @method Factory|RefundBusiness getFactory()
 */
class RefundDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return RefundComment
     */
    public function createRefundCommentModel()
    {
        return $this->getFactory()->createModelRefundComment();
    }

}
