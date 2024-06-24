<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui\Communication;

use Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantCommissionGui\Communication\Form\MerchantCommissionImportForm;
use Spryker\Zed\MerchantCommissionGui\Communication\Formatter\MerchantCommissionFormatter;
use Spryker\Zed\MerchantCommissionGui\Communication\Formatter\MerchantCommissionFormatterInterface;
use Spryker\Zed\MerchantCommissionGui\Communication\Mapper\MerchantCommissionCsvMapper;
use Spryker\Zed\MerchantCommissionGui\Communication\Mapper\MerchantCommissionCsvMapperInterface;
use Spryker\Zed\MerchantCommissionGui\Communication\Reader\MerchantCommissionCsvReader;
use Spryker\Zed\MerchantCommissionGui\Communication\Reader\MerchantCommissionCsvReaderInterface;
use Spryker\Zed\MerchantCommissionGui\Communication\Table\MerchantCommissionImportErrorTable;
use Spryker\Zed\MerchantCommissionGui\Communication\Table\MerchantCommissionListTable;
use Spryker\Zed\MerchantCommissionGui\Communication\Transformer\MerchantCommissionAmountTransformer;
use Spryker\Zed\MerchantCommissionGui\Communication\Transformer\MerchantCommissionAmountTransformerInterface;
use Spryker\Zed\MerchantCommissionGui\Communication\Validator\MerchantCommissionCsvValidator;
use Spryker\Zed\MerchantCommissionGui\Communication\Validator\MerchantCommissionCsvValidatorInterface;
use Spryker\Zed\MerchantCommissionGui\Dependency\Facade\MerchantCommissionGuiToGlossaryFacadeInterface;
use Spryker\Zed\MerchantCommissionGui\Dependency\Facade\MerchantCommissionGuiToMerchantCommissionDataExportFacadeInterface;
use Spryker\Zed\MerchantCommissionGui\Dependency\Facade\MerchantCommissionGuiToMerchantCommissionFacadeInterface;
use Spryker\Zed\MerchantCommissionGui\Dependency\Service\MerchantCommissionGuiToUtilCsvServiceInterface;
use Spryker\Zed\MerchantCommissionGui\Dependency\Service\MerchantCommissionGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig getConfig()
 */
class MerchantCommissionGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantCommissionGui\Communication\Table\MerchantCommissionListTable
     */
    public function createMerchantCommissionListTable(): MerchantCommissionListTable
    {
        return new MerchantCommissionListTable(
            $this->getMerchantCommissionPropelQuery(),
            $this->getUtilDateTimeService(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer $merchantCommissionCollectionResponseTransfer
     *
     * @return \Spryker\Zed\MerchantCommissionGui\Communication\Table\MerchantCommissionImportErrorTable
     */
    public function createMerchantCommissionImportErrorTable(
        MerchantCommissionCollectionResponseTransfer $merchantCommissionCollectionResponseTransfer
    ): MerchantCommissionImportErrorTable {
        return new MerchantCommissionImportErrorTable(
            $merchantCommissionCollectionResponseTransfer,
            $this->getGlossaryFacade(),
        );
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantCommissionImportForm(array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantCommissionImportForm::class, [], $options);
    }

    /**
     * @return \Spryker\Zed\MerchantCommissionGui\Communication\Validator\MerchantCommissionCsvValidatorInterface
     */
    public function createMerchantCommissionCsvValidator(): MerchantCommissionCsvValidatorInterface
    {
        return new MerchantCommissionCsvValidator(
            $this->getConfig(),
            $this->getUtilCsvService(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantCommissionGui\Communication\Reader\MerchantCommissionCsvReaderInterface
     */
    public function createMerchantCommissionCsvReader(): MerchantCommissionCsvReaderInterface
    {
        return new MerchantCommissionCsvReader(
            $this->createMerchantCommissionCsvMapper(),
            $this->getUtilCsvService(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantCommissionGui\Communication\Mapper\MerchantCommissionCsvMapperInterface
     */
    public function createMerchantCommissionCsvMapper(): MerchantCommissionCsvMapperInterface
    {
        return new MerchantCommissionCsvMapper($this->createMerchantCommissionAmountTransformer());
    }

    /**
     * @return \Spryker\Zed\MerchantCommissionGui\Communication\Transformer\MerchantCommissionAmountTransformerInterface
     */
    public function createMerchantCommissionAmountTransformer(): MerchantCommissionAmountTransformerInterface
    {
        return new MerchantCommissionAmountTransformer($this->getMerchantCommissionFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantCommissionGui\Communication\Formatter\MerchantCommissionFormatterInterface
     */
    public function createMerchantCommissionFormatter(): MerchantCommissionFormatterInterface
    {
        return new MerchantCommissionFormatter($this->getMerchantCommissionFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantCommissionGui\Dependency\Facade\MerchantCommissionGuiToMerchantCommissionFacadeInterface
     */
    public function getMerchantCommissionFacade(): MerchantCommissionGuiToMerchantCommissionFacadeInterface
    {
        return $this->getProvidedDependency(MerchantCommissionGuiDependencyProvider::FACADE_MERCHANT_COMMISSION);
    }

    /**
     * @return \Spryker\Zed\MerchantCommissionGui\Dependency\Facade\MerchantCommissionGuiToMerchantCommissionDataExportFacadeInterface
     */
    public function getMerchantCommissionDataExportFacade(): MerchantCommissionGuiToMerchantCommissionDataExportFacadeInterface
    {
        return $this->getProvidedDependency(MerchantCommissionGuiDependencyProvider::FACADE_MERCHANT_COMMISSION_DATA_EXPORT);
    }

    /**
     * @return \Spryker\Zed\MerchantCommissionGui\Dependency\Facade\MerchantCommissionGuiToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): MerchantCommissionGuiToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(MerchantCommissionGuiDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\MerchantCommissionGui\Dependency\Service\MerchantCommissionGuiToUtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): MerchantCommissionGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(MerchantCommissionGuiDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \Spryker\Zed\MerchantCommissionGui\Dependency\Service\MerchantCommissionGuiToUtilCsvServiceInterface
     */
    public function getUtilCsvService(): MerchantCommissionGuiToUtilCsvServiceInterface
    {
        return $this->getProvidedDependency(MerchantCommissionGuiDependencyProvider::SERVICE_UTIL_CSV);
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery
     */
    public function getMerchantCommissionPropelQuery(): SpyMerchantCommissionQuery
    {
        return $this->getProvidedDependency(MerchantCommissionGuiDependencyProvider::PROPEL_QUERY_MERCHANT_COMMISSION);
    }
}
