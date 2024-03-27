<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication;

use Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Form\ApproveMerchantRelationRequestForm;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Form\DataProvider\MerchantRelationRequestFormDataProvider;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Form\DataProvider\MerchantRelationRequestFormDataProviderInterface;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Form\DataProvider\MerchantRelationRequestListTableFiltersFormDataProvider;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Form\DataProvider\MerchantRelationRequestListTableFiltersFormDataProviderInterface;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Form\DataTransformer\AssigneeCompanyBusinessUnitsDataTransformer;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Form\IsOpenForRelationRequestFormType;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Form\MerchantRelationRequestForm;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Form\MerchantRelationRequestListTableFiltersForm;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Form\RejectMerchantRelationRequestForm;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Reader\MerchantRelationRequestReader;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Reader\MerchantRelationRequestReaderInterface;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Table\MerchantRelationRequestListTable;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Updater\MerchantRelationRequestUpdater;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Updater\MerchantRelationRequestUpdaterInterface;
use Spryker\Zed\MerchantRelationRequestGui\Dependency\Facade\MerchantRelationRequestGuiToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\MerchantRelationRequestGui\Dependency\Facade\MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface;
use Spryker\Zed\MerchantRelationRequestGui\Dependency\Service\MerchantRelationRequestGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\MerchantRelationRequestGui\MerchantRelationRequestGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @method \Spryker\Zed\MerchantRelationRequestGui\MerchantRelationRequestGuiConfig getConfig()
 */
class MerchantRelationRequestGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createIsOpenForRelationRequestFormType(): FormTypeInterface
    {
        return new IsOpenForRelationRequestFormType();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer $merchantRelationRequestConditionsTransfer
     *
     * @return \Spryker\Zed\MerchantRelationRequestGui\Communication\Table\MerchantRelationRequestListTable
     */
    public function createMerchantRelationRequestListTable(
        MerchantRelationRequestConditionsTransfer $merchantRelationRequestConditionsTransfer
    ): MerchantRelationRequestListTable {
        return new MerchantRelationRequestListTable(
            $this->getMerchantRelationRequestPropelQuery(),
            $this->getUtilDateTimeService(),
            $this->getConfig(),
            $merchantRelationRequestConditionsTransfer,
        );
    }

    /**
     * @param array<mixed> $data
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface<mixed>
     */
    public function createMerchantRelationRequestListTableFiltersForm(array $data = [], array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantRelationRequestListTableFiltersForm::class, $data, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer|null $merchantRelationRequestTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createApproveMerchantRelationRequestForm(
        ?MerchantRelationRequestTransfer $merchantRelationRequestTransfer = null
    ): FormInterface {
        return $this->getFormFactory()->create(ApproveMerchantRelationRequestForm::class, $merchantRelationRequestTransfer);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface<mixed>
     */
    public function createRejectMerchantRelationRequestForm(): FormInterface
    {
        return $this->getFormFactory()->create(RejectMerchantRelationRequestForm::class);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequestGui\Communication\Form\DataProvider\MerchantRelationRequestListTableFiltersFormDataProviderInterface
     */
    public function createMerchantRelationRequestListTableFiltersFormDataProvider(): MerchantRelationRequestListTableFiltersFormDataProviderInterface
    {
        return new MerchantRelationRequestListTableFiltersFormDataProvider(
            $this->getMerchantRelationRequestFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer|null $merchantRelationRequestTransfer
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface<mixed>
     */
    public function createMerchantRelationRequestForm(
        ?MerchantRelationRequestTransfer $merchantRelationRequestTransfer = null,
        array $options = []
    ): FormInterface {
        return $this->getFormFactory()->create(MerchantRelationRequestForm::class, $merchantRelationRequestTransfer, $options);
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createAssigneeCompanyBusinessUnitsDataTransformer(): DataTransformerInterface
    {
        return new AssigneeCompanyBusinessUnitsDataTransformer();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequestGui\Communication\Form\DataProvider\MerchantRelationRequestFormDataProviderInterface
     */
    public function createMerchantRelationRequestFormDataProvider(): MerchantRelationRequestFormDataProviderInterface
    {
        return new MerchantRelationRequestFormDataProvider($this->getCompanyBusinessUnitFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequestGui\Communication\Updater\MerchantRelationRequestUpdaterInterface
     */
    public function createMerchantRelationRequestUpdater(): MerchantRelationRequestUpdaterInterface
    {
        return new MerchantRelationRequestUpdater($this->getMerchantRelationRequestFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequestGui\Communication\Reader\MerchantRelationRequestReaderInterface
     */
    public function createMerchantRelationRequestReader(): MerchantRelationRequestReaderInterface
    {
        return new MerchantRelationRequestReader($this->getMerchantRelationRequestFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequestGui\Dependency\Facade\MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface
     */
    public function getMerchantRelationRequestFacade(): MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestGuiDependencyProvider::FACADE_MERCHANT_RELATION_REQUEST);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequestGui\Dependency\Facade\MerchantRelationRequestGuiToCompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacade(): MerchantRelationRequestGuiToCompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestGuiDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequestGui\Dependency\Service\MerchantRelationRequestGuiToUtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): MerchantRelationRequestGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestGuiDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery
     */
    public function getMerchantRelationRequestPropelQuery(): SpyMerchantRelationRequestQuery
    {
        return $this->getProvidedDependency(MerchantRelationRequestGuiDependencyProvider::PROPEL_QUERY_MERCHANT_RELATION_REQUEST);
    }
}
