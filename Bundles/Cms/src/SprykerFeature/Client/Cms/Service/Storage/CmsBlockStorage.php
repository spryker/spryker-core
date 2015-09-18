<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Client\Cms\Service\Storage;

use Generated\Shared\Transfer\CmsBlockTransfer;
use SprykerFeature\Client\Storage\Service\StorageClientInterface;

class CmsBlockStorage implements CmsBlockStorageInterface
{

    /**
     * @var StorageClientInterface
     */
    private $storage;

    /**
     * @var KeyBuilderInterface
     */
    private $keyBuilder;

    /**
     * @param StorageClientInterface $storage
     * @param KeyBuilderInterface $keyBuilder
     */
    public function __construct($storage, $keyBuilder)
    {
        $this->storage = $storage;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return array
     */
    public function getBlockByName(CmsBlockTransfer $cmsBlockTransfer)
    {
        $blockName = $cmsBlockTransfer->getName().'-'.$cmsBlockTransfer->getType().'-'.$cmsBlockTransfer->getValue();

        $key = $this->keyBuilder->generateKey($blockName, $cmsBlockTransfer->getLocale()->getLocaleName());
        $block = $this->storage->get($key);

        return $block;
    }
}
