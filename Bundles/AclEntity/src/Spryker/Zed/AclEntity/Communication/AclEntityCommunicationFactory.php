<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Communication;

use Spryker\Zed\AclEntity\Communication\Mapper\AclEntityMapper;
use Spryker\Zed\AclEntity\Communication\Mapper\AclEntityMapperInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\AclEntity\Persistence\AclEntityEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AclEntity\AclEntityConfig getConfig()
 * @method \Spryker\Zed\AclEntity\Persistence\AclEntityRepositoryInterface getRepository()
 * @method \Spryker\Zed\AclEntity\Business\AclEntityFacadeInterface getFacade()
 */
class AclEntityCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\AclEntity\Communication\Mapper\AclEntityMapperInterface
     */
    public function createAclEntityMapper(): AclEntityMapperInterface
    {
        return new AclEntityMapper();
    }
}
