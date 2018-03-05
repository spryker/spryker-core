<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\UrlStorage\Plugin;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\UrlStorageResourceMapTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\UrlStorage\Dependency\Plugin\UrlStorageResourceMapperPluginInterface;
use Spryker\Shared\UrlStorage\UrlStorageConstants;

/**
 * @method \Spryker\Client\UrlStorage\UrlStorageFactory getFactory()
 */
class UrlStorageRedirectMapperPlugin extends AbstractPlugin implements UrlStorageResourceMapperPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\UrlStorageResourceMapTransfer
     */
    public function map(UrlStorageTransfer $urlStorageTransfer, array $options = [])
    {
        $urlStorageResourceMapTransfer = new UrlStorageResourceMapTransfer();
        $idRedirectUrl = $urlStorageTransfer->getFkResourceRedirect();
        if ($idRedirectUrl) {
            $resourceKey = $this->generateKey($idRedirectUrl);
            $urlStorageResourceMapTransfer->setResourceKey($resourceKey);
            $urlStorageResourceMapTransfer->setType(UrlStorageConstants::REDIRECT_RESOURCE_NAME);
        }

        return $urlStorageResourceMapTransfer;
    }

    /**
     * @param int $idRedirectUrl
     *
     * @return mixed
     */
    protected function generateKey($idRedirectUrl)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference($idRedirectUrl);

        return $this->getFactory()
            ->getSynchronizationService()
            ->getStorageKeyBuilder(UrlStorageConstants::REDIRECT_RESOURCE_NAME)->generateKey($synchronizationDataTransfer);
    }
}
