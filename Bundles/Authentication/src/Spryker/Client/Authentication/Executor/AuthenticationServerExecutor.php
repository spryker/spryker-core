<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Authentication\Executor;

use Generated\Shared\Transfer\GlueAuthenticationRequestTransfer;
use Generated\Shared\Transfer\GlueAuthenticationResponseTransfer;
use Spryker\Client\Authentication\Exception\MissingServerPluginException;
use Spryker\Shared\AuthenticationExtension\Dependency\Plugin\AuthenticationServerPluginInterface;

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
     * @throws \Spryker\Client\Authentication\Exception\MissingServerPluginException
     *
     * @return \Generated\Shared\Transfer\GlueAuthenticationResponseTransfer
     */
    public function execute(GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer): GlueAuthenticationResponseTransfer
    {
        foreach ($this->authenticationServerPlugins as $authenticationServerPlugin) {
            $glueAuthenticationResponseTransfer = $authenticationServerPlugin->authenticate($glueAuthenticationRequestTransfer);

            if ($glueAuthenticationResponseTransfer->getOauthResponse() !== null) {
                return $glueAuthenticationResponseTransfer;
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
