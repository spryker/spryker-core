<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\DecisionRule;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * Class AbstractDecisionRule
 */
abstract class AbstractDecisionRule extends AbstractPlugin
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
