<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlossaryStorage\Dependency\Client;

interface GlossaryStorageToStorageInterface
{

    /**
     * @param string $key
     * @param string $prefix
     *
     * @return array
     */
    public function get($key, $prefix = '');

}
