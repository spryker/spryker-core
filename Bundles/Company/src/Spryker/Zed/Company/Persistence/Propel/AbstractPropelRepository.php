<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Persistence\Propel;

use Spryker\Zed\Company\Persistence\CompanyPersistenceFactory;

abstract class AbstractPropelRepository
{
    /**
     * @return \Spryker\Zed\Company\Persistence\CompanyPersistenceFactory
     */
    protected function getFactory(): CompanyPersistenceFactory
    {
        return new CompanyPersistenceFactory();
    }
}
