<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\EntityTag\Storage;

interface EntityTagReaderInterface
{
    /**
     * @param string $resourceName
     * @param string $resourceId
     *
     * @return string|null
     */
    public function read(string $resourceName, string $resourceId): ?string;
}
