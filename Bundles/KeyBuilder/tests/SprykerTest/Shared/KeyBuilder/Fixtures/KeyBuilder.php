<?php
// phpcs:ignoreFile

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\KeyBuilder\Fixtures;

use Spryker\Shared\KeyBuilder\KeyBuilderTrait;

if (version_compare(phpversion(), '8.0.0', '>=')) {
    class KeyBuilder
    {
        use KeyBuilderTrait;

        /**
         * @return string
         */
        public function getBundleName(): string
        {
            return 'key-builder';
        }

        /**
         * @param string $data
         *
         * @return string
         */
        protected function buildKey($data)
        {
            return 'identifier.' . $data;
        }
    }
} else {
    class KeyBuilder
    {
        use KeyBuilderTrait;

        /**
         * @return string
         */
        public function getBundleName(): string
        {
            return 'key-builder';
        }

        /**
         * @param string $data
         *
         * @return string
         */
        protected function buildKey(string $data): string
        {
            return 'identifier.' . $data;
        }
    }
}
