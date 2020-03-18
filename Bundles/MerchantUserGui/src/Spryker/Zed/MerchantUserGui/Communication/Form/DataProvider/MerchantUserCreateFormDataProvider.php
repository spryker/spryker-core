<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Spryker\Zed\MerchantUserGui\Communication\Form\MerchantUserCreateForm;
use Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToMerchantUserFacadeInterface;

class MerchantUserCreateFormDataProvider
{
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
     * @param int $idMerchant
     * @param int|null $idMerchantUser
     *
     * @return array
     */
    public function getData(int $idMerchant, ?int $idMerchantUser = null): array
    {
        if ($idMerchantUser === null) {
             return [MerchantUserCreateForm::FIELD_MERCHANT_ID => $idMerchant];
        }

        $merchantUserTransfer = $this->merchantUserFacade->findOne(
            (new MerchantUserCriteriaTransfer())
                ->setIdMerchantUser($idMerchantUser)
                ->setWithUser(true)
        );

        $merchantUserTransfer->requireUser();

        $formData = $this->unsetPasswordField($merchantUserTransfer->getUser()->toArray());

        $formData[MerchantUserCreateForm::FIELD_MERCHANT_ID] = $idMerchant;
        $formData[MerchantUserCreateForm::FIELD_MERCHANT_USER_ID] = $idMerchantUser;

        return $formData;
    }

    /**
     * @param array $formData
     *
     * @return array
     */
    protected function unsetPasswordField(array $formData): array
    {
        if (array_key_exists(MerchantUserCreateForm::FIELD_PASSWORD, $formData)) {
            unset($formData[MerchantUserCreateForm::FIELD_PASSWORD]);
        }

        return $formData;
    }
}
