<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Business;

use Spryker\Zed\DataImportMerchant\Business\Creator\DataImportMerchantFileCreator;
use Spryker\Zed\DataImportMerchant\Business\Creator\DataImportMerchantFileCreatorInterface;
use Spryker\Zed\DataImportMerchant\Business\Filter\DataImportMerchantFileFilter;
use Spryker\Zed\DataImportMerchant\Business\Filter\DataImportMerchantFileFilterInterface;
use Spryker\Zed\DataImportMerchant\Business\Importer\DataImportMerchantFileImporter;
use Spryker\Zed\DataImportMerchant\Business\Importer\DataImportMerchantFileImporterInterface;
use Spryker\Zed\DataImportMerchant\Business\Provider\PossibleCsvHeaderProvider;
use Spryker\Zed\DataImportMerchant\Business\Provider\PossibleCsvHeaderProviderInterface;
use Spryker\Zed\DataImportMerchant\Business\Reader\DataImportMerchantFileReader;
use Spryker\Zed\DataImportMerchant\Business\Reader\DataImportMerchantFileReaderInterface;
use Spryker\Zed\DataImportMerchant\Business\Reader\UserReader;
use Spryker\Zed\DataImportMerchant\Business\Reader\UserReaderInterface;
use Spryker\Zed\DataImportMerchant\Business\Validator\DataImportMerchantFileValidator;
use Spryker\Zed\DataImportMerchant\Business\Validator\DataImportMerchantFileValidatorInterface;
use Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile\FileContentTypeValidatorRule;
use Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile\ImporterTypeValidatorRule;
use Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile\MerchantExistsValidatorRule;
use Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile\UserExistsValidatorRule;
use Spryker\Zed\DataImportMerchant\Business\Writer\DataImportMerchantFileWriter;
use Spryker\Zed\DataImportMerchant\Business\Writer\DataImportMerchantFileWriterInterface;
use Spryker\Zed\DataImportMerchant\DataImportMerchantDependencyProvider;
use Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToDataImportFacadeInterface;
use Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToMerchantFacadeInterface;
use Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToUserFacadeInterface;
use Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToFileSystemServiceInterface;
use Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToUtilEncodingServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DataImportMerchant\Persistence\DataImportMerchantRepositoryInterface getRepository()
 * @method \Spryker\Zed\DataImportMerchant\Persistence\DataImportMerchantEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\DataImportMerchant\DataImportMerchantConfig getConfig()
 */
class DataImportMerchantBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImportMerchant\Business\Creator\DataImportMerchantFileCreatorInterface
     */
    public function createDataImportMerchantFileCreator(): DataImportMerchantFileCreatorInterface
    {
        return new DataImportMerchantFileCreator(
            $this->getEntityManager(),
            $this->createDataImportMerchantFileValidator(),
            $this->createDataImportMerchantFileFilter(),
            $this->createDataImportMerchantFileWriter(),
            $this->getConfig(),
            $this->getDataImportMerchantFileRequestExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Business\Importer\DataImportMerchantFileImporterInterface
     */
    public function createDataImportMerchantFileImporter(): DataImportMerchantFileImporterInterface
    {
        return new DataImportMerchantFileImporter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getConfig(),
            $this->getDataImportFacade(),
            $this->getUtilEncodingService(),
            $this->getMerchantFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Business\Validator\DataImportMerchantFileValidatorInterface
     */
    public function createDataImportMerchantFileValidator(): DataImportMerchantFileValidatorInterface
    {
        return new DataImportMerchantFileValidator(
            $this->getDataImportMerchantFileValidatorPlugins(),
            $this->getDataImportMerchantFileValidatorRules(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Business\Filter\DataImportMerchantFileFilterInterface
     */
    public function createDataImportMerchantFileFilter(): DataImportMerchantFileFilterInterface
    {
        return new DataImportMerchantFileFilter();
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Business\Writer\DataImportMerchantFileWriterInterface
     */
    public function createDataImportMerchantFileWriter(): DataImportMerchantFileWriterInterface
    {
        return new DataImportMerchantFileWriter(
            $this->getFileSystemService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Business\Reader\DataImportMerchantFileReaderInterface
     */
    public function createDataImportMerchantFileReader(): DataImportMerchantFileReaderInterface
    {
        return new DataImportMerchantFileReader(
            $this->getRepository(),
            $this->getDataImportMerchantFileExpanderPlugins(),
        );
    }

    /**
     * @return list<\Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile\DataImportMerchantFileValidatorRuleInterface>
     */
    public function getDataImportMerchantFileValidatorRules(): array
    {
        return [
            $this->createFileContentTypeValidatorRule(),
            $this->createImporterTypeValidatorRule(),
            $this->createUserExistsValidatorRule(),
            $this->createMerchantExistsValidatorRule(),
        ];
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile\ImporterTypeValidatorRule
     */
    public function createImporterTypeValidatorRule(): ImporterTypeValidatorRule
    {
        return new ImporterTypeValidatorRule(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile\UserExistsValidatorRule
     */
    public function createUserExistsValidatorRule(): UserExistsValidatorRule
    {
        return new UserExistsValidatorRule(
            $this->createUserReader(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile\MerchantExistsValidatorRule
     */
    public function createMerchantExistsValidatorRule(): MerchantExistsValidatorRule
    {
        return new MerchantExistsValidatorRule(
            $this->getMerchantFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile\FileContentTypeValidatorRule
     */
    public function createFileContentTypeValidatorRule(): FileContentTypeValidatorRule
    {
        return new FileContentTypeValidatorRule(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Business\Reader\UserReaderInterface
     */
    public function createUserReader(): UserReaderInterface
    {
        return new UserReader(
            $this->getUserFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Business\Provider\PossibleCsvHeaderProviderInterface
     */
    public function createPossibleCsvHeaderProvider(): PossibleCsvHeaderProviderInterface
    {
        return new PossibleCsvHeaderProvider(
            $this->getConfig(),
            $this->getPossibleCsvHeaderExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToFileSystemServiceInterface
     */
    public function getFileSystemService(): DataImportMerchantToFileSystemServiceInterface
    {
        return $this->getProvidedDependency(DataImportMerchantDependencyProvider::SERVICE_FILE_SYSTEM);
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): DataImportMerchantToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(DataImportMerchantDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToUserFacadeInterface
     */
    public function getUserFacade(): DataImportMerchantToUserFacadeInterface
    {
        return $this->getProvidedDependency(DataImportMerchantDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToDataImportFacadeInterface
     */
    public function getDataImportFacade(): DataImportMerchantToDataImportFacadeInterface
    {
        return $this->getProvidedDependency(DataImportMerchantDependencyProvider::FACADE_DATA_IMPORT);
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToMerchantFacadeInterface
     */
    public function getMerchantFacade(): DataImportMerchantToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(DataImportMerchantDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return list<\Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileValidatorPluginInterface>
     */
    public function getDataImportMerchantFileValidatorPlugins(): array
    {
        return $this->getProvidedDependency(DataImportMerchantDependencyProvider::PLUGINS_DATA_IMPORT_MERCHANT_FILE_VALIDATOR);
    }

    /**
     * @return list<\Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileExpanderPluginInterface>
     */
    public function getDataImportMerchantFileExpanderPlugins(): array
    {
        return $this->getProvidedDependency(DataImportMerchantDependencyProvider::PLUGINS_DATA_IMPORT_MERCHANT_FILE_EXPANDER);
    }

    /**
     * @return list<\Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileRequestExpanderPluginInterface>
     */
    public function getDataImportMerchantFileRequestExpanderPlugins(): array
    {
        return $this->getProvidedDependency(DataImportMerchantDependencyProvider::PLUGINS_DATA_IMPORT_MERCHANT_FILE_REQUEST_EXPANDER);
    }

    /**
     * @return list<\Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\PossibleCsvHeaderExpanderPluginInterface>
     */
    public function getPossibleCsvHeaderExpanderPlugins(): array
    {
        return $this->getProvidedDependency(DataImportMerchantDependencyProvider::PLUGINS_POSSIBLE_CSV_HEADER_EXPANDER);
    }
}
