<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Discount\Business\QueryString;

use Generated\Shared\Transfer\ClauseTransfer;

interface ComparatorOperatorsInterface
{

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param mixed $withValue
     *
     * @return bool
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     */
    public function compare(ClauseTransfer $clauseTransfer, $withValue);

    /**
     * @param array|string[] $acceptedTypes
     *
     * @return array|string[]
     */
    public function getOperatorExpressionsByTypes(array $acceptedTypes);

    /**
     * @return array|string[]
     */
    public function getAvailableComparatorExpressions();

}
