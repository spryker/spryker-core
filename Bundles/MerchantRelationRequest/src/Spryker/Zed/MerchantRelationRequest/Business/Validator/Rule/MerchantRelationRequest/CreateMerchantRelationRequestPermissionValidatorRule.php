<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToPermissionFacadeInterface;

class CreateMerchantRelationRequestPermissionValidatorRule implements MerchantRelationValidatorRuleInterface
{
    /**
     * @uses \Spryker\Client\MerchantRelationRequest\Plugin\Permission\CreateMerchantRelationRequestPermissionPlugin::KEY
     *
     * @var string
     */
    protected const PERMISSION_KEY_CREATE_MERCHANT_RELATION_REQUEST = 'CreateMerchantRelationRequestPermissionPlugin';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMPANY_USER_ACCESS_DENIED = 'merchant_relation_request.validation.company_user_access_denied';

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToPermissionFacadeInterface
     */
    protected MerchantRelationRequestToPermissionFacadeInterface $permissionFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface $errorAdder
     * @param \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToPermissionFacadeInterface $permissionFacade
     */
    public function __construct(
        ErrorAdderInterface $errorAdder,
        MerchantRelationRequestToPermissionFacadeInterface $permissionFacade
    ) {
        $this->errorAdder = $errorAdder;
        $this->permissionFacade = $permissionFacade;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $merchantRelationRequestTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $merchantRelationRequestTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        foreach ($merchantRelationRequestTransfers as $entityIdentifier => $merchantRelationRequestTransfer) {
            if (!$this->hasPermission($merchantRelationRequestTransfer->getCompanyUserOrFail())) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_COMPANY_USER_ACCESS_DENIED,
                );
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    protected function hasPermission(CompanyUserTransfer $companyUserTransfer): bool
    {
        return $this->permissionFacade->can(
            static::PERMISSION_KEY_CREATE_MERCHANT_RELATION_REQUEST,
            $companyUserTransfer->getIdCompanyUserOrFail(),
        );
    }
}
