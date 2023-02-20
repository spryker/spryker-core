<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Business\Model;

interface ConfigDeleterInterface
{
    /**
     * @param string $storeReference
     * @param string $applicationId
     *
     * @return void
     */
    public function delete(string $storeReference, string $applicationId): void;
}
