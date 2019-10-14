<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Business;

use Spryker\Zed\CmsSlotBlock\Business\Writer\CmsSlotBlockRelationsWriter;
use Spryker\Zed\CmsSlotBlock\Business\Writer\CmsSlotBlockRelationsWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockRepositoryInterface getRepository()
 */
class CmsSlotBlockBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CmsSlotBlock\Business\Writer\CmsSlotBlockRelationsWriterInterface
     */
    public function createCmsSlotBlockRelationsWriter(): CmsSlotBlockRelationsWriterInterface
    {
        return new CmsSlotBlockRelationsWriter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }
}
