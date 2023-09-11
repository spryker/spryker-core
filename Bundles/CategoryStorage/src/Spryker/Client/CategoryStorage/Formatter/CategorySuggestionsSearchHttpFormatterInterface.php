<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Formatter;

use Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer;

interface CategorySuggestionsSearchHttpFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $suggestionsSearchHttpResponseTransfer
     *
     * @return array<int, mixed>
     */
    public function format(SuggestionsSearchHttpResponseTransfer $suggestionsSearchHttpResponseTransfer): array;
}
