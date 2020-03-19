<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Business;

interface PublisherFacadeInterface
{
    /**
     * Specification:
     * - Returns an event collection for all registered publisher plugins to one flattened array and grouped by an event type.
     *
     * @api
     *
     * @return string[]
     */
    public function getPublisherEventCollection(): array;
}
