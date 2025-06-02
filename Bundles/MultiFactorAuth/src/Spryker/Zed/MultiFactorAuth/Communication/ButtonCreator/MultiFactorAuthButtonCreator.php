<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\ButtonCreator;

use Generated\Shared\Transfer\ButtonTransfer;
use Generated\Shared\Transfer\CustomerCriteriaTransfer;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToCustomerFacadeInterface;
use Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface;

class MultiFactorAuthButtonCreator implements MultiFactorAuthButtonCreatorInterface
{
    /**
     * @var string
     */
    protected const BUTTON_TITLE = 'MFA';

    /**
     * @var string
     */
    protected const BUTTON_URL = '/multi-factor-auth/customer/remove-mfa';

    /**
     * @var array<string, string>
     */
    protected const BUTTON_DEFAULT_OPTIONS = [
        'class' => 'btn-danger',
        'icon' => 'fa fa-trash',
        'data-qa' => 'remove-mfa-button',
    ];

    /**
     * @param \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface $repository
     * @param \Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToCustomerFacadeInterface $customerFacade
     */
    public function __construct(
        protected MultiFactorAuthRepositoryInterface $repository,
        protected MultiFactorAuthToCustomerFacadeInterface $customerFacade
    ) {
    }

    /**
     * @param int $idCustomer
     * @param array<\Generated\Shared\Transfer\ButtonTransfer> $buttonTransfers
     *
     * @return array<\Generated\Shared\Transfer\ButtonTransfer>
     */
    public function addRemoveMultiFactorAuthButton(int $idCustomer, array $buttonTransfers): array
    {
        $customerResponseTransfer = $this->customerFacade->getCustomerByCriteria((new CustomerCriteriaTransfer())->setIdCustomer($idCustomer));
        $multiFactorAuthTypesCollectionTransfer = $this->repository->getCustomerMultiFactorAuthTypes($customerResponseTransfer->getCustomerTransferOrFail());

        if ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes()->count() === 0) {
            return $buttonTransfers;
        }

        $buttonTransfer = (new ButtonTransfer())
            ->setTitle(static::BUTTON_TITLE)
            ->setUrl(sprintf('%s?id-customer=%d', static::BUTTON_URL, $idCustomer))
            ->setDefaultOptions(static::BUTTON_DEFAULT_OPTIONS);

        $buttonTransfers[] = $buttonTransfer;

        return $buttonTransfers;
    }
}
