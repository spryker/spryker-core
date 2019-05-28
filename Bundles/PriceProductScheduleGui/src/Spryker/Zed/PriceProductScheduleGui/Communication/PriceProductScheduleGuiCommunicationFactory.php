<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\PriceProductScheduleImportFormType;
use Spryker\Zed\PriceProductScheduleGui\Communication\Importer\PriceProductScheduleCsvReader;
use Spryker\Zed\PriceProductScheduleGui\Communication\Importer\PriceProductScheduleCsvReaderInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\PriceProductScheduleImportMapper;
use Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\PriceProductScheduleImportMapperInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductScheduleFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceInterface;
use Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
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
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Importer\PriceProductScheduleCsvReaderInterface
     */
    public function createPriceProductScheduleCsvReader(): PriceProductScheduleCsvReaderInterface
    {
        return new PriceProductScheduleCsvReader(
            $this->getUtilCsvService(),
            $this->createPriceProductScheduleImportMapper()
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
