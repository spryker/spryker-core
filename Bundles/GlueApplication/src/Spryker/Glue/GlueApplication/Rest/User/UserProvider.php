<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\User;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserFinderPluginInterface[]
     */
    protected $restUserFinderPlugins;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserFinderPluginInterface[] $restUserFinderPlugins
     */
    public function __construct(array $restUserFinderPlugins)
    {
        $this->restUserFinderPlugins = $restUserFinderPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    public function setUserToRestRequest(RestRequestInterface $restRequest): RestRequestInterface
    {
        if ($restRequest->getUser() || $restRequest->getRestUser()) {
            return $restRequest;
        }

        foreach ($this->restUserFinderPlugins as $restUserFinderPlugin) {
            $restUserTransfer = $restUserFinderPlugin->findUser($restRequest);
            if ($restUserTransfer) {
                $restRequest->setUser(
                    (string)$restUserTransfer->getSurrogateIdentifier(),
                    (string)$restUserTransfer->getNaturalIdentifier(),
                    $restUserTransfer->getScopes()
                );

                $restRequest->setRestUser($restUserTransfer);

                return $restRequest;
            }
        }

        return $restRequest;
    }
}
