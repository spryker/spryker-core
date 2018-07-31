<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Suggest;

interface ProductSuggesterInterface
{
    /**
     * @param string $suggestion
     * @param null|int $limit
     *
     * @return string[]
     */
    public function suggestProductAbstract(string $suggestion, ?int $limit = null): array;

    /**
     * @param string $suggestion
     * @param null|int $limit
     *
     * @return string[]
     */
    public function suggestProductConcrete(string $suggestion, ?int $limit = null): array;
}
