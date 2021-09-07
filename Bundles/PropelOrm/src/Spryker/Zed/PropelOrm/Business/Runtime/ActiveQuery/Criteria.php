<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery;

use Propel\Runtime\ActiveQuery\Criteria as PropelCriteria;

class Criteria extends PropelCriteria
{
    /**
     * @var string
     */
    public const BETWEEN = 'BETWEEN';
}
