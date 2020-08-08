<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Processor\Reader;

use Generated\Shared\Transfer\CustomerQueryTransfer;
use Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToAgentClientInterface;
use Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder\AgentAccessTokenRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomerReader implements CustomerReaderInterface
{
    /**
     * @var \Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToAgentClientInterface
     */
    protected $agentClient;

    /**
     * @var \Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder\AgentAccessTokenRestResponseBuilderInterface
     */
    protected $agentAccessTokenRestResponseBuilder;

    /**
     * @param \Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToAgentClientInterface $agentClient
     * @param \Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder\AgentAccessTokenRestResponseBuilderInterface $agentAccessTokenRestResponseBuilder
     */
    public function __construct(
        AgentAuthRestApiToAgentClientInterface $agentClient,
        AgentAccessTokenRestResponseBuilderInterface $agentAccessTokenRestResponseBuilder
    ) {
        $this->agentClient = $agentClient;
        $this->agentAccessTokenRestResponseBuilder = $agentAccessTokenRestResponseBuilder;
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

        $customerQueryTransfer = (new CustomerQueryTransfer())
            ->setQuery($restRequest->getHttpRequest()->get('q', ''))
            ->setLimit(10);

        $customerAutocompleteResponseTransfer = $this->agentClient->findCustomersByQuery($customerQueryTransfer);

        return $this->agentAccessTokenRestResponseBuilder
            ->createAgentCustomerSearchRestResponse($customerAutocompleteResponseTransfer);
    }
}
