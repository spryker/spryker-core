<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CmsBlockStorage\CmsBlockStorageFactory getFactory()
 */
class CmsBlockStorageClient extends AbstractClient implements CmsBlockStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use \Spryker\Client\CmsBlockStorage\CmsBlockStorageClient::findBlocksByKeys() instead.
     *
     * @param string[] $blockNames
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function findBlocksByNames($blockNames, $localeName, $storeName)
    {
        return $this->getFactory()
            ->createCmsBlockStorage()
            ->getBlocksByNames($blockNames, $localeName, $storeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $blockKeys
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function findBlocksByKeys(array $blockKeys, string $localeName, string $storeName): array
    {
        return $this->getFactory()
            ->createCmsBlockStorage()
            ->getBlocksByKeys($blockKeys, $localeName, $storeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $options
     *
     * @return array
     */
    public function findBlockKeysByOptions(array $options): array
    {
        return $this->getFactory()
            ->createCmsBlockStorage()
            ->getBlockKeysByOptions($options);
    }
}
