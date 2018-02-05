<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence\Propel;

use ArrayObject;
use Propel\Runtime\Collection\ObjectCollection;

interface CompanyUnitAddressHydratorInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $companyUnitAddressCollection
     *
     * @return \ArrayObject
     */
    public function hydrateUnitAddressCollectionEntityCollection(
        ObjectCollection $companyUnitAddressCollection
    ): ArrayObject;
}
