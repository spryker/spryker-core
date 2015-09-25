<?php

namespace SprykerFeature\Zed\Discount\Business\Model;

interface CartRuleInterface
{
    /**
     * @param int $idDiscount
     *
     * @return array
     */
    public function getCurrentCartRulesDetailsByIdDiscount($idDiscount);

}
