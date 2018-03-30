<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication;

use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Offer\Persistence\SpyOfferQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Offer\Business\OfferFacadeInterface;
use Spryker\Zed\OfferGui\Communication\Form\Constraint\SkuExists;
use Spryker\Zed\OfferGui\Communication\Form\OfferType;
use Spryker\Zed\OfferGui\Communication\Table\OffersTable;
use Spryker\Zed\OfferGui\Communication\Table\OffersTableQueryBuilder;
use Spryker\Zed\OfferGui\Communication\Table\OffersTableQueryBuilderInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCustomerFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToMoneyFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToOmsFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToOfferFacadeInterface;
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
     * @return SpyOfferQuery
     */
    public function getPropelQueryOffer()
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::PROPEL_QUERY_OFFER);
    }

    /**
     * @return OfferGuiToOfferFacadeInterface
     */
    public function getOfferFacade(): OfferGuiToOfferFacadeInterface
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::FACADE_OFFER);
    }

    public function getOfferForm(OfferTransfer $offerTransfer)
    {
        $form = $this->getFormFactory()->create(
            OfferType::class,
                $offerTransfer,
                    [
                        'data_class' => OfferTransfer::class,
                    ]
        );

        return $form;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createSkuExistsConstraint(): \Symfony\Component\Validator\Constraint
    {
        return new SkuExists([
            SkuExists::OPTION_PRODUCT_FACADE => Locator::getInstance()->product()->facade(),
        ]);
    }
}
