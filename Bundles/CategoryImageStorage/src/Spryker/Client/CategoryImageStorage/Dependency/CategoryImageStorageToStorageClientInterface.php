<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryImageStorage\Dependency;

interface CategoryImageStorageToStorageClientInterface
{
    /**
     * Specification:
     *  - Get data from storage by string key.
     *
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);
}
