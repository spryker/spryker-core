<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication;

use Generated\Shared\Transfer\OfferTransfer;
use Generated\Zed\Ide\Offer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\OfferGui\Communication\Form\Constraint\SkuExists;
use Spryker\Zed\OfferGui\Communication\Form\DataProvider\OfferDataProvider;
use Spryker\Zed\OfferGui\Communication\Form\Offer\CreateOfferType;
use Spryker\Zed\OfferGui\Communication\Form\Offer\EditOfferType;
use Spryker\Zed\OfferGui\Communication\Table\OffersTable;
use Spryker\Zed\OfferGui\Communication\Table\OffersTableQueryBuilder;
use Spryker\Zed\OfferGui\Communication\Table\OffersTableQueryBuilderInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCartFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCustomerFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToMoneyFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToOfferFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToOmsFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Service\OfferGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\OfferGui\Dependency\Service\OfferGuiToUtilSanitizeServiceInterface;
use Spryker\Zed\OfferGui\OfferGuiDependencyProvider;
use Symfony\Component\Validator\Constraint;

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
            $this->getUtilDateTimeService()
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

    /**
     * todo: checl
     *
     * @return \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToOmsFacadeInterface
     */
    public function getOmsFacade(): OfferGuiToOmsFacadeInterface
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::FACADE_OMS);
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
     * @param OfferTransfer $offerTransfer
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

    public function getCreateOfferForm(OfferTransfer $offerTransfer)
    {
        $form = $this->getFormFactory()->create(
            CreateOfferType::class,
                $offerTransfer,
            [
                'data_class' => OfferTransfer::class
            ]
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
     * @return OfferDataProvider
     */
    public function createOfferDataProvider()
    {
        return new OfferDataProvider(
            $this->getOfferFacade()
        );
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createSkuExistsConstraint(): Constraint
    {
        return new SkuExists([
            SkuExists::OPTION_PRODUCT_FACADE => Locator::getInstance()->product()->facade(),
        ]);
    }

    /**
     * @return \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCartFacadeInterface
     */
    public function getCartFacade(): OfferGuiToCartFacadeInterface
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::FACADE_CART);
    }
}
