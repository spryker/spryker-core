<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Communication\Plugin\Store;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\Country\Communication\CountryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Country\Business\CountryFacadeInterface getFacade()
 * @method \Spryker\Zed\Country\CountryConfig getConfig()
 * @method \Spryker\Zed\Country\Persistence\CountryRepositoryInterface getRepository()
 */
class CountryStoreCollectionExpanderPlugin extends AbstractPlugin implements StoreCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands store transfers with country names and codes.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expand(array $storeTransfers): array
    {
        return $this->getFacade()->expandStoreTransfersWithCountries($storeTransfers);
    }
}
