<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilUuidGenerator\Dependency\External;

interface UtilUuidGeneratorToUuid5GeneratorInterface
{
    /**
     * @param string $name
     *
     * @return string
     */
    public function uuid5(string $name): string;
}
