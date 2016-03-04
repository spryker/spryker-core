<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business\Subscription;

class SubscriberKeyGenerator implements SubscriberKeyGeneratorInterface
{

    /**
     * @return string
     */
    public function generateKey()
    {
        return uniqid();
    }

}
