<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Customer\Session;

interface AnonymousIdProviderInterface
{
    /**
     * @return string
     */
    public function generateUniqueId(): string;
}
