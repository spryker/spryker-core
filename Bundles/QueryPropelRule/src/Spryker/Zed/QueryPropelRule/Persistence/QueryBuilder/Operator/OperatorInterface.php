<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator;

use Generated\Shared\Transfer\RuleQuerySetTransfer;

interface OperatorInterface
{

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getOperator();

    /**
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $rule
     *
     * @return mixed
     */
    public function getValue(RuleQuerySetTransfer $rule);

}
