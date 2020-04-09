<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Generated\Shared\Transfer\PathMethodDataTransfer;

interface OpenApiTagGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     *
     * @return void
     */
    public function addTag(
        PathMethodDataTransfer $pathMethodDataTransfer
    ): void;

    /**
     * @return array
     */
    public function getTags(): array;
}
