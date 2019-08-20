<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication;

use Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PriceProductScheduleGui\Communication\Extractor\PriceProductScheduleDataExtractor;
use Spryker\Zed\PriceProductScheduleGui\Communication\Extractor\PriceProductScheduleDataExtractorInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint\PriceProductScheduleDateConstraint;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint\PriceProductSchedulePriceConstraint;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\PriceProductScheduleForm;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\PriceProductScheduleImportFormType;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleFormDataProvider;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Transformer\PriceTransformer;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\PriceProductScheduleDataFormatter;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\PriceProductScheduleDataFormatterInterface;
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
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\AbstractProductViewExpander;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\AbstractProductViewExpanderInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\ConcreteProductViewExpander;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\ConcreteProductViewExpanderInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\ViewExpanderTableFactoryInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToCurrencyFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToMoneyFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductScheduleFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToTranslatorFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig getConfig()
 */
class PriceProductScheduleGuiCommunicationFactory extends AbstractCommunicationFactory implements ViewExpanderTableFactoryInterface
{
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
     * @param int $idPriceType
     *
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleConcreteTable
     */
    public function createPriceProductScheduleConcreteTable(
        int $idProductConcrete,
        int $idPriceType
    ): PriceProductScheduleConcreteTable {
        return new PriceProductScheduleConcreteTable(
            $idProductConcrete,
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
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createPriceTransformer(): DataTransformerInterface
    {
        return new PriceTransformer();
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Extractor\PriceProductScheduleDataExtractorInterface
     */
    public function createPriceProductScheduleDataExtractor(): PriceProductScheduleDataExtractorInterface
    {
        return new PriceProductScheduleDataExtractor(
            $this->getStoreFacade(),
            $this->createPriceProductScheduleDataFormatter()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\PriceProductScheduleDataFormatterInterface
     */
    public function createPriceProductScheduleDataFormatter(): PriceProductScheduleDataFormatterInterface
    {
        return new PriceProductScheduleDataFormatter();
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
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): PriceProductScheduleGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_CURRENCY);
    }
}
