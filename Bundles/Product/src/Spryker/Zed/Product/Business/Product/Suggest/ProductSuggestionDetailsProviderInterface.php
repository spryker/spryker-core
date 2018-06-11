<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Suggest;

use Generated\Shared\Transfer\ProductSuggestionDetailsTransfer;

interface ProductSuggestionDetailsProviderInterface
{
    /**
     * @param string $suggestion
     *
     * @return \Generated\Shared\Transfer\ProductSuggestionDetailsTransfer
     */
    public function getSuggestionDetails(string $suggestion): ProductSuggestionDetailsTransfer;
}
