<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business;

interface CartsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Retrieves the list of quotes that do not have the uuid set.
     * - Saves them one by one to trigger uuid generation.
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @return void
     */
    public function updateQuoteUuid(): void;
}
