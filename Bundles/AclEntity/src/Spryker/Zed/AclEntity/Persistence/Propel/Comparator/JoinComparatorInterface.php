<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Comparator;

use Propel\Runtime\ActiveQuery\Join;

interface JoinComparatorInterface
{
    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join1
     * @param \Propel\Runtime\ActiveQuery\Join $join2
     *
     * @return bool
     */
    public function areEqual(Join $join1, Join $join2): bool;
}
