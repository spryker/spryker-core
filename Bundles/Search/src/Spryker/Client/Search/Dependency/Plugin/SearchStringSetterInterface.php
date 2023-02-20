<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Dependency\Plugin;

use Spryker\Client\SearchExtension\Dependency\Plugin\SearchStringSetterInterface as ExtensionSearchStringSetterInterface;

/**
 * @deprecated Use {@link \Spryker\Client\SearchExtension\Dependency\Plugin\SearchStringSetterInterface} instead.
 */
class_alias(
    ExtensionSearchStringSetterInterface::class,
    'Spryker\Client\Search\Dependency\Plugin\SearchStringSetterInterface',
);

// This is done to support Composer's --classmap-authoritative option.
// phpcs:ignore
if (false) {
    interface SearchStringSetterInterface
    {
        /**
         * Specification:
         * - Sets a string for search.
         *
         * @api
         *
         * @param string $searchString
         *
         * @return void
         */
        public function setSearchString($searchString);
    }
}
