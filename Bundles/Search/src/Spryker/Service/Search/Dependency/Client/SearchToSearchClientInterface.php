<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Search\Dependency\Client;

interface SearchToSearchClientInterface
{
    /**
     * @throws \Exception
     *
     * @return void
     */
    public function checkConnection();
}
