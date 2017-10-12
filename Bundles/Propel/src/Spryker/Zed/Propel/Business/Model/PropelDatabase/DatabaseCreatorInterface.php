<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase;

interface DatabaseCreatorInterface
{
    /**
     * @return void
     */
    public function createIfNotExists();

    /**
     * @return string
     */
    public function getEngine();
}
