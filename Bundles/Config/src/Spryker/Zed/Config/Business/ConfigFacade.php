<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Config\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Config\Business\ConfigBusinessFactory getFactory()
 * @method \Spryker\Zed\Config\Persistence\ConfigRepositoryInterface getRepository()
 * @method \Spryker\Zed\Config\Persistence\ConfigEntityManagerInterface getEntityManager()
 */
class ConfigFacade extends AbstractFacade implements ConfigFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getProfileData(): array
    {
        return $this->getFactory()->createConfigProfiler()->getProfileData();
    }
}
