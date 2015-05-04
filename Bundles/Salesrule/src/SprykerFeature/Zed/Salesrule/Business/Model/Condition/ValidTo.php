<?php

namespace SprykerFeature\Zed\Salesrule\Business\Model\Condition;

use Generated\Shared\Transfer\SalesOrderTransfer;
use SprykerFeature\Zed\Salesrule\Business\Model\Logger;

final class ValidTo extends AbstractCondition
{
    const CONFIG_KEY_DATE = 'date';

    /**
     * @var string
     */
    public static $conditionName = 'Valid from date';

    /**
     * @var string
     */
    public static $conditionFacadeGetter = 'ConditionValidTo';

    /**
     * @var array
     */
    protected $allowedConfigKeys = array(
        self::CONFIG_KEY_DATE
    );

    /**
     * @param Order $order
     * @return bool|mixed
     */
    public function match(Order $order)
    {
        assert(null !== $this->configuration);

        $configuration = $this->configuration;
        $now = time();
        if (strtotime($configuration[self::CONFIG_KEY_DATE]) >= $now) {
            Logger::getInstance()->log(
                static::$conditionName . ': ' . strtotime($configuration[self::CONFIG_KEY_DATE]) . ' >= ' . $now
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return \Zend_Form
     */
    public function getForm()
    {
    }
}
