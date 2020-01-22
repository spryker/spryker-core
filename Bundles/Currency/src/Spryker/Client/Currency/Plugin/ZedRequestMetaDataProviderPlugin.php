<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Currency\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ZedRequest\Dependency\Plugin\MetaDataProviderPluginInterface;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

/**
 * @method \Spryker\Client\Currency\CurrencyFactory getFactory()
 */
class ZedRequestMetaDataProviderPlugin extends AbstractPlugin implements MetaDataProviderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getRequestMetaData(TransferInterface $transfer)
    {
        return $this->getFactory()->createCurrencyBuilder()->getCurrent();
    }
}
