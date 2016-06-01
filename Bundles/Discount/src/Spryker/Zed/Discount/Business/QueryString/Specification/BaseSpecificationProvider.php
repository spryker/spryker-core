<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification;

use Generated\Shared\Transfer\ClauseTransfer;

class BaseSpecificationProvider
{

    const ATTRIBUTE_FIELD_WILDCARD = '.*';

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return string
     */
    protected function getClauseFieldName(ClauseTransfer $clauseTransfer)
    {
        $clauseFieldName = $clauseTransfer->getField();
        if ($clauseTransfer->getAttribute()) {
            $clauseFieldName = $clauseTransfer->getField() . self::ATTRIBUTE_FIELD_WILDCARD;
        }
        return $clauseFieldName;
    }

}
