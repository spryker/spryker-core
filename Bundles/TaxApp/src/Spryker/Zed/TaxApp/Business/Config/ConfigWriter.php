<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Config;

use Exception;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Spryker\Zed\TaxApp\Business\Exception\TaxAppConfigurationCouldNotBeSaved;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface;
use Spryker\Zed\TaxApp\Persistence\TaxAppEntityManagerInterface;

class ConfigWriter implements ConfigWriterInterface
{
    /**
     * @var string
     */
    protected const LOG_MESSAGE_CONFIG_SAVING_FAILED = 'Tax app config saving failed due to exception. Exception message: %s';

    /**
     * @var \Spryker\Zed\TaxApp\Persistence\TaxAppEntityManagerInterface
     */
    protected TaxAppEntityManagerInterface $taxAppEntityManager;

    /**
     * @var \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface
     */
    protected TaxAppToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\TaxApp\Persistence\TaxAppEntityManagerInterface $taxAppEntityManager
     * @param \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        TaxAppEntityManagerInterface $taxAppEntityManager,
        TaxAppToStoreFacadeInterface $storeFacade
    ) {
        $this->taxAppEntityManager = $taxAppEntityManager;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     *
     * @throws \Spryker\Zed\TaxApp\Business\Exception\TaxAppConfigurationCouldNotBeSaved
     *
     * @return void
     */
    public function write(TaxAppConfigTransfer $taxAppConfigTransfer): void
    {
        $storeTransfer = new StoreTransfer();

        if ($taxAppConfigTransfer->getStoreReference() !== null) {
            $storeTransfer = $this->storeFacade->getStoreByStoreReference($taxAppConfigTransfer->getStoreReference());
        }

        try {
            $this->taxAppEntityManager->saveTaxAppConfig($taxAppConfigTransfer, $storeTransfer);
        } catch (Exception $e) {
            throw new TaxAppConfigurationCouldNotBeSaved(sprintf(static::LOG_MESSAGE_CONFIG_SAVING_FAILED, $e->getMessage()), 0, $e);
        }
    }
}
