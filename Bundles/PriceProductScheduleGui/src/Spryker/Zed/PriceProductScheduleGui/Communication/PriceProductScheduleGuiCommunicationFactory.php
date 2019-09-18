<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication;

use Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PriceProductScheduleGui\Communication\Exporter\PriceProductScheduleCsvExporter;
use Spryker\Zed\PriceProductScheduleGui\Communication\Exporter\PriceProductScheduleCsvExporterInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Extractor\PriceProductScheduleDataExtractor;
use Spryker\Zed\PriceProductScheduleGui\Communication\Extractor\PriceProductScheduleDataExtractorInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint\PriceProductScheduleDateConstraint;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint\PriceProductSchedulePriceConstraint;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint\PriceProductScheduleUniqueConstraint;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\PriceProdductScheduleDeleteForm;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\PriceProductScheduleForm;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\PriceProductScheduleImportFormType;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\PriceProductScheduleListForm;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleDeleteFormDataProvider;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleFormDataProvider;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleListFormDataProvider;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Transformer\DateTransformer;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Transformer\PriceTransformer;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\PriceProductScheduleDataFormatter;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\PriceProductScheduleDataFormatterInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\Redirect\PriceProductScheduleRedirectInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\Redirect\PriceProductScheduleRedirectStrategyResolver;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\Redirect\PriceProductScheduleRedirectStrategyResolverInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\Redirect\PriceProductScheduleRedirectToProductAbstract;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\Redirect\PriceProductScheduleRedirectToProductConcrete;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\Redirect\PriceProductScheduleRedirectToScheduleList;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatter;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\CurrencyMapper;
use Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\CurrencyMapperInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\AbstractProductTabCreator;
use Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\AbstractProductTabCreatorInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\ConcreteProductTabCreator;
use Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\ConcreteProductTabCreatorInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\Formatter\TableFormatter;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\Formatter\TableFormatterInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\ImportErrorListTable;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\ImportSuccessListTable;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleAbstractTable;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleConcreteTable;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleListTable;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleTable;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleTableForEditList;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\AbstractProductViewExpander;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\AbstractProductViewExpanderInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\ConcreteProductViewExpander;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\ConcreteProductViewExpanderInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\ViewExpanderTableFactoryInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToCurrencyFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToMoneyFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductScheduleFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToProductFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToTranslatorFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceInterface;
use Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig getConfig()
 */
class PriceProductScheduleGuiCommunicationFactory extends AbstractCommunicationFactory implements ViewExpanderTableFactoryInterface
{
    protected const GROUP_AFTER = 'After';

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\AbstractProductTabCreatorInterface
     */
    public function createAbstractProductTabCreator(): AbstractProductTabCreatorInterface
    {
        return new AbstractProductTabCreator();
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\ConcreteProductTabCreatorInterface
     */
    public function createConcreteProductTabCreator(): ConcreteProductTabCreatorInterface
    {
        return new ConcreteProductTabCreator();
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface
     */
    public function createRowFormatter(): RowFormatterInterface
    {
        return new RowFormatter($this->getMoneyFacade(), $this->getStoreFacade(), $this->createCurrencyMapper());
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\CurrencyMapperInterface
     */
    public function createCurrencyMapper(): CurrencyMapperInterface
    {
        return new CurrencyMapper();
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\AbstractProductViewExpanderInterface
     */
    public function createAbstractProductViewExpander(): AbstractProductViewExpanderInterface
    {
        return new AbstractProductViewExpander(
            $this->getPriceProductFacade(),
            $this->getTranslatorFacade(),
            $this
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\ConcreteProductViewExpanderInterface
     */
    public function createConcreteProductViewExpander(): ConcreteProductViewExpanderInterface
    {
        return new ConcreteProductViewExpander(
            $this->getPriceProductFacade(),
            $this->getTranslatorFacade(),
            $this
        );
    }

    /**
     * @param int $idProductAbstract
     * @param int $idPriceType
     *
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleAbstractTable
     */
    public function createPriceProductScheduleAbstractTable(
        int $idProductAbstract,
        int $idPriceType
    ): PriceProductScheduleAbstractTable {
        return new PriceProductScheduleAbstractTable(
            $idProductAbstract,
            $idPriceType,
            $this->createRowFormatter(),
            $this->getPriceProductScheduleQuery()
        );
    }

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param int $idPriceType
     *
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleConcreteTable
     */
    public function createPriceProductScheduleConcreteTable(
        int $idProductConcrete,
        int $idProductAbstract,
        int $idPriceType
    ): PriceProductScheduleConcreteTable {
        return new PriceProductScheduleConcreteTable(
            $idProductConcrete,
            $idProductAbstract,
            $idPriceType,
            $this->createRowFormatter(),
            $this->getPriceProductScheduleQuery()
        );
    }

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
     * @param \Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer $priceProductScheduleListImportResponseTransfer
     *
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Table\ImportErrorListTable
     */
    public function createImportErrorTable(
        PriceProductScheduleListImportResponseTransfer $priceProductScheduleListImportResponseTransfer
    ): ImportErrorListTable {
        return new ImportErrorListTable(
            $priceProductScheduleListImportResponseTransfer,
            $this->getTranslatorFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Table\Formatter\TableFormatterInterface
     */
    public function createTableFormatter(): TableFormatterInterface
    {
        return new TableFormatter();
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
            $priceProductScheduleListTransfer,
            $this->getPriceProductScheduleQuery(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleListTable
     */
    public function createPriceProductScheduleListTable(): PriceProductScheduleListTable
    {
        return new PriceProductScheduleListTable(
            $this->getPriceProductScheduleListPropelQuery(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleFormDataProvider
     */
    public function createPriceProductScheduleFormDataProvider(): PriceProductScheduleFormDataProvider
    {
        return new PriceProductScheduleFormDataProvider(
            $this->getPriceProductFacade(),
            $this->getStoreFacade(),
            $this->getCurrencyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Exporter\PriceProductScheduleCsvExporterInterface
     */
    public function createPriceProductScheduleCsvExporter(): PriceProductScheduleCsvExporterInterface
    {
        return new PriceProductScheduleCsvExporter(
            $this->getPriceProductScheduleFacade(),
            $this->getUtilCsvService()
        );
    }

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleFormDataProvider $formDataProvider
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createPriceProductScheduleForm(
        PriceProductScheduleFormDataProvider $formDataProvider,
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): FormInterface {
        return $this->getFormFactory()->create(
            PriceProductScheduleForm::class,
            $formDataProvider->getData($priceProductScheduleTransfer),
            $formDataProvider->getOptions()
        );
    }

    /**
     * @param int $idPriceProductScheduleList
     *
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleTable
     */
    public function createPriceProductScheduleTable(int $idPriceProductScheduleList): PriceProductScheduleTable
    {
        return new PriceProductScheduleTable(
            $this->createRowFormatter(),
            $this->getPriceProductScheduleQuery(),
            $idPriceProductScheduleList
        );
    }

    /**
     * @param int $idPriceProductScheduleList
     *
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleTableForEditList
     */
    public function createPriceProductScheduleTableForEditList(int $idPriceProductScheduleList): PriceProductScheduleTableForEditList
    {
        return new PriceProductScheduleTableForEditList(
            $this->createRowFormatter(),
            $this->getPriceProductScheduleQuery(),
            $idPriceProductScheduleList
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleDeleteFormDataProvider
     */
    public function createPriceProductScheduleDeleteFormDataProvider(): PriceProductScheduleDeleteFormDataProvider
    {
        return new PriceProductScheduleDeleteFormDataProvider();
    }

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleDeleteFormDataProvider $dataProvider
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     * @param string $redirectUrl
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createPriceProductScheduleDeleteForm(
        PriceProductScheduleDeleteFormDataProvider $dataProvider,
        PriceProductScheduleTransfer $priceProductScheduleTransfer,
        string $redirectUrl
    ): FormInterface {
        return $this->getFormFactory()->create(
            PriceProdductScheduleDeleteForm::class,
            $dataProvider->getData($priceProductScheduleTransfer),
            $dataProvider->getOptions($redirectUrl)
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleListFormDataProvider
     */
    public function createPriceProductScheduleListFormDataProvider(): PriceProductScheduleListFormDataProvider
    {
        return new PriceProductScheduleListFormDataProvider();
    }

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleListFormDataProvider $dataProvider
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createPriceProductScheduleListForm(
        PriceProductScheduleListFormDataProvider $dataProvider,
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): FormInterface {
        return $this->getFormFactory()->create(
            PriceProductScheduleListForm::class,
            $dataProvider->getData($priceProductScheduleListTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint\PriceProductScheduleDateConstraint
     */
    public function createPriceProductScheduleDateConstraint(): PriceProductScheduleDateConstraint
    {
        return new PriceProductScheduleDateConstraint();
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint\PriceProductSchedulePriceConstraint
     */
    public function createPriceProductSchedulePriceConstraint(): PriceProductSchedulePriceConstraint
    {
        return new PriceProductSchedulePriceConstraint();
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint\PriceProductScheduleUniqueConstraint
     */
    public function createPriceProductScheduleUniqueConstraint(): PriceProductScheduleUniqueConstraint
    {
        return new PriceProductScheduleUniqueConstraint([
            PriceProductScheduleUniqueConstraint::OPTION_PRICE_PRODUCT_SCHEDULE_FACADE => $this->getPriceProductScheduleFacade(),
            'groups' => [
                static::GROUP_AFTER,
            ],
        ]);
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createPriceTransformer(): DataTransformerInterface
    {
        return new PriceTransformer($this->getMoneyFacade());
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createDateTransformer(): DataTransformerInterface
    {
        return new DateTransformer();
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Extractor\PriceProductScheduleDataExtractorInterface
     */
    public function createPriceProductScheduleDataExtractor(): PriceProductScheduleDataExtractorInterface
    {
        return new PriceProductScheduleDataExtractor(
            $this->getStoreFacade(),
            $this->createPriceProductScheduleDataFormatter(),
            $this->createPriceProductScheduleRedirectStrategyResolver()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\PriceProductScheduleDataFormatterInterface
     */
    public function createPriceProductScheduleDataFormatter(): PriceProductScheduleDataFormatterInterface
    {
        return new PriceProductScheduleDataFormatter(
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\Redirect\PriceProductScheduleRedirectInterface
     */
    public function createPriceProductScheduleRedirectToProductAbstract(): PriceProductScheduleRedirectInterface
    {
        return new PriceProductScheduleRedirectToProductAbstract();
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\Redirect\PriceProductScheduleRedirectInterface
     */
    public function createPriceProductScheduleRedirectToProductConcrete(): PriceProductScheduleRedirectInterface
    {
        return new PriceProductScheduleRedirectToProductConcrete();
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\Redirect\PriceProductScheduleRedirectInterface
     */
    public function createPriceProductScheduleRedirectToScheduleList(): PriceProductScheduleRedirectInterface
    {
        return new PriceProductScheduleRedirectToScheduleList();
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\Redirect\PriceProductScheduleRedirectStrategyResolverInterface
     */
    public function createPriceProductScheduleRedirectStrategyResolver(): PriceProductScheduleRedirectStrategyResolverInterface
    {
        return new PriceProductScheduleRedirectStrategyResolver([
            PriceProductScheduleRedirectStrategyResolver::KEY_ABSTRACT_PRODUCT => $this->createPriceProductScheduleRedirectToProductAbstract(),
            PriceProductScheduleRedirectStrategyResolver::KEY_CONCRETE_PRODUCT => $this->createPriceProductScheduleRedirectToProductConcrete(),
            PriceProductScheduleRedirectStrategyResolver::KEY_SCHEDULE_LIST => $this->createPriceProductScheduleRedirectToScheduleList(),
        ]);
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): PriceProductScheduleGuiToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): PriceProductScheduleGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): PriceProductScheduleGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): PriceProductScheduleGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductScheduleFacadeInterface
     */
    public function getPriceProductScheduleFacade(): PriceProductScheduleGuiToPriceProductScheduleFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_PRICE_PRODUCT_SCHEDULE);
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    public function getPriceProductScheduleQuery(): SpyPriceProductScheduleQuery
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::PROPEL_QUERY_PRICE_PRODUCT_SCHEDULE);
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery
     */
    public function getPriceProductScheduleListPropelQuery(): SpyPriceProductScheduleListQuery
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::PROPEL_QUERY_PRICE_PRODUCT_SCHEDULE_LIST);
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceInterface
     */
    public function getUtilCsvService(): PriceProductScheduleGuiToUtilCsvServiceInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::SERVICE_UTIL_CSV);
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): PriceProductScheduleGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToProductFacadeInterface
     */
    public function getProductFacade(): PriceProductScheduleGuiToProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_PRODUCT);
    }
}
