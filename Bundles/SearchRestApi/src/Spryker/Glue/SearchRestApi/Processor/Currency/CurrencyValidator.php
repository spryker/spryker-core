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

class CurrencyValidator implements CurrencyValidatorInterface
{
    /**
     * @var \Spryker\Shared\Kernel\Store \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

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
            $currencies = $this->store
                ->getCurrencyIsoCodes();
            if (!\in_array($currency, $currencies, true)) {
                return (new RestErrorMessageTransfer())
                    ->setCode(SearchRestApiConfig::RESPONSE_CODE_INVALID_CURRENCY)
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->setDetail(SearchRestApiConfig::RESPONSE_DETAIL_INVALID_CURRENCY);
            }
        }

        return null;
    }
}
