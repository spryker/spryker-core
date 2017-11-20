<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Form\DataProvider;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;

class AbstractCustomerDataProvider
{
    /**
     * @return array
     */
    protected function getSalutationChoices()
    {
        $salutationSet = SpyCustomerTableMap::getValueSet(SpyCustomerTableMap::COL_SALUTATION);

        return array_combine($salutationSet, $salutationSet);
    }
}
