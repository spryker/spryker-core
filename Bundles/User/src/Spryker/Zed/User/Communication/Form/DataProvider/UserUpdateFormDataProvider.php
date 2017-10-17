<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Form\DataProvider;

use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Zed\User\Communication\Form\UserUpdateForm;

class UserUpdateFormDataProvider extends UserFormDataProvider
{
    /**
     * @return array
     */
    public function getOptions()
    {
        $options = parent::getOptions();

        $options[UserUpdateForm::OPTION_STATUS_CHOICES] = $this->getStatusSelectChoices();

        return $options;
    }

    /**
     * @return array
     */
    protected function getStatusSelectChoices()
    {
        return array_combine(
            SpyUserTableMap::getValueSet(SpyUserTableMap::COL_STATUS),
            SpyUserTableMap::getValueSet(SpyUserTableMap::COL_STATUS)
        );
    }
}
