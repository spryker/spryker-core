<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelOrm\Communication\Generator;

interface ConfigurablePropelCommandInterface
{
    /**
     * @param array $propelConfig
     *
     * @return $this
     */
    public function setPropelConfig(array $propelConfig);
}
