<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntityDummyProduct\Business;

use Spryker\Zed\AclEntityDummyProduct\Business\Expander\AclEntityMetadataConfigExpander;
use Spryker\Zed\AclEntityDummyProduct\Business\Expander\AclEntityMetadataConfigExpanderInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AclEntityDummyProduct\AclEntityDummyProductConfig getConfig()
 */
class AclEntityDummyProductBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AclEntityDummyProduct\Business\Expander\AclEntityMetadataConfigExpanderInterface
     */
    public function createAclEntityMetadataConfigExpander(): AclEntityMetadataConfigExpanderInterface
    {
        return new AclEntityMetadataConfigExpander();
    }
}
