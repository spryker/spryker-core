<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Formatter;

use Generated\Shared\Transfer\ResourceContextTransfer;

interface RestApiSchemaParametersFormatterInterface
{
    /**
     * @param array<mixed> $operation
     * @param \Generated\Shared\Transfer\ResourceContextTransfer $resourceContext
     *
     * @return array<mixed>
     */
    public function setOperationParameters(array $operation, ResourceContextTransfer $resourceContext): array;

    /**
     * @param array<mixed> $formattedData
     *
     * @return array<mixed>
     */
    public function setComponentParameters(array $formattedData): array;
}
