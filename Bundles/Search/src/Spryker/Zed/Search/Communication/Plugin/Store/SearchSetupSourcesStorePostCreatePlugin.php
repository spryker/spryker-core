<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Communication\Plugin\Store;

use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Psr\Log\NullLogger;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreExtension\Dependency\Plugin\StorePostCreatePluginInterface;

/**
 * @method \Spryker\Zed\Search\Business\SearchFacadeInterface getFacade()
 * @method \Spryker\Zed\Search\Communication\SearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\Search\SearchConfig getConfig()
 */
class SearchSetupSourcesStorePostCreatePlugin extends AbstractPlugin implements StorePostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function execute(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        $this->getFacade()->installSources(new NullLogger(), $storeTransfer->getNameOrFail());

        return (new StoreResponseTransfer())->setStore($storeTransfer)->setIsSuccessful(true);
    }
}
