<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Writer;

use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer;
use Spryker\Shared\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiConfig as ConfigurableBundleCartsRestApiSharedConfig;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Checker\QuotePermissionCheckerInterface;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Mapper\ConfiguredBundleMapperInterface;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToCartsRestApiFacadeInterface;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToPersistentCartFacadeInterface;

class ConfiguredBundleWriter implements ConfiguredBundleWriterInterface
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
     * @param \Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToPersistentCartFacadeInterface $persistentCartFacade
     * @param \Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade
     * @param \Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Mapper\ConfiguredBundleMapperInterface $configuredBundleMapper
     * @param \Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Checker\QuotePermissionCheckerInterface $quotePermissionChecker
     */
    public function __construct(
        ConfigurableBundleCartsRestApiToPersistentCartFacadeInterface $persistentCartFacade,
        ConfigurableBundleCartsRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade,
        ConfiguredBundleMapperInterface $configuredBundleMapper,
        QuotePermissionCheckerInterface $quotePermissionChecker
    ) {
        $this->persistentCartFacade = $persistentCartFacade;
        $this->cartsRestApiFacade = $cartsRestApiFacade;
        $this->configuredBundleMapper = $configuredBundleMapper;
        $this->quotePermissionChecker = $quotePermissionChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addConfiguredBundle(
        CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
    ): QuoteResponseTransfer {
        $this->assertRequiredCreateRequestProperties($createConfiguredBundleRequestTransfer);
        $quoteResponseTransfer = $this->setCustomerQuoteUuid($createConfiguredBundleRequestTransfer->getQuote());

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $quoteResponseTransfer = $this->checkQuoteFromRequest($quoteResponseTransfer->getQuoteTransfer());

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
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
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateConfiguredBundleQuantity(
        UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
    ): QuoteResponseTransfer {
        $this->assertRequiredUpdateRequestProperties($updateConfiguredBundleRequestTransfer);
        $updateConfiguredBundleRequestTransfer->requireQuantity();

        $quoteResponseTransfer = $this->checkQuoteFromRequest($updateConfiguredBundleRequestTransfer->getQuote());

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $persistentCartChangeTransfer = $this->configuredBundleMapper->mapUpdateConfiguredBundleRequestToPersistentCartChange(
            $updateConfiguredBundleRequestTransfer,
            (new PersistentCartChangeTransfer())->setQuote((new QuoteTransfer())->fromArray($quoteResponseTransfer->getQuoteTransfer()->toArray(), true))
        );

        if (!$persistentCartChangeTransfer->getItems()->count()) {
            return $this->addQuoteErrorToResponse($quoteResponseTransfer, ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_CONFIGURED_BUNDLE_NOT_FOUND);
        }

        $quoteResponseTransfer = $this->persistentCartFacade->updateQuantity($persistentCartChangeTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            $quoteResponseTransfer = $this->addQuoteErrorToResponse($quoteResponseTransfer, ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_UPDATING_CONFIGURED_BUNDLE);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeConfiguredBundle(
        UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
    ): QuoteResponseTransfer {
        $this->assertRequiredUpdateRequestProperties($updateConfiguredBundleRequestTransfer);
        $quoteResponseTransfer = $this->checkQuoteFromRequest($updateConfiguredBundleRequestTransfer->getQuote());

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $persistentCartChangeTransfer = $this->configuredBundleMapper->mapUpdateConfiguredBundleRequestToPersistentCartChange(
            $updateConfiguredBundleRequestTransfer,
            (new PersistentCartChangeTransfer())->setQuote((new QuoteTransfer())->fromArray($quoteResponseTransfer->getQuoteTransfer()->toArray(), true))
        );

        if (!$persistentCartChangeTransfer->getItems()->count()) {
            return $this->addQuoteErrorToResponse($quoteResponseTransfer, ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_CONFIGURED_BUNDLE_NOT_FOUND);
        }

        $quoteResponseTransfer = $this->persistentCartFacade->remove($persistentCartChangeTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            $quoteResponseTransfer = $this->addQuoteErrorToResponse($quoteResponseTransfer, ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_REMOVING_CONFIGURED_BUNDLE);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function checkQuoteFromRequest(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        if (!$this->quotePermissionChecker->checkQuoteWritePermission($quoteResponseTransfer->getQuoteTransfer())) {
            return $this->addQuoteErrorToResponse($quoteResponseTransfer, ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_UNAUTHORIZED_CART_ACTION);
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
            return $quoteResponseTransfer;
        }

        $quoteCriteriaFilterTransfer = (new QuoteCriteriaFilterTransfer())
            ->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference());

        $customerQuoteTransfers = $this->cartsRestApiFacade
            ->getQuoteCollection($quoteCriteriaFilterTransfer)
            ->getQuotes();

        if (!$customerQuoteTransfers->count()) {
            return $this->addQuoteErrorToResponse($quoteResponseTransfer, ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_CART_NOT_FOUND);
        }

        $quoteResponseTransfer->getQuoteTransfer()->setUuid($customerQuoteTransfers->offsetGet(0)->getUuid());

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
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredUpdateRequestProperties(UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer): void
    {
        $updateConfiguredBundleRequestTransfer
            ->requireGroupKey()
            ->requireQuote()
            ->getQuote()
                ->requireUuid()
                ->requireCustomer()
                ->requireCustomerReference()
                ->getCustomer()
                    ->requireCustomerReference();
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
