<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IndexGenerator\Business\IndexGenerator;

interface PostgresIndexGeneratorInterface
{
    /**
     * @return void
     */
    public function generateIndexes(): void;
}
