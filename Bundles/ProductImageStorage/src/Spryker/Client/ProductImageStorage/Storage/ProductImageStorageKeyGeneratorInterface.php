<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageStorage\Storage;

interface ProductImageStorageKeyGeneratorInterface
{
    /**
     * @param string $resourceName
     * @param int $resourceId
     * @param string $locale
     *
     * @return string
     */
    public function generateKey($resourceName, $resourceId, $locale);
}
