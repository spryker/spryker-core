<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Communication\Plugin\Store;

use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreExtension\Dependency\Plugin\StorePostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\Country\Communication\CountryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Country\Business\CountryFacadeInterface getFacade()
 * @method \Spryker\Zed\Country\CountryConfig getConfig()
 */
class CountryStorePostUpdatePlugin extends AbstractPlugin implements StorePostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Updates `CountryStore` entities after store is updated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function execute(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        return $this->getFacade()->updateStoreCountries($storeTransfer);
    }
}
