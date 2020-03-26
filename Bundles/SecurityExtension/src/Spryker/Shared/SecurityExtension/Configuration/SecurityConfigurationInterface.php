<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SecurityExtension\Configuration;

interface SecurityConfigurationInterface
{
    /**
     * @return array
     */
    public function getFirewalls(): array;

    /**
     * @return array
     */
    public function getAccessRules(): array;

    /**
     * @return array
     */
    public function getRoleHierarchies(): array;

    /**
     * @return callable[]
     */
    public function getAuthenticationSuccessHandlers(): array;

    /**
     * @return callable[]
     */
    public function getAuthenticationFailureHandlers(): array;

    /**
     * @return callable[]
     */
    public function getLogoutHandlers(): array;

    /**
     * @return callable[]
     */
    public function getAccessDeniedHandlers(): array;

    /**
     * @return array
     */
    public function getEventSubscribers(): array;
}
