<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Writer;

use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiConfig as ConfigurableBundleCartsRestApiSharedConfig;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Checker\QuotePermissionCheckerInterface;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Mapper\ConfiguredBundleMapperInterface;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToCartsRestApiFacadeInterface;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToPersistentCartFacadeInterface;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToStoreFacadeInterface;

class GuestConfiguredBundleWriter implements GuestConfiguredBundleWriterInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToPersistentCartFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @var \Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToCartsRestApiFacadeInterface
     */
    protected $cartsRestApiFacade;

    /**
     * @var \Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Mapper\ConfiguredBundleMapperInterface
     */
    protected $configuredBundleMapper;

    /**
     * @var \Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Checker\QuotePermissionCheckerInterface
     */
    protected $quotePermissionChecker;

    /**
     * @var \Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToPersistentCartFacadeInterface $persistentCartFacade
     * @param \Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade
     * @param \Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Mapper\ConfiguredBundleMapperInterface $configuredBundleMapper
     * @param \Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Checker\QuotePermissionCheckerInterface $quotePermissionChecker
     * @param \Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ConfigurableBundleCartsRestApiToPersistentCartFacadeInterface $persistentCartFacade,
        ConfigurableBundleCartsRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade,
        ConfiguredBundleMapperInterface $configuredBundleMapper,
        QuotePermissionCheckerInterface $quotePermissionChecker,
        ConfigurableBundleCartsRestApiToStoreFacadeInterface $storeFacade
    ) {
        $this->persistentCartFacade = $persistentCartFacade;
        $this->cartsRestApiFacade = $cartsRestApiFacade;
        $this->configuredBundleMapper = $configuredBundleMapper;
        $this->quotePermissionChecker = $quotePermissionChecker;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addConfiguredBundleToGuestCart(
        CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
    ): QuoteResponseTransfer {
        $this->assertRequiredCreateRequestProperties($createConfiguredBundleRequestTransfer);
        $quoteResponseTransfer = $this->setCustomerQuoteUuid($createConfiguredBundleRequestTransfer->getQuote());

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        if (!$this->quotePermissionChecker->checkQuoteWritePermission($quoteResponseTransfer->getQuoteTransfer())) {
            return $this->addQuoteErrorToResponse($quoteResponseTransfer, ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_UNAUTHORIZED_CART_ACTION);
        }

        $persistentCartChangeTransfer = $this->configuredBundleMapper->mapCreateConfiguredBundleRequestToPersistentCartChange(
            $createConfiguredBundleRequestTransfer,
            (new PersistentCartChangeTransfer())->fromArray($quoteResponseTransfer->getQuoteTransfer()->toArray(), true)
        );

        $quoteResponseTransfer = $this->persistentCartFacade->add($persistentCartChangeTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            $quoteResponseTransfer = $this->addQuoteErrorToResponse($quoteResponseTransfer, ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_ADDING_CONFIGURED_BUNDLE);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function setCustomerQuoteUuid(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = (new QuoteResponseTransfer())
            ->setQuoteTransfer($quoteTransfer)
            ->setIsSuccessful(true);

        if ($quoteTransfer->getUuid()) {
            return $this->cartsRestApiFacade->findQuoteByUuid($quoteTransfer);
        }

        $quoteCriteriaFilterTransfer = (new QuoteCriteriaFilterTransfer())
            ->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference());

        $customerQuoteTransfers = $this->cartsRestApiFacade
            ->getQuoteCollection($quoteCriteriaFilterTransfer)
            ->getQuotes();

        if (!$customerQuoteTransfers->count()) {
            return $this->createGuestQuote($quoteResponseTransfer);
        }

        /** @var \Generated\Shared\Transfer\QuoteTransfer $customerQuoteTransfer */
        $customerQuoteTransfer = $customerQuoteTransfers->offsetGet(0);

        $quoteResponseTransfer->getQuoteTransfer()
            ->setUuid($customerQuoteTransfer->getUuid())
            ->setIdQuote($customerQuoteTransfer->getIdQuote());

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createGuestQuote(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        $currentStore = $this->storeFacade->getCurrentStore();

        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer()
            ->setStore($currentStore)
            ->setCurrency((new CurrencyTransfer())->setCode($currentStore->getDefaultCurrencyIsoCode()));

        $guestQuoteResponseTransfer = $this->cartsRestApiFacade->createQuote($quoteTransfer);

        if (!$guestQuoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer
                ->setErrors($guestQuoteResponseTransfer->getErrors())
                ->setIsSuccessful(false);
        }

        $quoteResponseTransfer->getQuoteTransfer()
            ->setUuid($guestQuoteResponseTransfer->getQuoteTransfer()->getUuid());

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredCreateRequestProperties(CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer): void
    {
        $createConfiguredBundleRequestTransfer
            ->requireQuote()
            ->getQuote()
                ->requireCustomer()
                ->requireCustomerReference()
                ->getCustomer()
                    ->requireCustomerReference();

        $createConfiguredBundleRequestTransfer
            ->requireItems()
            ->requireConfiguredBundle()
            ->getConfiguredBundle()
                ->requireQuantity()
                ->requireTemplate()
                ->getTemplate()
                    ->requireUuid()
                    ->requireName();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param string $errorIdentifier
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function addQuoteErrorToResponse(QuoteResponseTransfer $quoteResponseTransfer, string $errorIdentifier): QuoteResponseTransfer
    {
        return $quoteResponseTransfer
            ->setIsSuccessful(false)
            ->addError((new QuoteErrorTransfer())->setErrorIdentifier($errorIdentifier));
    }
}
