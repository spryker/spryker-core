<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Communication;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryQuery;
use Spryker\Service\FileManager\FileManagerServiceInterface;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Mail\Business\MailFacadeInterface;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use SprykerFeature\Zed\SspInquiryManagement\Communication\Form\DataProvider\SspInquiryFilterFormDataProvider;
use SprykerFeature\Zed\SspInquiryManagement\Communication\Form\DataProvider\SspInquiryFilterFormDataProviderInterface;
use SprykerFeature\Zed\SspInquiryManagement\Communication\Form\DataProvider\TriggerEventFormDataProvider;
use SprykerFeature\Zed\SspInquiryManagement\Communication\Form\DataProvider\TriggerEventFormDataProviderInterface;
use SprykerFeature\Zed\SspInquiryManagement\Communication\Form\SspInquiryFilterForm;
use SprykerFeature\Zed\SspInquiryManagement\Communication\Form\TriggerEventForm;
use SprykerFeature\Zed\SspInquiryManagement\Communication\Table\OrderSspInquiryTable;
use SprykerFeature\Zed\SspInquiryManagement\Communication\Table\SspInquiryTable;
use SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementEntityManagerInterface getEntityManager()
 * @method \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig getConfig()
 */
class SspInquiryManagementCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return array<\Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface>
     */
    public function getStateMachineCommandPlugins(): array
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::PLUGINS_STATE_MACHINE_COMMAND);
    }

    /**
     * @return array<\Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface>
     */
    public function getStateMachineConditionPlugins(): array
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::PLUGINS_STATE_MACHINE_CONDITION);
    }

    /**
     * @return \Spryker\Zed\Mail\Business\MailFacadeInterface
     */
    public function getMailFacade(): MailFacadeInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface
     */
    public function getStateMachineFacade(): StateMachineFacadeInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::FACADE_STATE_MACHINE);
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Communication\Form\DataProvider\TriggerEventFormDataProviderInterface
     */
    public function createTriggerEventFormDataProvider(): TriggerEventFormDataProviderInterface
    {
        return new TriggerEventFormDataProvider(
            $this->getFacade(),
            $this->getStateMachineFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @param array<mixed> $data
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getTriggerEventForm(array $data, array $options): FormInterface
    {
        return $this->getFormFactory()->create(TriggerEventForm::class, $data, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryConditionsTransfer $sspInquiryConditionsTransfer
     *
     * @return \SprykerFeature\Zed\SspInquiryManagement\Communication\Table\SspInquiryTable
     */
    public function createSspInquiryTable(SspInquiryConditionsTransfer $sspInquiryConditionsTransfer): SspInquiryTable
    {
        return new SspInquiryTable(
            $this->getSspInquiryQuery(),
            $this->getRepository(),
            $this->getConfig(),
            $this->getUtilDateTimeService(),
            $sspInquiryConditionsTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryConditionsTransfer $sspInquiryConditionsTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getSspInquiryFilterForm(SspInquiryConditionsTransfer $sspInquiryConditionsTransfer): FormInterface
    {
        return $this->getFormFactory()->create(SspInquiryFilterForm::class, $sspInquiryConditionsTransfer, $this->createSspInquiryFilterFormDataProvider()->getOptions());
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Communication\Form\DataProvider\SspInquiryFilterFormDataProviderInterface
     */
    public function createSspInquiryFilterFormDataProvider(): SspInquiryFilterFormDataProviderInterface
    {
        return new SspInquiryFilterFormDataProvider(
            $this->getConfig(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \SprykerFeature\Zed\SspInquiryManagement\Communication\Table\OrderSspInquiryTable
     */
    public function createOrderSspInquiryTable(OrderTransfer $orderTransfer): OrderSspInquiryTable
    {
        return new OrderSspInquiryTable(
            $this->getSspInquiryQuery(),
            $this->getRepository(),
            $this->getConfig(),
            $orderTransfer,
        );
    }

    /**
     * @return \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryQuery
     */
    public function getSspInquiryQuery(): SpySspInquiryQuery
    {
        return SpySspInquiryQuery::create();
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface
     */
    public function getFileManagerFacade(): FileManagerFacadeInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::FACADE_FILE_MANAGER);
    }

    /**
     * @return \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): UtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \Spryker\Service\FileManager\FileManagerServiceInterface
     */
    public function getFileManagerService(): FileManagerServiceInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::SERVICE_FILE_MANAGER);
    }
}
