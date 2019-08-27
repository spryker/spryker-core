<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockStorage\Storage;

interface CmsBlockStorageInterface
{
    /**
     * @deprecated Use \Spryker\Client\CmsBlockStorage\Storage\CmsBlockStorageInterface::getBlocksByKeys() instead.
     *
     * @param string[] $blockNames
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function getBlocksByNames(array $blockNames, $localeName, $storeName): array;

    /**
     * @param string[] $blockKeys
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function getBlocksByKeys(array $blockKeys, string $localeName, string $storeName): array;

    /**
     * @param array $options
     *
     * @return array
     */
    public function getBlockKeysByOptions(array $options): array;
}
