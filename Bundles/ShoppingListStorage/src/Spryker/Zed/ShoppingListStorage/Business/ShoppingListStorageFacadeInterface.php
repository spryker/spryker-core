<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Business;

interface ShoppingListStorageFacadeInterface
{
    /**
     * Specification:
     * - Publishes Shopping List changes to storage.
     *
     * @api
     *
     * @param array<string> $customerReferences
     *
     * @return void
     */
    public function publish(array $customerReferences): void;
}
