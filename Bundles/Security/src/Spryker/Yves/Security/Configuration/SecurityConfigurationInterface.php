<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security\Configuration;

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
    public function getAuthenticationSuccessHandler(): array;

    /**
     * @return callable[]
     */
    public function getAuthenticationFailureHandler(): array;

    /**
     * @return callable[]
     */
    public function getAccessDeniedHandler(): array;

    /**
     * @return array
     */
    public function getEventSubscriber(): array;
}
