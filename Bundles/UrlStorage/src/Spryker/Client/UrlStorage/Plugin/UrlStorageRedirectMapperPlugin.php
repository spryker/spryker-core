<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\UrlStorage\Plugin;

use Generated\Shared\Transfer\SpyUrlTransfer;
use Generated\Shared\Transfer\UrlStorageResourceMapTransfer;
use Spryker\Client\UrlStorage\Dependency\Plugin\UrlStorageResourceMapperPluginInterface;
use Spryker\Shared\Kernel\Store;

class UrlStorageRedirectMapperPlugin implements UrlStorageResourceMapperPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\SpyUrlTransfer $spyUrlTransfer
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\UrlStorageResourceMapTransfer
     */
    public function map(SpyUrlTransfer $spyUrlTransfer, $options = [])
    {
        $urlStorageResourceMapTransfer = new UrlStorageResourceMapTransfer();
        if ($spyUrlTransfer->getFkResourceRedirect()) {
            $resourceKey = sprintf(
                'redirect:%s:%d',
                strtolower($this->getStoreName()),
                $spyUrlTransfer->getFkResourceRedirect()
            );
            $urlStorageResourceMapTransfer->setResourceKey($resourceKey);
            $urlStorageResourceMapTransfer->setType('redirect');
        }

        return $urlStorageResourceMapTransfer;
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return Store::getInstance()->getStoreName();
    }

}
