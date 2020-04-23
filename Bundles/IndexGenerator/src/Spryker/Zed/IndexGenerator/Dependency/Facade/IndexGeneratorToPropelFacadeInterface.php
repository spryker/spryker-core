<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IndexGenerator\Dependency\Facade;

interface IndexGeneratorToPropelFacadeInterface
{
    /**
     * @return string
     */
    public function getSchemaDirectory(): string;
}
