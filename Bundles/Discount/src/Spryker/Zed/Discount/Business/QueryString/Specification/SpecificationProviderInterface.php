<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification;

use Generated\Shared\Transfer\ClauseTransfer;

interface SpecificationProviderInterface
{

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return mixed
     */
    public function getSpecificationContext(ClauseTransfer $clauseTransfer);

    /**
     * @param mixed $leftNode
     * @param mixed $rightNode
     *
     * @return mixed
     */
    public function createAnd($leftNode, $rightNode);

    /**
     * @param mixed $leftNode
     * @param mixed $rightNode
     *
     * @return mixed
     */
    public function createOr($leftNode, $rightNode);

}
