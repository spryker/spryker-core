<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Business;

interface MerchantProfileStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all merchants with status `active`.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param int[] $merchantProfileIds
     *
     * @return void
     */
    public function publish(array $merchantProfileIds): void;

    /**
     * Specification:
     * - Finds and deletes merchants.
     * - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param int[] $merchantProfileIds
     *
     * @return void
     */
    public function unpublish(array $merchantProfileIds): void;
}
