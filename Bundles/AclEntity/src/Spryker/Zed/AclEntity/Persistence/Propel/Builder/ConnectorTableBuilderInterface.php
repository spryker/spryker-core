<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Builder;

use Propel\Generator\Model\Table;

interface ConnectorTableBuilderInterface
{
    /**
     * @return \Propel\Generator\Model\Table
     */
    public function build(): Table;
}
