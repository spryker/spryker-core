<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Communication\Plugin\Oms\Condition;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\OmsFacade;
use Spryker\Zed\Oms\Communication\OmsCommunicationFactory;

/**
 * @method OmsFacade getFacade()
 * @method OmsCommunicationFactory getFactory()
 */
abstract class AbstractCondition extends AbstractPlugin implements ConditionInterface
{
}
