<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Processor\Reader;

use Generated\Shared\Transfer\CustomerQueryTransfer;
use Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig;
use Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToAgentClientInterface;
use Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder\AgentAccessTokenRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomerReader implements CustomerReaderInterface
{
    protected const REQUEST_PARAMETER_QUERY = 'q';

    /**
     * @var \Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToAgentClientInterface
     */
    protected $agentClient;

    /**
     * @var \Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder\AgentAccessTokenRestResponseBuilderInterface
     */
    protected $agentAccessTokenRestResponseBuilder;

    /**
     * @var \Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig
     */
    protected $agentAuthRestApiConfig;

    /**
     * @param \Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToAgentClientInterface $agentClient
     * @param \Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder\AgentAccessTokenRestResponseBuilderInterface $agentAccessTokenRestResponseBuilder
     * @param \Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig $agentAuthRestApiConfig
     */
    public function __construct(
        AgentAuthRestApiToAgentClientInterface $agentClient,
        AgentAccessTokenRestResponseBuilderInterface $agentAccessTokenRestResponseBuilder,
        AgentAuthRestApiConfig $agentAuthRestApiConfig
    ) {
        $this->agentClient = $agentClient;
        $this->agentAccessTokenRestResponseBuilder = $agentAccessTokenRestResponseBuilder;
        $this->agentAuthRestApiConfig = $agentAuthRestApiConfig;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCustomers(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$restRequest->getRestUser() || !$restRequest->getRestUser()->getIdAgent()) {
            return $this->agentAccessTokenRestResponseBuilder->createActionAvailableForAgentsOnlyErrorResponse();
        }

        $customerQueryTransfer = $this->createCustomerQueryTransfer($restRequest);

        $customerAutocompleteResponseTransfer = $this->agentClient->findCustomersByQuery($customerQueryTransfer);

        return $this->agentAccessTokenRestResponseBuilder
            ->createAgentCustomerSearchRestResponse($customerAutocompleteResponseTransfer, $restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerQueryTransfer
     */
    protected function createCustomerQueryTransfer(RestRequestInterface $restRequest): CustomerQueryTransfer
    {
        $offset = 0;
        $limit = $this->agentAuthRestApiConfig->getDefaultCustomerSearchPaginationLimit();
        if ($restRequest->getPage()) {
            $offset = $restRequest->getPage()->getOffset();
            $limit = $restRequest->getPage()->getLimit();
        }

        return (new CustomerQueryTransfer())
            ->setQuery($restRequest->getHttpRequest()->get(static::REQUEST_PARAMETER_QUERY, ''))
            ->setOffset($offset)
            ->setLimit($limit);
    }
}
