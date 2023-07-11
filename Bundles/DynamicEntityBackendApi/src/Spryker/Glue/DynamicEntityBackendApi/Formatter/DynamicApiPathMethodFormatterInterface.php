<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Formatter;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;

interface DynamicApiPathMethodFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     * @param array<mixed> $formattedData
     *
     * @return array<mixed>
     */
    public function format(ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer, array $formattedData): array;
}
