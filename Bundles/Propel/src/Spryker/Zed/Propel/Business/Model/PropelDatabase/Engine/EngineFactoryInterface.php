<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine;

interface EngineFactoryInterface
{
    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\EngineInterface
     */
    public function createMySqlEngine();

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\EngineInterface
     */
    public function createPostgreSqlEngine();
}
