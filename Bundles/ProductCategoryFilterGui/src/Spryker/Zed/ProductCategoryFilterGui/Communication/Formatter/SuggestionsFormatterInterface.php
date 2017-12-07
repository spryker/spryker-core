<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Formatter;

interface SuggestionsFormatterInterface
{
    /**
     * @param string[] $suggestions
     * @param int $idCategory
     *
     * @return string[]
     */
    public function formatCategorySuggestions($suggestions, $idCategory);
}
