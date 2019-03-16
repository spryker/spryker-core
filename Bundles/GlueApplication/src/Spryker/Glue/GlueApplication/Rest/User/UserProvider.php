<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\User;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserFinderPluginInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserFinderPluginInterface
     */
    protected $restUserFinderPlugin;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserFinderPluginInterface $userFinderPlugin
     */
    public function __construct(RestUserFinderPluginInterface $userFinderPlugin)
    {
        $this->restUserFinderPlugin = $userFinderPlugin;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    public function setUserToRestRequest(RestRequestInterface $restRequest): RestRequestInterface
    {
        if ($restRequest->getUser()) {
            return $restRequest;
        }

        $user = $this->restUserFinderPlugin->findUser($restRequest);
        if ($user) {
            $restRequest->setUser(
                $user->getSurrogateIdentifier(),
                $user->getNaturalIdentifier(),
                $user->getScopes(),
                $user->getRestUserIdentifierTransfer()
            );
        }

        return $restRequest;
    }
}
