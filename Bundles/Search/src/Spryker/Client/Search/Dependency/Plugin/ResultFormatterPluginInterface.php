<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Dependency\Plugin;

use Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface as ExtensionResultFormatterPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface} instead.
 */
class_alias(
    ExtensionResultFormatterPluginInterface::class,
    'Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface'
);

// This is done to support Composer's --classmap-authoritative option.
// phpcs:ignore
if (false) {
    interface ResultFormatterPluginInterface
    {
        /**
         * Specification:
         * - TODO: Add method specification.
         *
         * @api
         *
         * @return string
         */
        public function getName();

        /**
         * Specification:
         * - TODO: Add method specification.
         *
         * @api
         *
         * @param mixed $searchResult
         * @param array $requestParameters
         *
         * @return mixed
         */
        public function formatResult($searchResult, array $requestParameters = []);
    }
}
