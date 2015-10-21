<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\DecisionRule;

use SprykerFeature\Zed\Discount\Communication\Plugin\AbstractDiscountPlugin;

/**
 * Class AbstractDecisionRule
 */
abstract class AbstractDecisionRule extends AbstractDiscountPlugin
{

    const KEY_ENTITY = 'entity';
    const KEY_DATA = 'data';

    /**
     * @var
     */
    protected $context = [];

    /**
     * @param array $context
     */
    public function setContext(array $context)
    {
        $this->context = $context;
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

}
