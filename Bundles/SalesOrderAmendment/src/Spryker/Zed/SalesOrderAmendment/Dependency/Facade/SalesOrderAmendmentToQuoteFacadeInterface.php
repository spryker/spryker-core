<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Dependency\Facade;

interface SalesOrderAmendmentToQuoteFacadeInterface
{
    /**
     * @return string
     */
    public function getStorageStrategy(): string;
}
