<?php
  /**
  * (c) Spryker Systems GmbH copyright protected
  */

namespace SprykerFeature\Zed\PayoneOmsConnector\Communication\Plugin\Condition;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin as BaseAbstractPlugin;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use SprykerFeature\Zed\Payone\Business\PayoneDependencyContainer;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 */
abstract class AbstractPlugin extends BaseAbstractPlugin implements ConditionInterface
{

    /**
     * @var array
     */
    protected static $resultCache = [];
} 
