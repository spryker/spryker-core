<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\MerchantUserGui\Communication\Form\MerchantUserUpdateForm;
use Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToMerchantUserFacadeInterface;

class MerchantUserUpdateFormDataProvider
{
    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_BLOCKED
     */
    protected const USER_STATUS_BLOCKED = 'blocked';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE
     */
    protected const USER_STATUS_ACTIVE = 'active';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_DELETED
     */
    protected const USER_STATUS_DELETED = 'deleted';

    /**
     * @var \Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @param \Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(MerchantUserGuiToMerchantUserFacadeInterface $merchantUserFacade)
    {
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @param int $idMerchantUser
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    public function getData(int $idMerchantUser): ?MerchantUserTransfer
    {
        return $this->merchantUserFacade->findMerchantUser(
            (new MerchantUserCriteriaTransfer())
                ->setIdMerchantUser($idMerchantUser)
                ->setWithUser(true)
        );
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            MerchantUserUpdateForm::OPTION_STATUS_CHOICES => $this->getStatusSelectChoices(),
        ];
    }

    /**
     * @return string[]
     */
    protected function getStatusSelectChoices(): array
    {
        return [
            static::USER_STATUS_ACTIVE => static::USER_STATUS_ACTIVE,
            static::USER_STATUS_BLOCKED => static::USER_STATUS_BLOCKED,
            static::USER_STATUS_DELETED => static::USER_STATUS_DELETED,
        ];
    }
}
