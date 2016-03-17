<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business\Subscription;

use Spryker\Zed\Library\Generator\StringGenerator;

class SubscriberKeyGenerator implements SubscriberKeyGeneratorInterface
{

    /**
     * @return string
     */
    public function generateKey()
    {
        $generator = new StringGenerator();

        return $generator->generateRandomString();
    }

}
