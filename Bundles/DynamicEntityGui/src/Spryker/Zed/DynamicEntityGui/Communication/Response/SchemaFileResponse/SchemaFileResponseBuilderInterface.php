<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Communication\Response\SchemaFileResponse;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface SchemaFileResponseBuilderInterface
{
    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function createResponse(): BinaryFileResponse;
}
