<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\AclEntity;

use Spryker\Service\AclEntity\SegmentConnectorGenerator\SegmentConnectorGenerator;
use Spryker\Service\AclEntity\SegmentConnectorGenerator\SegmentConnectorGeneratorInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

class AclEntityServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\AclEntity\SegmentConnectorGenerator\SegmentConnectorGeneratorInterface
     */
    public function createSegmentConnectorGenerator(): SegmentConnectorGeneratorInterface
    {
        return new SegmentConnectorGenerator();
    }
}
