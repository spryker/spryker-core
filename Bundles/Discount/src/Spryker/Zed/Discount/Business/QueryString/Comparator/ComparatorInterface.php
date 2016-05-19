<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Comparator;

use Generated\Shared\Transfer\ClauseTransfer;

interface ComparatorInterface
{

    /**
     * @param ClauseTransfer $clauseTransfer
     * @param string $withValue
     *
     * @return bool
     */
    public function compare(ClauseTransfer $clauseTransfer, $withValue);

    /**
     * @param ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function accept(ClauseTransfer $clauseTransfer);

    /**
     * @return string
     */
    public function getExpression();

    /**
     * @return array|string[]
     */
    public function getAcceptedTypes();
}
