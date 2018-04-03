<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\Dump;

interface ImporterDumperInterface
{
    /**
     * @return array
     */
    public function dump(): array;
}
