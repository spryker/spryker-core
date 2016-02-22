<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business\Model;

interface PropelMigrationCleanerInterface
{

    /**
     * @return bool
     */
    public function clean();

}
