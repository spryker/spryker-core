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
     *  - Returns all publisher plugins to one flattened array and groups them by event type
     *
     * @api
     *
     * @return string[]
     */
    public function getPublisherPlugins(): array;
}
