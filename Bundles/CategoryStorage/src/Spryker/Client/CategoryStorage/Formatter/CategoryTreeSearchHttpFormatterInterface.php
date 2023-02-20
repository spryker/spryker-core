<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Formatter;

use ArrayObject;
use Generated\Shared\Transfer\SearchHttpResponseTransfer;

interface CategoryTreeSearchHttpFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SearchHttpResponseTransfer $searchResult
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer>
     */
    public function format(SearchHttpResponseTransfer $searchResult): ArrayObject;
}
