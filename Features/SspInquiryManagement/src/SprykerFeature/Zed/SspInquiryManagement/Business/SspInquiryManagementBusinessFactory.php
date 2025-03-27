<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business;

use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\Comment\Business\CommentFacadeInterface;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use Spryker\Zed\DataImport\Business\DataImportFactoryTrait;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerFeature\Zed\SspAssetManagement\Business\SspAssetManagementFacadeInterface;
use SprykerFeature\Zed\SspInquiryManagement\Business\DashboardDataProvider\InquiryDashboardDataProvider;
use SprykerFeature\Zed\SspInquiryManagement\Business\DashboardDataProvider\InquiryDashboardDataProviderInterface;
use SprykerFeature\Zed\SspInquiryManagement\Business\DataImport\Step\CompanyUserKeyToIdCompanyUserStep;
use SprykerFeature\Zed\SspInquiryManagement\Business\DataImport\Step\SspInquiryStateMachineWriterStep;
use SprykerFeature\Zed\SspInquiryManagement\Business\DataImport\Step\SspInquiryWriterStep;
use SprykerFeature\Zed\SspInquiryManagement\Business\DataImport\Step\StoreCodeToStoreIdStep;
use SprykerFeature\Zed\SspInquiryManagement\Business\Expander\CommentsSspInquiryExpander;
use SprykerFeature\Zed\SspInquiryManagement\Business\Expander\CompanyUserSspInquiryExpander;
use SprykerFeature\Zed\SspInquiryManagement\Business\Expander\FileSspInquiryExpander;
use SprykerFeature\Zed\SspInquiryManagement\Business\Expander\ManualEventsSspInquiryExpander;
use SprykerFeature\Zed\SspInquiryManagement\Business\Expander\SalesOrderSspInquiryExpander;
use SprykerFeature\Zed\SspInquiryManagement\Business\Expander\SspAssetSspInquiryExpander;
use SprykerFeature\Zed\SspInquiryManagement\Business\Expander\SspInquiryExpanderInterface;
use SprykerFeature\Zed\SspInquiryManagement\Business\Expander\StatusHistorySspInquiryExpander;
use SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PostCreate\FileSspInquiryPostCreateHook;
use SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PostCreate\OrderSspInquiryPostCreateHook;
use SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PostCreate\SspAssetSspInquiryPostCreateHook;
use SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PostCreate\SspInquiryPostCreateHookInterface;
use SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PostCreate\StateMachineSspInquiryPostCreateHook;
use SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PreCreate\FileSspInquiryPreCreateHook;
use SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PreCreate\OrderSspInquiryPreCreateHook;
use SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PreCreate\SspAssetSspInquiryPreCreateHook;
use SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PreCreate\SspInquiryPreCreateHookInterface;
use SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PreCreate\StoreSspInquiryPreCreateHook;
use SprykerFeature\Zed\SspInquiryManagement\Business\Reader\SspInquiryReader;
use SprykerFeature\Zed\SspInquiryManagement\Business\Reader\SspInquiryReaderInterface;
use SprykerFeature\Zed\SspInquiryManagement\Business\Validator\SspInquiryValidator;
use SprykerFeature\Zed\SspInquiryManagement\Business\Validator\SspInquiryValidatorInterface;
use SprykerFeature\Zed\SspInquiryManagement\Business\Writer\SspAssetFileDeleter;
use SprykerFeature\Zed\SspInquiryManagement\Business\Writer\SspAssetFileDeleterInterface;
use SprykerFeature\Zed\SspInquiryManagement\Business\Writer\SspInquiryStateWriter;
use SprykerFeature\Zed\SspInquiryManagement\Business\Writer\SspInquiryStateWriterInterface;
use SprykerFeature\Zed\SspInquiryManagement\Business\Writer\SspInquiryWriter;
use SprykerFeature\Zed\SspInquiryManagement\Business\Writer\SspInquiryWriterInterface;
use SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementDependencyProvider;

/**
 * @method \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementEntityManagerInterface getEntityManager()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig getConfig()
 */
class SspInquiryManagementBusinessFactory extends AbstractBusinessFactory
{
    use DataImportFactoryTrait;

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Writer\SspInquiryWriterInterface
     */
    public function createSspInquiryWriter(): SspInquiryWriterInterface
    {
        return new SspInquiryWriter(
            $this->getEntityManager(),
            $this->getSequenceNumberFacade(),
            $this->getStateMachineFacade(),
            $this->getConfig(),
            $this->createSspInquiryValidator(),
            $this->getSspInquiryPreCreateHooks(),
            $this->getSspInquiryPostCreateHooks(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Writer\SspInquiryStateWriterInterface
     */
    public function createSspInquiryStateWriter(): SspInquiryStateWriterInterface
    {
        return new SspInquiryStateWriter(
            $this->createSspInquiryReader(),
            $this->getStateMachineFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return array<\SprykerFeature\Zed\SspInquiryManagement\Business\Expander\SspInquiryExpanderInterface>
     */
    public function getSspInquiryExpanders(): array
    {
        return [
            $this->createManualEventsSspInquiryExpander(),
            $this->createFileSspInquiryExpander(),
            $this->createSalesOrderSspInquiryExpander(),
            $this->createCompanyUserSspInquiryExpander(),
            $this->createStatusHistorySspInquiryExpander(),
            $this->createCommentsSspInquiryExpander(),
            $this->createSsAssetSspInquiryExpander(),
        ];
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Expander\SspInquiryExpanderInterface
     */
    public function createManualEventsSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new ManualEventsSspInquiryExpander(
            $this->getConfig(),
            $this->getStateMachineFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Expander\SspInquiryExpanderInterface
     */
    public function createStatusHistorySspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new StatusHistorySspInquiryExpander(
            $this->getConfig(),
            $this->getStateMachineFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Expander\SspInquiryExpanderInterface
     */
    public function createFileSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new FileSspInquiryExpander(
            $this->getFileManagerFacade(),
            $this->getRepository(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Expander\SspInquiryExpanderInterface
     */
    public function createCompanyUserSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new CompanyUserSspInquiryExpander(
            $this->getCompanyUserFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Expander\SspInquiryExpanderInterface
     */
    public function createSalesOrderSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new SalesOrderSspInquiryExpander(
            $this->getRepository(),
            $this->getSalesFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Expander\SspInquiryExpanderInterface
     */
    public function createSsAssetSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new SspAssetSspInquiryExpander(
            $this->getRepository(),
            $this->getSspAssetManagementFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Expander\SspInquiryExpanderInterface
     */
    public function createCommentsSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new CommentsSspInquiryExpander(
            $this->getCommentFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Reader\SspInquiryReaderInterface
     */
    public function createSspInquiryReader(): SspInquiryReaderInterface
    {
        return new SspInquiryReader(
            $this->getRepository(),
            $this->getSspInquiryExpanders(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Writer\SspAssetFileDeleterInterface
     */
    public function createSspAssetFileDeleter(): SspAssetFileDeleterInterface
    {
        return new SspAssetFileDeleter(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface
     */
    public function getStateMachineFacade(): StateMachineFacadeInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::FACADE_STATE_MACHINE);
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Validator\SspInquiryValidatorInterface
     */
    public function createSspInquiryValidator(): SspInquiryValidatorInterface
    {
        return new SspInquiryValidator($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface
     */
    public function getSequenceNumberFacade(): SequenceNumberFacadeInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface
     */
    public function getFileManagerFacade(): FileManagerFacadeInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::FACADE_FILE_MANAGER);
    }

    /**
     * @return array<\SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PreCreate\SspInquiryPreCreateHookInterface>
     */
    public function getSspInquiryPreCreateHooks(): array
    {
        return [
            $this->createFileSspInquiryPreCreateHook(),
            $this->createOrderSspInquiryPreCreateHook(),
            $this->createStoreSspInquiryPreCreateHook(),
            $this->createSspAssetSspInquiryPreCreateHook(),
        ];
    }

    /**
     * @return array<\SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PostCreate\SspInquiryPostCreateHookInterface>
     */
    public function getSspInquiryPostCreateHooks(): array
    {
        return [
            $this->createOrderSspInquiryPostCreateHook(),
            $this->createFileSspInquiryPostCreateHook(),
            $this->createStateMachineSspInquiryPostCreateHook(),
            $this->createSspAssetSspInquiryPostCreateHook(),
        ];
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PreCreate\SspInquiryPreCreateHookInterface
     */
    public function createFileSspInquiryPreCreateHook(): SspInquiryPreCreateHookInterface
    {
        return new FileSspInquiryPreCreateHook(
            $this->getFileManagerFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PreCreate\SspInquiryPreCreateHookInterface
     */
    public function createStoreSspInquiryPreCreateHook(): SspInquiryPreCreateHookInterface
    {
        return new StoreSspInquiryPreCreateHook(
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PreCreate\SspInquiryPreCreateHookInterface
     */
    public function createOrderSspInquiryPreCreateHook(): SspInquiryPreCreateHookInterface
    {
        return new OrderSspInquiryPreCreateHook(
            $this->getSalesFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PreCreate\SspInquiryPreCreateHookInterface
     */
    public function createSspAssetSspInquiryPreCreateHook(): SspInquiryPreCreateHookInterface
    {
        return new SspAssetSspInquiryPreCreateHook(
            $this->getSspAssetManagementFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PostCreate\SspInquiryPostCreateHookInterface
     */
    public function createOrderSspInquiryPostCreateHook(): SspInquiryPostCreateHookInterface
    {
        return new OrderSspInquiryPostCreateHook(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PostCreate\SspInquiryPostCreateHookInterface
     */
    public function createFileSspInquiryPostCreateHook(): SspInquiryPostCreateHookInterface
    {
        return new FileSspInquiryPostCreateHook(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PostCreate\SspInquiryPostCreateHookInterface
     */
    public function createSspAssetSspInquiryPostCreateHook(): SspInquiryPostCreateHookInterface
    {
        return new SspAssetSspInquiryPostCreateHook(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PostCreate\StateMachineSspInquiryPostCreateHook
     */
    public function createStateMachineSspInquiryPostCreateHook(): SspInquiryPostCreateHookInterface
    {
        return new StateMachineSspInquiryPostCreateHook(
            $this->getConfig(),
            $this->getStateMachineFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    public function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): CompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getSspInquiryDataImporter(): DataImporterInterface
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getSspInquiryDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        if ($dataSetStepBroker instanceof DataImportStepAwareInterface) {
            $dataSetStepBroker
                ->addStep($this->createCompanyUserKeyToIdCompanyUserStep())
                ->addStep($this->createStoreCodeToStoreIdStep())
                ->addStep($this->createSspInquiryWriterStep())
                ->addStep($this->createSspInquiryStateMachineWriterStep());
        }

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createSspInquiryWriterStep(): DataImportStepInterface
    {
        return new SspInquiryWriterStep(
            $this->getConfig(),
            $this->getSequenceNumberFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCompanyUserKeyToIdCompanyUserStep(): DataImportStepInterface
    {
        return new CompanyUserKeyToIdCompanyUserStep($this->getCompanyUserQuery());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStoreCodeToStoreIdStep(): DataImportStepInterface
    {
        return new StoreCodeToStoreIdStep($this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createSspInquiryStateMachineWriterStep(): DataImportStepInterface
    {
        return new SspInquiryStateMachineWriterStep(
            $this->getStateMachineFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    public function getCompanyUserQuery(): SpyCompanyUserQuery
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::PROPEL_QUERY_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    public function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Comment\Business\CommentFacadeInterface
     */
    public function getCommentFacade(): CommentFacadeInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::FACADE_COMMENT);
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Business\DashboardDataProvider\InquiryDashboardDataProviderInterface
     */
    public function createInquiryDashboardDataProvider(): InquiryDashboardDataProviderInterface
    {
        return new InquiryDashboardDataProvider($this->createSspInquiryReader(), $this->getConfig());
    }

    /**
     * @return \SprykerFeature\Zed\SspAssetManagement\Business\SspAssetManagementFacadeInterface
     */
    public function getSspAssetManagementFacade(): SspAssetManagementFacadeInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::FACADE_SSP_ASSET_MANAGEMENT);
    }
}
