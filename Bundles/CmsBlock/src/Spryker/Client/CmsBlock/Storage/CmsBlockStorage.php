<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlock\Storage;

use Generated\Shared\Transfer\CmsBlockTransfer;

class CmsBlockStorage implements CmsBlockStorageInterface
{

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    private $storage;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface KeyBuilderInterface
     */
    private $keyBuilder;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storage
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface KeyBuilderInterface $keyBuilder
     */
    public function __construct($storage, $keyBuilder)
    {
        $this->storage = $storage;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param string $localeName
     *
     * @return array
     */
    public function getBlockByName(CmsBlockTransfer $cmsBlockTransfer, $localeName)
    {
        $key = $this->keyBuilder->generateKey($cmsBlockTransfer->getName(), $localeName);
        $block = $this->storage->get($key);

        return $block;
    }

}
