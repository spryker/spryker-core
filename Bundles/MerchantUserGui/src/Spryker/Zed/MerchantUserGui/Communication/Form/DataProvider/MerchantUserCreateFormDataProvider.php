<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
    private $merchantUserFacade;

    /**
     * @param \Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(MerchantUserGuiToMerchantUserFacadeInterface $merchantUserFacade)
    {
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @param int $merchantId
     * @param int|null $merchantUserId
     *
     * @return array
     */
    public function getData(int $merchantId, ?int $merchantUserId = null): array
    {
        if ($merchantUserId === null) {
             return [MerchantUserCreateForm::FIELD_MERCHANT_ID => $merchantId];
        }

        $merchantUserCriteria = $this->createMerchantUserCriteria($merchantUserId);

        $merchantUserTransfer = $this->merchantUserFacade->find($merchantUserCriteria);

        $merchantUserTransfer->requireUser();

        $formData = $this->unsetPasswordField($merchantUserTransfer->getUser()->toArray());

        $formData[MerchantUserCreateForm::FIELD_MERCHANT_ID] = $merchantId;
        $formData[MerchantUserCreateForm::FIELD_MERCHANT_USER_ID] = $merchantUserId;

        return $formData;
    }

    /**
     * @param int $merchantUserId
     *
     * @return \Generated\Shared\Transfer\MerchantUserCriteriaTransfer
     */
    protected function createMerchantUserCriteria(int $merchantUserId): MerchantUserCriteriaTransfer
    {
        return (new MerchantUserCriteriaTransfer())->setIdMerchantUser($merchantUserId)->setWithUser(true);
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
