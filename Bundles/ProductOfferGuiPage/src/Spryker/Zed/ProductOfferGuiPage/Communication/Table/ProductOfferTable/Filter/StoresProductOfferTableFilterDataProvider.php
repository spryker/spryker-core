<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\Filter;

use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\Filter\TableFilterDataProviderInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToStoreFacadeInterface;

class StoresProductOfferTableFilterDataProvider implements TableFilterDataProviderInterface
{
    public const FILTER_NAME = 'stores';

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToStoreFacadeInterface $storeFacade
     */
    public function __construct(ProductOfferGuiPageToStoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    public function getFilterData(): GuiTableFilterTransfer
    {
        return (new GuiTableFilterTransfer())
            ->setId(static::FILTER_NAME)
            ->setTitle('Stores')
            ->setType('select')
            ->addTypeOption(static::OPTION_NAME_MULTISELECT, true)
            ->addTypeOption(static::OPTION_NAME_VALUES, $this->getStoreOptions());
    }

    /**
     * @return array
     */
    protected function getStoreOptions(): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();

        $storeOptions = [];
        foreach ($storeTransfers as $storeTransfer) {
            $storeOptions[] = [
                static::OPTION_VALUE_KEY_TITLE => $storeTransfer->getName(),
                static::OPTION_VALUE_KEY_TITLE => $storeTransfer->getIdStore(),
            ];
        }

        return $storeOptions;
    }
}
