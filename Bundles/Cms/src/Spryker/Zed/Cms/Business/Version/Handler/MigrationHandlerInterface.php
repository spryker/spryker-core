<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version\Handler;

interface MigrationHandlerInterface
{

    /**
     * @param array $originData
     * @param array $targetData
     *
     * @return void
     */
    public function handle(array $originData, array $targetData);
}
