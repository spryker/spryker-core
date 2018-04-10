<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication;

use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\OfferGui\Communication\Form\DataProvider\OfferDataProvider;
use Spryker\Zed\OfferGui\Communication\Form\Offer\EditOfferType;
use Spryker\Zed\OfferGui\Communication\Table\OffersTable;
use Spryker\Zed\OfferGui\Communication\Table\OffersTableQueryBuilder;
use Spryker\Zed\OfferGui\Communication\Table\OffersTableQueryBuilderInterface;
use Spryker\Zed\OfferGui\Dependency\Client\OfferGuiToSessionClientInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCartFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCurrencyFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCustomerFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToMessengerFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToMoneyFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToOfferFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToStoreFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Service\OfferGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\OfferGui\Dependency\Service\OfferGuiToUtilSanitizeServiceInterface;
use Spryker\Zed\OfferGui\OfferGuiDependencyProvider;

/**
 * @method \Spryker\Zed\OfferGui\OfferGuiConfig getConfig()
 */
class OfferGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\OfferGui\Communication\Table\OffersTable
     */
    public function createOffersTable(): OffersTable
    {
        return new OffersTable(
            $this->createOffersTableQueryBuilder(),
            $this->getMoneyFacade(),
            $this->getCustomerFacade(),
            $this->getUtilSanitize(),
            $this->getUtilDateTimeService(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\OfferGui\Dependency\Service\OfferGuiToUtilSanitizeServiceInterface
     */
    public function getUtilSanitize(): OfferGuiToUtilSanitizeServiceInterface
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Zed\OfferGui\Dependency\Service\OfferGuiToUtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): OfferGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): OfferGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::FACADE_MONEY);
    }

    public function getStoreFacade(): OfferGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCustomerFacadeInterface
     */
    public function getCustomerFacade(): OfferGuiToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\OfferGui\Communication\Table\OffersTableQueryBuilderInterface
     */
    public function createOffersTableQueryBuilder(): OffersTableQueryBuilderInterface
    {
        return new OffersTableQueryBuilder(
            $this->getPropelQueryOffer()
        );
    }

    /**
     * @return \Orm\Zed\Offer\Persistence\SpyOfferQuery
     */
    public function getPropelQueryOffer()
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::PROPEL_QUERY_OFFER);
    }

    /**
     * @return \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToOfferFacadeInterface
     */
    public function getOfferFacade(): OfferGuiToOfferFacadeInterface
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::FACADE_OFFER);
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getOfferForm(OfferTransfer $offerTransfer)
    {
        $offerDataProvider = $this->createOfferDataProvider();

        $form = $this->getFormFactory()->create(
            $this->getOfferType(),
            $offerDataProvider->getData($offerTransfer),
            $offerDataProvider->getOptions()
        );

        return $form;
    }

    /**
     * @return string
     */
    public function getOfferType(): string
    {
        return EditOfferType::class;
    }

    /**
     * @return \Spryker\Zed\OfferGui\Communication\Form\DataProvider\OfferDataProvider
     */
    public function createOfferDataProvider()
    {
        return new OfferDataProvider(
            $this->getCurrencyFacade(),
            $this->getCustomerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): OfferGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCartFacadeInterface
     */
    public function getCartFacade(): OfferGuiToCartFacadeInterface
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::FACADE_CART);
    }

    /**
     * @return \Spryker\Zed\OfferGui\Dependency\Client\OfferGuiToSessionClientInterface
     */
    public function getSessionClient(): OfferGuiToSessionClientInterface
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToMessengerFacadeInterface
     */
    public function getMessengerFacade(): OfferGuiToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::FACADE_MESSENGER);
    }
}
