<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Config;

use Exception;
use Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\TaxApp\Business\Exception\TaxAppConfigurationCouldNotBeDeleted;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface;
use Spryker\Zed\TaxApp\Persistence\TaxAppEntityManagerInterface;

class ConfigDeleter implements ConfigDeleterInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const LOG_MESSAGE_CONFIG_DELETION_FAILED = 'Tax app config deletion failed due to exception';

    /**
     * @var \Spryker\Zed\TaxApp\Persistence\TaxAppEntityManagerInterface
     */
    protected $taxAppEntityManager;

    /**
     * @var \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface
     */
    protected $storeFacade;

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
     * @param \Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer
     *
     * @throws \Spryker\Zed\TaxApp\Business\Exception\TaxAppConfigurationCouldNotBeDeleted
     *
     * @return void
     */
    public function delete(TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer): void
    {
        try {
            $taxAppConfigConditionsTransfer = $taxAppConfigCriteriaTransfer->getTaxAppConfigConditionsOrFail();

            if ($taxAppConfigConditionsTransfer->getStoreReferences() !== []) {
                $taxAppConfigCriteriaTransfer = $this->getStoreIdsByStoreReference($taxAppConfigCriteriaTransfer);
            }

            $this->taxAppEntityManager->deleteTaxAppConfig($taxAppConfigCriteriaTransfer);
        } catch (NullValueException $e) {
            $this->logException($e);

            throw new TaxAppConfigurationCouldNotBeDeleted($e->getMessage());
        }
    }

    /**
     * @param \Exception $e
     *
     * @return void
     */
    protected function logException(Exception $e): void
    {
        $this->getLogger()->error(sprintf(static::LOG_MESSAGE_CONFIG_DELETION_FAILED, $e->getMessage()), ['exception' => $e]);
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer
     */
    protected function getStoreIdsByStoreReference(TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer): TaxAppConfigCriteriaTransfer
    {
        foreach ($taxAppConfigCriteriaTransfer->getTaxAppConfigConditionsOrFail()->getStoreReferences() as $storeReference) {
            $storeTransfer = $this->storeFacade->getStoreByStoreReference($storeReference);
            $taxAppConfigCriteriaTransfer->getTaxAppConfigConditionsOrFail()->addFkStore($storeTransfer->getIdStoreOrFail());
        }

        return $taxAppConfigCriteriaTransfer;
    }
}
