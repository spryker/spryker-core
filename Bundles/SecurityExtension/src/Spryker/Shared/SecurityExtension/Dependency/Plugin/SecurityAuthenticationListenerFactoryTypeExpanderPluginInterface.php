<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SecurityExtension\Dependency\Plugin;

/**
 * Use this plugin interface to provide functionality to expand security authentication listener factory types list.
 */
interface SecurityAuthenticationListenerFactoryTypeExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands security authentication listener factory types list.
     *
     * @api
     *
     * @param array<string> $authenticationListenerFactoryTypes
     *
     * @return list<string>
     */
    public function expand(array $authenticationListenerFactoryTypes): array;
}
