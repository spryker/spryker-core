<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Processor;

use Generated\Shared\Transfer\CustomRoutesContextTransfer;

interface CustomPathMethodFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomRoutesContextTransfer $customRouteTransfer
     * @param array<mixed> $formattedData
     *
     * @return array<mixed>
     */
    public function format(CustomRoutesContextTransfer $customRouteTransfer, array $formattedData): array;
}
