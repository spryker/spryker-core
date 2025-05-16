<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Validator;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\TaxAppValidationRequestTransfer;
use Generated\Shared\Transfer\TaxAppValidationResponseTransfer;
use Generated\Shared\Transfer\TaxIdValidationHistoryTransfer;
use Spryker\Shared\TaxApp\Dependency\Service\TaxAppToUtilEncodingServiceInterface;
use Spryker\Zed\TaxApp\Business\AccessTokenProvider\AccessTokenProviderInterface;
use Spryker\Zed\TaxApp\Business\Config\ConfigReaderInterface;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToKernelAppFacadeInterface;
use Spryker\Zed\TaxApp\Persistence\TaxAppEntityManagerInterface;
use Spryker\Zed\TaxApp\TaxAppConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaxIdValidator implements TaxIdValidatorInterface
{
    /**
     * @var string
     */
    protected const HEADER_AUTHORIZATION = 'Authorization';

    /**
     * @var string
     */
    protected const CONTENT_KEY_CODE = 'code';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_TAX_APP_IS_DISABLED = 'tax_app.vertex.tax-app-disabled';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_TAX_VALIDATOR_IS_UNAVAILABLE = 'tax_app.vertex.tax-validator-unavailable';

    /**
     * @param \Spryker\Zed\TaxApp\Business\Config\ConfigReaderInterface $configReader
     * @param \Spryker\Zed\TaxApp\Business\AccessTokenProvider\AccessTokenProviderInterface $accessTokenProvider
     * @param \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToKernelAppFacadeInterface $kernelAppFacade
     * @param \Spryker\Zed\TaxApp\Persistence\TaxAppEntityManagerInterface $entityManager
     * @param \Spryker\Shared\TaxApp\Dependency\Service\TaxAppToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        protected ConfigReaderInterface $configReader,
        protected AccessTokenProviderInterface $accessTokenProvider,
        protected TaxAppToKernelAppFacadeInterface $kernelAppFacade,
        protected TaxAppEntityManagerInterface $entityManager,
        protected TaxAppToUtilEncodingServiceInterface $utilEncodingService
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppValidationRequestTransfer $taxAppValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppValidationResponseTransfer
     */
    public function validate(TaxAppValidationRequestTransfer $taxAppValidationRequestTransfer): TaxAppValidationResponseTransfer
    {
        $taxAppValidationRequestTransfer->requireTaxId();
        $taxAppValidationRequestTransfer->requireCountryCode();
        $taxAppConfigTransfer = $this->configReader->findTaxAppConfigForCurrentStore();

        if (
            !$taxAppConfigTransfer ||
            !$taxAppConfigTransfer->getIsActive() ||
            !$taxAppConfigTransfer->getApiUrls() ||
            !$taxAppConfigTransfer->getApiUrls()->getTaxIdValidationUrl()
        ) {
            return $this->createTaxAppValidationResponseTransfer(false, TaxAppConfig::MESSAGE_TAX_APP_IS_DISABLED, static::GLOSSARY_KEY_TAX_APP_IS_DISABLED);
        }

        $acpHttpResponseTransfer = $this->kernelAppFacade->makeRequest(
            (new AcpHttpRequestTransfer())
                ->setUri($taxAppConfigTransfer->getApiUrls()->getTaxIdValidationUrlOrFail())
                ->setMethod(Request::METHOD_POST)
                ->setBody((string)$this->utilEncodingService->encodeJson($taxAppValidationRequestTransfer->toArray(true, true)))
                ->setHeaders([static::HEADER_AUTHORIZATION => $this->accessTokenProvider->getAccessToken()]),
        );
        if ($acpHttpResponseTransfer->getHttpStatusCode() !== Response::HTTP_OK && $acpHttpResponseTransfer->getContent() === null) {
            return $this->createTaxAppValidationResponseTransfer(false, TaxAppConfig::MESSAGE_TAX_VALIDATOR_IS_UNAVAILABLE, static::GLOSSARY_KEY_TAX_VALIDATOR_IS_UNAVAILABLE);
        }

        $content = (array)$this->utilEncodingService->decodeJson((string)$acpHttpResponseTransfer->getContent(), true);

        if (!$content) {
            return $this->createTaxAppValidationResponseTransfer(false, TaxAppConfig::MESSAGE_TAX_VALIDATOR_IS_UNAVAILABLE, static::GLOSSARY_KEY_TAX_VALIDATOR_IS_UNAVAILABLE);
        }
        $content = $acpHttpResponseTransfer->getHttpStatusCode() === Response::HTTP_OK ? $content : current($content);
        $messageKey = $content[static::CONTENT_KEY_CODE] ?? null;
        $taxAppValidationResponseTransfer = (new TaxAppValidationResponseTransfer())
            ->setMessageKey($messageKey)
            ->setIsValid(false)
            ->fromArray($content, true);

        if ($taxAppValidationResponseTransfer->getIsValid() === true) {
            $this->entityManager->saveTaxIdValidationHistory(
                (new TaxIdValidationHistoryTransfer())
                    ->fromArray($taxAppValidationResponseTransfer->toArray(), true)
                    ->setTaxId((string)$taxAppValidationRequestTransfer->getTaxId())
                    ->setCountryCode((string)$taxAppValidationRequestTransfer->getCountryCode())
                    ->setResponseData((string)$taxAppValidationResponseTransfer->getAdditionalInfo()),
            );
        }

        return $taxAppValidationResponseTransfer;
    }

    /**
     * @param bool $isValid
     * @param string $message
     * @param string $messageKey
     *
     * @return \Generated\Shared\Transfer\TaxAppValidationResponseTransfer
     */
    protected function createTaxAppValidationResponseTransfer(
        bool $isValid,
        string $message,
        string $messageKey
    ): TaxAppValidationResponseTransfer {
        return (new TaxAppValidationResponseTransfer())
            ->setIsValid($isValid)
            ->setMessageKey($messageKey)
            ->setMessage($message);
    }
}
