<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security\AuthenticationListener;

interface AuthenticationListenerInterface
{
    /**
     * @return array<string>
     */
    public function getAuthenticationListenerFactoryTypes(): array;
}
