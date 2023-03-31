<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Locale\Plugin\ZedRequest;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ZedRequestExtension\Dependency\Plugin\MetaDataProviderPluginInterface;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

/**
 * @method \Spryker\Client\Locale\LocaleFactory getFactory()
 * @method \Spryker\Client\Locale\LocaleClientInterface getClient()
 */
class LocaleMetaDataProviderPlugin extends AbstractPlugin implements MetaDataProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds current locale to Zed Request meta data.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getRequestMetaData(TransferInterface $transfer): TransferInterface
    {
        return (new LocaleTransfer())->setLocaleName($this->getClient()->getCurrentLocale());
    }
}
