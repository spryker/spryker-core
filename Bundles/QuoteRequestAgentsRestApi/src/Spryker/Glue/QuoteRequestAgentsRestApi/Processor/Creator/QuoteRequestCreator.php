<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestAgentsRestApi\Processor\Creator;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToCompanyUserStorageClientInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToQuoteRequestAgentClientInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\RestResource\QuoteRequestAgentsRestApiToQuoteRequestsRestApiResourceInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface;

class QuoteRequestCreator implements QuoteRequestCreatorInterface
{
    /**
     * @var string
     */
    protected const MAPPING_TYPE_UUID = 'uuid';

    /**
     * @var \Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToQuoteRequestAgentClientInterface
     */
    protected $quoteRequestAgentClient;

    /**
     * @var \Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToCompanyUserStorageClientInterface
     */
    protected $companyUserStorageClient;

    /**
     * @var \Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\RestResource\QuoteRequestAgentsRestApiToQuoteRequestsRestApiResourceInterface
     */
    protected $quoteRequestsRestApiResource;

    /**
     * @var \Spryker\Glue\QuoteRequestAgentsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface
     */
    protected $quoteRequestRestResponseBuilder;

    /**
     * @param \Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToQuoteRequestAgentClientInterface $quoteRequestAgentClient
     * @param \Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToCompanyUserStorageClientInterface $companyUserStorageClient
     * @param \Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\RestResource\QuoteRequestAgentsRestApiToQuoteRequestsRestApiResourceInterface $quoteRequestsRestApiResource
     * @param \Spryker\Glue\QuoteRequestAgentsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder
     */
    public function __construct(
        QuoteRequestAgentsRestApiToQuoteRequestAgentClientInterface $quoteRequestAgentClient,
        QuoteRequestAgentsRestApiToCompanyUserStorageClientInterface $companyUserStorageClient,
        QuoteRequestAgentsRestApiToQuoteRequestsRestApiResourceInterface $quoteRequestsRestApiResource,
        QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder
    ) {
        $this->quoteRequestAgentClient = $quoteRequestAgentClient;
        $this->companyUserStorageClient = $companyUserStorageClient;
        $this->quoteRequestsRestApiResource = $quoteRequestsRestApiResource;
        $this->quoteRequestRestResponseBuilder = $quoteRequestRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createQuoteRequest(RestRequestInterface $restRequest): RestResponseInterface
    {
        /** @var \Generated\Shared\Transfer\RestAgentQuoteRequestsRequestAttributesTransfer $quoteRequestAgentsRequestAttributesTransfer */
        $quoteRequestAgentsRequestAttributesTransfer = $restRequest->getResource()->getAttributes();
        $companyUserStorageTransfer = $this->companyUserStorageClient->findCompanyUserByMapping(
            static::MAPPING_TYPE_UUID,
            $quoteRequestAgentsRequestAttributesTransfer->getCompanyUserUuidOrFail(),
        );

        if (!$companyUserStorageTransfer) {
            return $this->quoteRequestRestResponseBuilder
                ->createCompanyUserNotFoundErrorResponse();
        }

        $companyUserTransfer = (new CompanyUserTransfer())
            ->fromArray($companyUserStorageTransfer->toArray(), true);

        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setCompanyUser($companyUserTransfer);

        $quoteRequestResponseTransfer = $this->quoteRequestAgentClient->createQuoteRequest($quoteRequestTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $this->quoteRequestRestResponseBuilder->createFailedErrorResponse($quoteRequestResponseTransfer->getMessages());
        }

        return $this->quoteRequestsRestApiResource
            ->createQuoteRequestRestResponse(
                $quoteRequestResponseTransfer,
                $restRequest->getMetadata()->getLocale(),
            );
    }
}
