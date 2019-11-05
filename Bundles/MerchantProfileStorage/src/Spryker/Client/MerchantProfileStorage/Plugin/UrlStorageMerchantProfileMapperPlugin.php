<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProfileStorage\Plugin;

use Generated\Shared\Transfer\UrlStorageResourceMapTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\UrlStorage\Dependency\Plugin\UrlStorageResourceMapperPluginInterface;

/**
 * @method \Spryker\Client\MerchantProfileStorage\MerchantProfileStorageFactory getFactory()
 */
class UrlStorageMerchantProfileMapperPlugin extends AbstractPlugin implements UrlStorageResourceMapperPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\UrlStorageResourceMapTransfer
     */
    public function map(UrlStorageTransfer $urlStorageTransfer, array $options = []): UrlStorageResourceMapTransfer
    {
        return $this->getFactory()
            ->createUrlStorageMerchantProfileMapper()
            ->mapUrlStorageTransferToUrlStorageResourceMapTransfer($urlStorageTransfer);
    }
}
