<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\StockAddress\Business\Creator\StockAddressCreator;
use Spryker\Zed\StockAddress\Business\Creator\StockAddressCreatorInterface;
use Spryker\Zed\StockAddress\Business\Deleter\StockAddressDeleter;
use Spryker\Zed\StockAddress\Business\Deleter\StockAddressDeleterInterface;
use Spryker\Zed\StockAddress\Business\Expander\StockCollectionExpander;
use Spryker\Zed\StockAddress\Business\Expander\StockCollectionExpanderInterface;
use Spryker\Zed\StockAddress\Business\Updater\StockAddressUpdater;
use Spryker\Zed\StockAddress\Business\Updater\StockAddressUpdaterInterface;

/**
 * @method \Spryker\Zed\StockAddress\Persistence\StockAddressEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\StockAddress\StockAddressConfig getConfig()
 * @method \Spryker\Zed\StockAddress\Persistence\StockAddressRepositoryInterface getRepository()
 */
class StockAddressBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\StockAddress\Business\Expander\StockCollectionExpanderInterface
     */
    public function createStockCollectionExpander(): StockCollectionExpanderInterface
    {
        return new StockCollectionExpander($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\StockAddress\Business\Creator\StockAddressCreatorInterface
     */
    public function createStockAddressCreator(): StockAddressCreatorInterface
    {
        return new StockAddressCreator($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\StockAddress\Business\Updater\StockAddressUpdaterInterface
     */
    public function createStockAddressUpdater(): StockAddressUpdaterInterface
    {
        return new StockAddressUpdater(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createStockAddressDeleter()
        );
    }

    /**
     * @return \Spryker\Zed\StockAddress\Business\Deleter\StockAddressDeleterInterface
     */
    public function createStockAddressDeleter(): StockAddressDeleterInterface
    {
        return new StockAddressDeleter($this->getEntityManager());
    }
}
