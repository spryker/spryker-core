<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi\Processor\Currency;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SearchRestApi\SearchRestApiConfig;
use Spryker\Shared\Kernel\Store;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateCurrency implements ValidateCurrencyInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(Request $request, RestRequestInterface $restRequest): ?RestErrorMessageTransfer
    {

        $currency = $restRequest->getHttpRequest()->query->get(SearchRestApiConfig::CURRENCY_STRING_PARAMETER);
        if ($currency) {
            $currencies = Store::getInstance()
                ->getCurrencyIsoCodes();
            if (!in_array($currency, $currencies)) {
                return $this->createErrorMessageTransfer(
                    SearchRestApiConfig::RESPONSE_DETAIL_INVALID_REQUEST_CURRENCY,
                    Response::HTTP_BAD_REQUEST,
                    SearchRestApiConfig::RESPONSE_CODE_INVALID_REQUEST_CURRENCY
                );
            }
        }

        return null;
    }

    /**
     * @param string $detail
     * @param int $status
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorMessageTransfer(
        string $detail,
        int $status,
        string $code
    ): RestErrorMessageTransfer {

        return (new RestErrorMessageTransfer())
            ->setDetail($detail)
            ->setStatus($status)
            ->setCode($code);
    }
}
