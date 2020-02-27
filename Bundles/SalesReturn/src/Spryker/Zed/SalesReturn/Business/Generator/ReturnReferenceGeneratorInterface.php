<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Generator;

interface ReturnReferenceGeneratorInterface
{
    /**
     * @param string $orderReference
     *
     * @return string
     */
    public function generateReturnReference(string $orderReference): string;
}
