<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Dependency\Plugin;

use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface as ExtensionQueryInterface;

/**
 * @deprecated Use {@link \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface} instead.
 */
class_alias(
    ExtensionQueryInterface::class,
    'Spryker\Client\Search\Dependency\Plugin\QueryInterface'
);

// This is done to support Composer's --classmap-authoritative option.
// phpcs:ignore
if (false) {
    interface QueryInterface
    {
        /**
         * @api
         *
         * @return mixed A query object.
         */
        public function getSearchQuery();
    }
}
