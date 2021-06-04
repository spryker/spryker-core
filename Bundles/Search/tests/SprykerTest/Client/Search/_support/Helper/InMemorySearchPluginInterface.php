<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Helper;

use Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface;

interface InMemorySearchPluginInterface extends SearchAdapterPluginInterface
{
    /**
     * @param string $source
     *
     * @return array
     */
    public function getAllKeys(string $source): array;

    /**
     * @return void
     */
    public function deleteAll(): void;
}
