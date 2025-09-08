<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogExtension\Dependency\Plugin;

/**
 * @deprecated Use {@link \Spryker\Client\SearchExtension\Dependency\Plugin\SearchTypeIdentifierInterface} instead.
 */
interface SearchTypeIdentifierInterface
{
    /**
     * Specification:
     * - Returns search type name for {@link \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface} plugin.
     *
     * @api
     *
     * @return string
     */
    public function getSearchType(): string;
}
