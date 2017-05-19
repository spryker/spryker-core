<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Dependency\Console;

interface DataImportToConsoleInterface
{

    /**
     * @param string $message
     *
     * @return void
     */
    public function notice($message);

}
