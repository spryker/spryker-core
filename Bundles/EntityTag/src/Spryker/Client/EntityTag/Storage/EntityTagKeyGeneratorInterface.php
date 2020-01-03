<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\EntityTag\Storage;

interface EntityTagKeyGeneratorInterface
{
    /**
     * @param string $resourceName
     * @param string $resourceId
     *
     * @return string
     */
    public function generate(string $resourceName, string $resourceId): string;
}
