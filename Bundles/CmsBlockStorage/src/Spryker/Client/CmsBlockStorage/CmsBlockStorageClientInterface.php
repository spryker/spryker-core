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
     * - Finds blocks by provided array of names with a single multi request to a storage
     *
     * @api
     *
     * @deprecated Use findBlocksByKeys instead.
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
     * - Finds blocks by required options.
     * - Returns only block names which fit to all provided options.
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
     * - Prepares a valid block key by provided name.
     *
     * @api
     *
     * @deprecated Will be removed in the next major release.
     *
     * @param string $name
     *
     * @return string
     */
    public function generateBlockNameKey($name);

    /**
     * Specification:
     * - Finds blocks by provided array of keys with a single multi request to a storage.
     *
     * @api
     *
     * @param string[] $blockKeys
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function findBlocksByKeys(array $blockKeys, string $localeName, string $storeName): array;

    /**
     * Specification:
     * - Finds mapping data by provided block bane with a single request to a storage.
     *
     * @api
     *
     * @param string $blockName
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function findMappingDataByBlockName(string $blockName, string $localeName, string $storeName): array;

    /**
     * Specification:
     * - Finds blocks by required options.
     * - Returns only block keys which fit to all provided options.
     *
     * @api
     *
     * @param array $options
     *
     * @return array
     */
    public function findBlockKeysByOptions(array $options): array;
}
