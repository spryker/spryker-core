<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlock\Storage;

interface CmsBlockStorageInterface
{
    /**
     * @param string[] $blockNames
     * @param string $localeName
     *
     * @return array
     */
    public function getBlocksByNames(array $blockNames, $localeName);

    /**
     * @param array $options
     * @param string $localName
     *
     * @return array
     */
    public function getBlockNamesByOptions(array $options, $localName);

    /**
     * @param string $name
     * @param string $localeName
     *
     * @return string
     */
    public function generateBlockNameKey($name, $localeName);
}
