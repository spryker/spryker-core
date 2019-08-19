<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockStorage\Storage;

interface CmsBlockStorageInterface
{
    /**
     * @param string[] $blockNames
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function getBlocksByNames(array $blockNames, $localeName, $storeName);

    /**
     * @param array $options
     * @param string $localName
     *
     * @return array
     */
    public function getBlockNamesByOptions(array $options, $localName);

    /**
     * @param string $name
     *
     * @return string
     */
    public function generateBlockNameKey($name);

    /**
     * @param string[] $blockKeys
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function getBlocksByKeys(array $blockKeys, string $localeName, string $storeName): array;

    /**
     * @param string $blockName
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function getMappingDataByBlockName(string $blockName, string $localeName, string $storeName): array;

    /**
     * @param array $options
     * @param string $localeName
     *
     * @return array
     */
    public function getBlockKeysByOptions(array $options, string $localeName): array;
}
