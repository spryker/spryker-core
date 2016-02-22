<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cms\Storage;

use Generated\Shared\Transfer\CmsBlockTransfer;

class CmsBlockStorage implements CmsBlockStorageInterface
{

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    private $storage;

    /**
     * @var \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface KeyBuilderInterface
     */
    private $keyBuilder;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storage
     * @param \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface KeyBuilderInterface $keyBuilder
     */
    public function __construct($storage, $keyBuilder)
    {
        $this->storage = $storage;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return array
     */
    public function getBlockByName(CmsBlockTransfer $cmsBlockTransfer)
    {
        $blockName = $cmsBlockTransfer->getName() . '-' . $cmsBlockTransfer->getType() . '-' . $cmsBlockTransfer->getValue();

        $key = $this->keyBuilder->generateKey($blockName, $cmsBlockTransfer->getLocale()->getLocaleName());
        $block = $this->storage->get($key);

        return $block;
    }

}
