<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ZedRequest\Client\Fixture;

use Spryker\Shared\ZedRequest\Client\AbstractHttpClient as SharedAbstractHttpClient;

class AbstractHttpClient extends SharedAbstractHttpClient
{

    /**
     * @return array
     */
    public function getHeaders()
    {
        return [];
    }

}
