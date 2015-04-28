<?php

namespace SprykerFeature\Zed\Salesrule\Business;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class SalesruleConfig extends AbstractBundleConfig
{

    /**
     * @static
     * @return array
     */
    public function getAvailableConditions()
    {
        return [
            $this->factory->createModelConditionDateBetween(),
            $this->factory->createModelConditionVoucherCodeInPool(),
            $this->factory->createModelConditionMinimumOrderSubtotal(),
        ];
    }

    /**
     * @param $action
     * @return string
     */
    public function getTypeByAction($action)
    {
        if ($action === 'ActionPercentShipping') {
            return 'shipping';
        } else {
            return 'standard';
        }
    }

    /**
     * @return array
     */
    public function getAvailableActions()
    {
        return array(
            'ActionFixed' => 'Fixed',
            'ActionPercent' => 'Percent',
        );
    }

    /**
     * @static
     * @return string
     */
    public static function getAllowedCodeAlphabet()
    {
        return 'ABCDEFGHJKLMNPQRSTUVWXYZ123456789';
    }
}
