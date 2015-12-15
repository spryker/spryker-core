<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Communication\Plugin\DecisionRule;

use Spryker\Zed\Discount\Communication\Plugin\AbstractDiscountPlugin;

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
     *
     * @return void
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
