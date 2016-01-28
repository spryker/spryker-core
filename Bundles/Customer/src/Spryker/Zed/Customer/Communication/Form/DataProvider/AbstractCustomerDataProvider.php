<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
