<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\ConfigReader;

use Spryker\Zed\Propel\PropelConfig;

class PropelConfigReader implements PropelConfigReaderInterface
{
    /**
     * @var \Spryker\Zed\Propel\PropelConfig
     */
    protected $propelConfig;

    /**
     * @param \Spryker\Zed\Propel\PropelConfig $propelConfig
     */
    public function __construct(PropelConfig $propelConfig)
    {
        $this->propelConfig = $propelConfig;
    }

    /**
     * @return string
     */
    public function getSchemaDirectory(): string
    {
        return $this->propelConfig->getSchemaDirectory();
    }
}
