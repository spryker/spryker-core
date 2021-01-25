<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\QueryCreator;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

interface RuleQueryCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function createQuery(ProductRelationTransfer $productRelationTransfer): ModelCriteria;
}
