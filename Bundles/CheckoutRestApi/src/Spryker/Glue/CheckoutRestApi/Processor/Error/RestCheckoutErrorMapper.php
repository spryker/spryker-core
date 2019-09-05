<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Error;

use Generated\Shared\Transfer\RestCheckoutErrorTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface;

class RestCheckoutErrorMapper implements RestCheckoutErrorMapperInterface
{
    /**
     * @var \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig
     */
    protected $config;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig $config
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        CheckoutRestApiConfig $config,
        CheckoutRestApiToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
        $this->config = $config;
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutErrorTransfer $restCheckoutErrorTransfer
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function mapRestCheckoutErrorTransferToRestErrorTransfer(
        RestCheckoutErrorTransfer $restCheckoutErrorTransfer,
        RestErrorMessageTransfer $restErrorMessageTransfer
    ): RestErrorMessageTransfer {
        return $this->mergeErrorDataWithErrorConfiguration(
            $restCheckoutErrorTransfer,
            $restErrorMessageTransfer,
            $restCheckoutErrorTransfer->toArray()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutErrorTransfer $restCheckoutErrorTransfer
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     * @param string $localeCode
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function mapLocalizedRestCheckoutErrorTransferToRestErrorTransfer(
        RestCheckoutErrorTransfer $restCheckoutErrorTransfer,
        RestErrorMessageTransfer $restErrorMessageTransfer,
        string $localeCode
    ): RestErrorMessageTransfer {
        return $this->mergeErrorDataWithErrorConfiguration(
            $restCheckoutErrorTransfer,
            $restErrorMessageTransfer,
            $this->translateCheckoutErrorMessage($restCheckoutErrorTransfer, $localeCode)->toArray()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutErrorTransfer $restCheckoutErrorTransfer
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     * @param array $errorData
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function mergeErrorDataWithErrorConfiguration(
        RestCheckoutErrorTransfer $restCheckoutErrorTransfer,
        RestErrorMessageTransfer $restErrorMessageTransfer,
        array $errorData
    ): RestErrorMessageTransfer {
        $errorIdentifierMapping = $this->getErrorIdentifierMapping($restCheckoutErrorTransfer);

        if ($errorIdentifierMapping) {
            $errorData = array_merge($errorIdentifierMapping, array_filter($errorData));
        }

        return $restErrorMessageTransfer->fromArray($errorData, true);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutErrorTransfer $restCheckoutErrorTransfer
     *
     * @return array
     */
    protected function getErrorIdentifierMapping(RestCheckoutErrorTransfer $restCheckoutErrorTransfer): array
    {
        return $this->config->getErrorIdentifierToRestErrorMapping()[$restCheckoutErrorTransfer->getErrorIdentifier()] ?? [];
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutErrorTransfer $restCheckoutErrorTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestCheckoutErrorTransfer
     */
    protected function translateCheckoutErrorMessage(
        RestCheckoutErrorTransfer $restCheckoutErrorTransfer,
        string $localeName
    ): RestCheckoutErrorTransfer {
        $restCheckoutErrorDetail = $this->glossaryStorageClient->translate(
            $restCheckoutErrorTransfer->getDetail(),
            $localeName,
            $restCheckoutErrorTransfer->getParameters()
        );

        if (!$restCheckoutErrorDetail) {
            return $restCheckoutErrorTransfer;
        }

        return $restCheckoutErrorTransfer->setDetail($restCheckoutErrorDetail);
    }
}
