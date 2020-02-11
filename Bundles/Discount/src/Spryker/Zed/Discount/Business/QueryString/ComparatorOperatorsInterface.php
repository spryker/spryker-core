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
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return bool
     */
    public function compare(ClauseTransfer $clauseTransfer, $withValue);

    /**
     * @param string[] $acceptedTypes
     *
     * @return string[]
     */
    public function getOperatorExpressionsByTypes(array $acceptedTypes);

    /**
     * @return string[]
     */
    public function getAvailableComparatorExpressions();

    /**
     * @return string[]
     */
    public function getCompoundComparatorExpressions();

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isExistingComparator(ClauseTransfer $clauseTransfer);

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return bool
     */
    public function isValidComparatorValue(ClauseTransfer $clauseTransfer);
}
