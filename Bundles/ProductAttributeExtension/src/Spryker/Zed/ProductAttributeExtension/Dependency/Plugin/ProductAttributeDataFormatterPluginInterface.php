<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeExtension\Dependency\Plugin;

/**
 * Provides custom formatting capabilities for attributes data.
 */
interface ProductAttributeDataFormatterPluginInterface
{
    /**
     * Specification:
     * - Formats product attribute data.
     *
     * @api
     *
     * @param array<mixed> $attributes
     * @param array<mixed> $formattedAttributes
     *
     * @return array<mixed>
     */
    public function format(array $attributes, array $formattedAttributes): array;
}
