<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockStorage;

interface CmsBlockStorageClientInterface
{
    /**
     * Specification:
     * - Find blocks by provided array of names with a single multi request to a storage
     *
     * @api
     *
     * @param string[] $blockNames
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function findBlocksByNames($blockNames, $localeName, $storeName);

    /**
     * Specification:
     * - Find blocs by required options
     * - Return only block names which fit to all provided options
     *
     * @api
     *
     * @param array $options
     * @param string $localName
     *
     * @return array
     */
    public function findBlockNamesByOptions(array $options, $localName);

    /**
     * Specification:
     * - Prepare a valid block key by provided name
     *
     * @api
     *
     * @param string $name
     *
     * @return string
     */
    public function generateBlockNameKey($name);
}
