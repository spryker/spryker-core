<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartReorderRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartReorderRestApi\CartReorderRestApiConfig;
use Spryker\Glue\CartReorderRestApi\Dependency\Client\CartReorderRestApiToGlossaryStorageClientInterface;
use Symfony\Component\HttpFoundation\Response;

class CartReorderRestErrorMapper implements CartReorderRestErrorMapperInterface
{
    /**
     * @param \Spryker\Glue\CartReorderRestApi\CartReorderRestApiConfig $cartReorderRestApiConfig
     * @param \Spryker\Glue\CartReorderRestApi\Dependency\Client\CartReorderRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        protected CartReorderRestApiConfig $cartReorderRestApiConfig,
        protected CartReorderRestApiToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function mapErrorTransferToRestErrorMessageTransfer(
        ErrorTransfer $errorTransfer,
        RestErrorMessageTransfer $restErrorMessageTransfer,
        string $locale
    ): RestErrorMessageTransfer {
        $errorMessageToRestErrorMapping = $this->cartReorderRestApiConfig->getErrorMessageToRestErrorMapping();
        if (isset($errorMessageToRestErrorMapping[$errorTransfer->getMessageOrFail()])) {
            return $restErrorMessageTransfer
                ->setStatus((int)$errorMessageToRestErrorMapping[$errorTransfer->getMessage()]['status'])
                ->setCode((string)$errorMessageToRestErrorMapping[$errorTransfer->getMessage()]['code'])
                ->setDetail($this->glossaryStorageClient->translate(
                    $errorTransfer->getMessageOrFail(),
                    $locale,
                    $errorTransfer->getParameters(),
                ));
        }

        return $restErrorMessageTransfer
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setCode(CartReorderRestApiConfig::ERROR_CODE_DEFAULT_CART_REORDER_FAILED)
            ->setDetail($this->glossaryStorageClient->translate(
                $errorTransfer->getMessageOrFail(),
                $locale,
                $errorTransfer->getParameters(),
            ));
    }
}
