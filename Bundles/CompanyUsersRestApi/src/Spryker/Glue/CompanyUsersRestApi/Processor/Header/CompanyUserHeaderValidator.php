<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi\Processor\Header;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CompanyUserHeaderValidator implements CompanyUserHeaderValidatorInterface
{
    /**
     * @var \Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiConfig
     */
    private $config;

    /**
     * @param \Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiConfig $config
     */
    public function __construct(CompanyUsersRestApiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        if (!$this->isCompanyUserResource($restRequest)) {
            return null;
        }

        if ($this->isCompanyUserHeaderExistsAndNotEmpty($restRequest)) {
            return null;
        }

        return (new RestErrorCollectionTransfer())
            ->addRestError(
                (new RestErrorMessageTransfer())
                    ->setDetail(CompanyUsersRestApiConfig::RESPONSE_HEADERS_MISSING_COMPANY_USER)
                    ->setCode(CompanyUsersRestApiConfig::RESPONSE_HEADERS_MISSING_COMPANY_USER_CODE)
                    ->setStatus(Response::HTTP_BAD_REQUEST)
            );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function isCompanyUserResource(RestRequestInterface $restRequest): bool
    {
        return in_array(
            $restRequest->getResource()->getType(),
            $this->config->getCompanyUserResources(),
            true
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function isCompanyUserHeaderExistsAndNotEmpty(RestRequestInterface $restRequest): bool
    {
        $requestHeaders = $restRequest->getHttpRequest()->headers;
        $headerKey = CompanyUsersRestApiConfig::X_COMPANY_USER_ID_HEADER_KEY;

        return $requestHeaders->has($headerKey) && !empty($requestHeaders->get($headerKey));
    }
}
