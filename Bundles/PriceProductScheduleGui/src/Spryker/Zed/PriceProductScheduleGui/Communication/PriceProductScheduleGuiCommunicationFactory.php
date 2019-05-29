<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication;

use Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PriceProductScheduleGui\Communication\Csv\PriceProductScheduleCsvReader;
use Spryker\Zed\PriceProductScheduleGui\Communication\Csv\PriceProductScheduleCsvReaderInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Csv\PriceProductScheduleCsvValidator;
use Spryker\Zed\PriceProductScheduleGui\Communication\Csv\PriceProductScheduleCsvValidatorInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\PriceProductScheduleImportFormType;
use Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\PriceProductScheduleImportMapper;
use Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\PriceProductScheduleImportMapperInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\ImportErrorListTable;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\ImportSuccessListTable;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductScheduleFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceInterface;
use Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Persistence\PriceProductScheduleGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig getConfig()
 */
class PriceProductScheduleGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getPriceProductScheduleImportForm(
        array $options = []
    ): FormInterface {
        return $this
            ->getFormFactory()
            ->create(
                PriceProductScheduleImportFormType::class,
                [],
                $options
            );
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\PriceProductScheduleImportMapperInterface
     */
    public function createPriceProductScheduleImportMapper(): PriceProductScheduleImportMapperInterface
    {
        return new PriceProductScheduleImportMapper($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Csv\PriceProductScheduleCsvReaderInterface
     */
    public function createPriceProductScheduleCsvReader(): PriceProductScheduleCsvReaderInterface
    {
        return new PriceProductScheduleCsvReader(
            $this->getUtilCsvService(),
            $this->createPriceProductScheduleImportMapper()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Csv\PriceProductScheduleCsvValidatorInterface
     */
    public function createPriceProductScheduleCsvValidator(): PriceProductScheduleCsvValidatorInterface
    {
        return new PriceProductScheduleCsvValidator(
            $this->getUtilCsvService(),
            $this->getConfig()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer $priceProductScheduleListImportResponseTransfer
     *
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Table\ImportErrorListTable
     */
    public function createImportErrorTable(
        PriceProductScheduleListImportResponseTransfer $priceProductScheduleListImportResponseTransfer
    ): ImportErrorListTable {
        return new ImportErrorListTable(
            $priceProductScheduleListImportResponseTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Table\ImportSuccessListTable
     */
    public function createImportSuccessListTable(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): ImportSuccessListTable {
        return new ImportSuccessListTable(
            $this->getRepository(),
            $priceProductScheduleListTransfer,
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductScheduleFacadeInterface
     */
    public function getPriceProductScheduleFacade(): PriceProductScheduleGuiToPriceProductScheduleFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_PRICE_PRODUCT_SCHEDULE);
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceInterface
     */
    public function getUtilCsvService(): PriceProductScheduleGuiToUtilCsvServiceInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::SERVICE_UTIL_CSV);
    }
}
