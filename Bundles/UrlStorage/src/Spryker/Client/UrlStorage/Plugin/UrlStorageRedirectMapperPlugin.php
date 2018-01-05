<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\UrlStorage\Plugin;

use Generated\Shared\Transfer\SpyUrlEntityTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\UrlStorageResourceMapTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\UrlStorage\Dependency\Plugin\UrlStorageResourceMapperPluginInterface;
use Spryker\Client\UrlStorage\UrlStorageFactory;
use Spryker\Shared\UrlStorage\UrlStorageConstants;

/**
 * Class UrlStorageRedirectMapperPlugin
 *
 * @method UrlStorageFactory getFactory()
 */
class UrlStorageRedirectMapperPlugin extends AbstractPlugin implements UrlStorageResourceMapperPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\SpyUrlEntityTransfer $spyUrlEntityTransfer
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\UrlStorageResourceMapTransfer
     */
    public function map(SpyUrlEntityTransfer $spyUrlEntityTransfer, $options = [])
    {
        $urlStorageResourceMapTransfer = new UrlStorageResourceMapTransfer();
        $idRedirectUrl = $spyUrlEntityTransfer->getFkResourceRedirect();
        if ($idRedirectUrl) {
            $resourceKey = $this->generateKey($idRedirectUrl);
            $urlStorageResourceMapTransfer->setResourceKey($resourceKey);
            $urlStorageResourceMapTransfer->setType(UrlStorageConstants::REDIRECT_RESOURCE_NAME);
        }

        return $urlStorageResourceMapTransfer;
    }

    /**
     * @param $idRedirectUrl
     *
     * @return mixed
     */
    protected function generateKey($idRedirectUrl)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setStore($this->getStoreName());
        $synchronizationDataTransfer->setReference($idRedirectUrl);

        return $this->getFactory()
            ->getSynchronizationService()
            ->getStorageKeyBuilder(UrlStorageConstants::REDIRECT_RESOURCE_NAME)->generateKey($synchronizationDataTransfer);
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()
            ->getStore()
            ->getStoreName();
    }

}
