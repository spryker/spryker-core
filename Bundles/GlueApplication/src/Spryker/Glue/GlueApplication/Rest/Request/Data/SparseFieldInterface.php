<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request\Data;

/**
 * @deprecated Will be removed without replacement.
 */
interface SparseFieldInterface
{
    /**
     * @return string
     */
    public function getResource(): string;

    /**
     * @return array
     */
    public function getAttributes(): array;
}
