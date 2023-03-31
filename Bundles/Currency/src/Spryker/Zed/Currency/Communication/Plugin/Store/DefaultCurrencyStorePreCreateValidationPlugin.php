<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Communication\Plugin\Store;

use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreExtension\Dependency\Plugin\StorePreCreateValidationPluginInterface;

/**
 * @method \Spryker\Zed\Currency\CurrencyConfig getConfig()
 * @method \Spryker\Zed\Currency\Business\CurrencyFacadeInterface getFacade()
 * @method \Spryker\Zed\Currency\Communication\CurrencyCommunicationFactory getFactory()
 */
class DefaultCurrencyStorePreCreateValidationPlugin extends AbstractPlugin implements StorePreCreateValidationPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validate default currency before store is created.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function validate(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        return $this->getFacade()->validateStoreCurrencies($storeTransfer);
    }
}
