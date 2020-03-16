<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Form\DataProvider;

use Spryker\Zed\MerchantUserGui\Communication\Form\MerchantUserUpdateForm;

class MerchantUserUpdateFormDataProvider extends MerchantUserCreateFormDataProvider
{
    /**
     * @see \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_BLOCKED
     */
    protected const USER_STATUS_BLOCKED = 'blocked';

    /**
     * @see \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE
     */
    protected const USER_STATUS_ACTIVE = 'active';

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $options[MerchantUserUpdateForm::OPTION_STATUS_CHOICES] = $this->getStatusSelectChoices();

        return $options;
    }

    /**
     * @return string[]
     */
    protected function getStatusSelectChoices(): array
    {
        return [
            static::USER_STATUS_ACTIVE => static::USER_STATUS_ACTIVE,
            static::USER_STATUS_BLOCKED => static::USER_STATUS_BLOCKED,
        ];
    }
}
