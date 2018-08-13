<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SwaggerGenerator\Business\Generator;

interface SwaggerGeneratorInterface
{
    /**
     * @return void
     */
    public function generate(): void;
}
