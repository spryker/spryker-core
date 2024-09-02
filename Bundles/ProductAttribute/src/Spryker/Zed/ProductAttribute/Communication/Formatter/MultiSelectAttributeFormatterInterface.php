<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Communication\Formatter;

interface MultiSelectAttributeFormatterInterface
{
    /**
     * @param array<mixed> $attributes
     * @param array<mixed> $formattedAttributes
     *
     * @return array<mixed>
     */
    public function format(array $attributes, array $formattedAttributes): array;
}
