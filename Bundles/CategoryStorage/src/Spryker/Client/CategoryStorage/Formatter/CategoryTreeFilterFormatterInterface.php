<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Formatter;

use ArrayObject;
use Elastica\ResultSet;

interface CategoryTreeFilterFormatterInterface
{
    /**
     * @param \Elastica\ResultSet $searchResult
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryNodeSearchResultTransfer[]
     */
    public function formatResultSetToCategoryTreeFilter(ResultSet $searchResult): ArrayObject;
}
