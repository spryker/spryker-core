<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Communication\Plugin\Store;

use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreExtension\Dependency\Plugin\StorePreUpdateValidationPluginInterface;

/**
 * @method \Spryker\Zed\Locale\Business\LocaleFacadeInterface getFacade()
 * @method \Spryker\Zed\Locale\LocaleConfig getConfig()
 * @method \Spryker\Zed\Locale\Communication\LocaleCommunicationFactory getFactory()
 */
class DefaultLocaleStorePreUpdateValidationPlugin extends AbstractPlugin implements StorePreUpdateValidationPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates default locale before store is updated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function validate(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        return $this->getFacade()->validateStoreLocale($storeTransfer);
    }
}
