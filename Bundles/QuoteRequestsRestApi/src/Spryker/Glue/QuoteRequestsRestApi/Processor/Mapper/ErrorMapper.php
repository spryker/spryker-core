<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ErrorMapper implements ErrorMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function mapQuoteRequestErrorMessageTransferToRestErrorMessageTransfer(
        MessageTransfer $messageTransfer,
        RestErrorMessageTransfer $restErrorMessageTransfer
    ): RestErrorMessageTransfer {
        return $restErrorMessageTransfer
            ->setCode(QuoteRequestsRestApiConfig::RESPONSE_CODE_ITEM_VALIDATION)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail($messageTransfer->getMessage());
    }
}
