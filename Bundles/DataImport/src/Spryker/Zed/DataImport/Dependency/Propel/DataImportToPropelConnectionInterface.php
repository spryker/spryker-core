<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Dependency\Propel;

interface DataImportToPropelConnectionInterface
{
    /**
     * @return bool
     */
    public function inTransaction();

    /**
     * @return void
     */
    public function beginTransaction();

    /**
     * @return void
     */
    public function endTransaction();

    /**
     * @return bool
     */
    public function rollBack();
}
