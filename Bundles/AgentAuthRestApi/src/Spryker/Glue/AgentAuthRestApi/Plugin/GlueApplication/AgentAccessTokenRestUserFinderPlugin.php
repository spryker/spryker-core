<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Plugin\GlueApplication;

use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserFinderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiFactory getFactory()
 */
class AgentAccessTokenRestUserFinderPlugin extends AbstractPlugin implements RestUserFinderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Finds rest user for the `X-Agent-Authorization` header.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer|null
     */
    public function findUser(RestRequestInterface $restRequest): ?RestUserTransfer
    {
        return $this->getFactory()
            ->createRestUserFinder()
            ->findAgentRestUser($restRequest);
    }
}
