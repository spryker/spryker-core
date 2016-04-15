<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @var array
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
