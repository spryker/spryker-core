<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\DataProvider;

use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\Discount\Communication\Form\CartRuleForm;

class CartRuleFormDataProvider
{

    /**
     * @var \Spryker\Zed\Discount\Business\DiscountFacade
     */
    protected $discountFacade;

    /**
     * @param \Spryker\Zed\Discount\Business\DiscountFacade $discountFacade
     */
    public function __construct(DiscountFacade $discountFacade)
    {
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param int|null $idDiscount
     *
     * @return array
     */
    public function getData($idDiscount = null)
    {
        if ($idDiscount > 0) {
            $cartRuleDefaultData = $this->discountFacade->getCurrentCartRulesDetailsByIdDiscount($idDiscount);

            return $cartRuleDefaultData;
        }

        return [
            CartRuleForm::VALID_FROM => new \DateTime('now'),
            CartRuleForm::VALID_TO => new \DateTime('now'),
            CartRuleForm::FIELD_DECISION_RULES => [
                'rule_1' => [
                    'value' => '',
                    'rules' => '',
                ],
            ],
            CartRuleForm::FIELD_COLLECTOR_PLUGINS => [
                'plugin_1' => [
                    'collector_plugin' => '',
                    'value' => '',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

}
