<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Plugin\GlueApplication;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserValidatorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Response;

class AgentRestUserValidatorPlugin extends AbstractPlugin implements RestUserValidatorPluginInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(RestRequestInterface $restRequest): ?RestErrorMessageTransfer
    {
        // TODO: move logic to processors
        if (
            (!$restRequest->getRestUser() || !$restRequest->getRestUser()->getIdAgent())
            && in_array($restRequest->getResource()->getType(), [AgentAuthRestApiConfig::RESOURCE_AGENT_CUSTOMER_IMPERSONATION_ACCESS_TOKENS])
        ) {
            return (new RestErrorMessageTransfer())
                ->setStatus(Response::HTTP_UNAUTHORIZED)
                ->setCode(AgentAuthRestApiConfig::RESPONSE_CODE_AGENT_ONLY)
                ->setDetail(AgentAuthRestApiConfig::RESPONSE_DETAIL_AGENT_ONLY);
        }

        return null;
    }
}
