<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Authentication\Business\Executor;

use Generated\Shared\Transfer\GlueAuthenticationRequestTransfer;
use Generated\Shared\Transfer\GlueAuthenticationResponseTransfer;
use Spryker\Shared\AuthenticationExtension\Dependency\Plugin\AuthenticationServerPluginInterface;
use Spryker\Zed\Authentication\Business\Exception\MissingServerPluginException;

class AuthenticationServerExecutor implements AuthenticationServerExecutorInterface
{
    /**
     * @var array<\Spryker\Shared\AuthenticationExtension\Dependency\Plugin\AuthenticationServerPluginInterface>
     */
    protected $authenticationServerPlugins = [];

    /**
     * @param array<\Spryker\Shared\AuthenticationExtension\Dependency\Plugin\AuthenticationServerPluginInterface> $authenticationServerPlugins
     */
    public function __construct(array $authenticationServerPlugins)
    {
        $this->authenticationServerPlugins = $authenticationServerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer
     *
     * @throws \Spryker\Zed\Authentication\Business\Exception\MissingServerPluginException
     *
     * @return \Generated\Shared\Transfer\GlueAuthenticationResponseTransfer
     */
    public function execute(GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer): GlueAuthenticationResponseTransfer
    {
        foreach ($this->authenticationServerPlugins as $authenticationServerPlugin) {
            if ($authenticationServerPlugin->isApplicable($glueAuthenticationRequestTransfer)) {
                return $authenticationServerPlugin->authenticate($glueAuthenticationRequestTransfer);
            }
        }

        throw new MissingServerPluginException(
            sprintf(
                'Missing instance of `%s`! Authentication server needs to be configured.',
                AuthenticationServerPluginInterface::class,
            ),
        );
    }
}
