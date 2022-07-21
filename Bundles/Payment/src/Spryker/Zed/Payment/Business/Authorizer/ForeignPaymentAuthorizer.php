<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Authorizer;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer;
use Generated\Shared\Transfer\PaymentAuthorizeResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Client\Payment\PaymentClientInterface;
use Spryker\Service\Payment\PaymentServiceInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Payment\Business\Mapper\QuoteDataMapperInterface;
use Spryker\Zed\Payment\Dependency\Facade\PaymentToLocaleFacadeInterface;
use Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreReferenceFacadeInterface;
use Spryker\Zed\Payment\PaymentConfig;
use Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface;

class ForeignPaymentAuthorizer implements ForeignPaymentAuthorizerInterface
{
    /**
     * @var string
     */
    protected const ERROR_CODE_PAYMENT_FAILED = 'payment failed';

    /**
     * @var \Spryker\Zed\Payment\Business\Mapper\QuoteDataMapperInterface
     */
    protected $quoteDataMapper;

    /**
     * @var \Spryker\Zed\Payment\Dependency\Facade\PaymentToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface
     */
    protected $paymentRepository;

    /**
     * @var \Spryker\Client\Payment\PaymentClientInterface
     */
    protected $paymentClient;

    /**
     * @var \Spryker\Zed\Payment\PaymentConfig
     */
    protected $paymentConfig;

    /**
     * @var \Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreReferenceFacadeInterface
     */
    protected $storeReferenceFacade;

    /**
     * @var \Spryker\Service\Payment\PaymentServiceInterface
     */
    protected $paymentService;

    /**
     * @var array<int, \Spryker\Zed\PaymentExtension\Dependency\Plugin\PaymentAuthorizeRequestExpanderPluginInterface>
     */
    protected $paymentAuthorizeRequestExpanderPlugins;

    /**
     * @param \Spryker\Zed\Payment\Business\Mapper\QuoteDataMapperInterface $quoteDataMapper
     * @param \Spryker\Zed\Payment\Dependency\Facade\PaymentToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface $paymentRepository
     * @param \Spryker\Client\Payment\PaymentClientInterface $paymentClient
     * @param \Spryker\Zed\Payment\PaymentConfig $paymentConfig
     * @param \Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreReferenceFacadeInterface $storeReferenceFacade
     * @param \Spryker\Service\Payment\PaymentServiceInterface $paymentService
     * @param array<int, \Spryker\Zed\PaymentExtension\Dependency\Plugin\PaymentAuthorizeRequestExpanderPluginInterface> $paymentAuthorizeRequestExpanderPlugins
     */
    public function __construct(
        QuoteDataMapperInterface $quoteDataMapper,
        PaymentToLocaleFacadeInterface $localeFacade,
        PaymentRepositoryInterface $paymentRepository,
        PaymentClientInterface $paymentClient,
        PaymentConfig $paymentConfig,
        PaymentToStoreReferenceFacadeInterface $storeReferenceFacade,
        PaymentServiceInterface $paymentService,
        array $paymentAuthorizeRequestExpanderPlugins
    ) {
        $this->quoteDataMapper = $quoteDataMapper;
        $this->localeFacade = $localeFacade;
        $this->paymentRepository = $paymentRepository;
        $this->paymentClient = $paymentClient;
        $this->paymentConfig = $paymentConfig;
        $this->storeReferenceFacade = $storeReferenceFacade;
        $this->paymentService = $paymentService;
        $this->paymentAuthorizeRequestExpanderPlugins = $paymentAuthorizeRequestExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function initForeignPaymentForCheckoutProcess(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): void {
        $paymentSelectionKey = $this->paymentService->getPaymentSelectionKey($quoteTransfer->getPaymentOrFail());

        if ($paymentSelectionKey !== PaymentTransfer::FOREIGN_PAYMENTS) {
            return;
        }

        $paymentMethodKey = $this->paymentService->getPaymentMethodKey($quoteTransfer->getPaymentOrFail());
        $paymentMethodTransfer = $this->paymentRepository->findPaymentMethod(
            (new PaymentMethodTransfer())->setPaymentMethodKey($paymentMethodKey),
        );

        if (!$paymentMethodTransfer || empty($paymentMethodTransfer->getPaymentAuthorizationEndpoint())) {
            return;
        }

        $paymentAuthorizeResponseTransfer = $this->requestPaymentAuthorization(
            $paymentMethodTransfer,
            $quoteTransfer,
            $checkoutResponseTransfer->getSaveOrderOrFail(),
        );
        $this->processPaymentAuthorizeResponse(
            $paymentAuthorizeResponseTransfer,
            $checkoutResponseTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAuthorizeResponseTransfer
     */
    protected function requestPaymentAuthorization(
        PaymentMethodTransfer $paymentMethodTransfer,
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): PaymentAuthorizeResponseTransfer {
        $localeTransfer = $this->localeFacade->getCurrentLocale();
        $quoteTransfer->setOrderReference($saveOrderTransfer->getOrderReference());
        $quoteTransfer->getCustomerOrFail()->setLocale($localeTransfer);

        $language = $this->getCurrentLanguage($localeTransfer);
        $postData = [
            'orderData' => $this->quoteDataMapper->mapQuoteDataByAllowedFields(
                $quoteTransfer,
                $this->paymentConfig->getQuoteFieldsForForeignPayment(),
            ),
            'redirectSuccessUrl' => $this->generatePaymentRedirectUrl(
                $language,
                $this->paymentConfig->getSuccessRoute(),
            ),
            'redirectCancelUrl' => $this->generatePaymentRedirectUrl(
                $language,
                $this->paymentConfig->getCancelRoute(),
                ['orderReference' => $quoteTransfer->getOrderReference()],
            ),
            'checkoutSummaryPageUrl' => $this->generatePaymentRedirectUrl(
                $language,
                $this->paymentConfig->getCheckoutSummaryPageRoute(),
            ),
        ];

        $paymentAuthorizeRequestTransfer = (new PaymentAuthorizeRequestTransfer())
            ->setRequestUrl($paymentMethodTransfer->getPaymentAuthorizationEndpoint())
            ->setStoreReference($this->getCurrentStoreReference($quoteTransfer))
            ->setPostData($postData);

        $paymentAuthorizeRequestTransfer = $this->executePaymentAuthorizeRequestExpanderPlugins($paymentAuthorizeRequestTransfer);

        return $this->paymentClient->authorizeForeignPayment($paymentAuthorizeRequestTransfer);
    }

    /**
     * @param string $language
     * @param string $urlPath
     * @param array<mixed> $queryParts
     *
     * @return string
     */
    protected function generatePaymentRedirectUrl(string $language, string $urlPath, array $queryParts = []): string
    {
        $url = sprintf(
            '%s/%s%s',
            $this->paymentConfig->getBaseUrlYves(),
            $language,
            $urlPath,
        );

        return Url::generate($url, $queryParts)->build();
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function getCurrentLanguage(LocaleTransfer $localeTransfer): string
    {
        $splitLocale = explode('_', $localeTransfer->getLocaleNameOrFail());

        return $splitLocale[0];
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentAuthorizeResponseTransfer $paymentAuthorizeResponseTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function processPaymentAuthorizeResponse(
        PaymentAuthorizeResponseTransfer $paymentAuthorizeResponseTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): void {
        if (!$paymentAuthorizeResponseTransfer->getIsSuccessful()) {
            $checkoutErrorTransfer = (new CheckoutErrorTransfer())
                ->setErrorCode(static::ERROR_CODE_PAYMENT_FAILED)
                ->setMessage($paymentAuthorizeResponseTransfer->getMessage());
            $checkoutResponseTransfer->setIsSuccess(false)
                ->addError($checkoutErrorTransfer);

            return;
        }

        $checkoutResponseTransfer
            ->setIsExternalRedirect(true)
            ->setRedirectUrl($paymentAuthorizeResponseTransfer->getRedirectUrl());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getCurrentStoreReference(QuoteTransfer $quoteTransfer): string
    {
        return $this->storeReferenceFacade
            ->getStoreByStoreName($quoteTransfer->getStoreOrFail()->getNameOrFail())
            ->getStoreReferenceOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer
     */
    protected function executePaymentAuthorizeRequestExpanderPlugins(
        PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
    ): PaymentAuthorizeRequestTransfer {
        foreach ($this->paymentAuthorizeRequestExpanderPlugins as $paymentAuthorizeRequestExpanderPlugin) {
            $paymentAuthorizeRequestTransfer = $paymentAuthorizeRequestExpanderPlugin->expand($paymentAuthorizeRequestTransfer);
        }

        return $paymentAuthorizeRequestTransfer;
    }
}
