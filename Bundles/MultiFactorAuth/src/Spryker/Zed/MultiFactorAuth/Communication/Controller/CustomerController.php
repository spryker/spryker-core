<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Controller;

use Generated\Shared\Transfer\CustomerCriteriaTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface getRepository()
 */
class CustomerController extends AbstractUserMultiFactorAuthController
{
    /**
     * @var string
     */
    protected const CSRF_TOKEN_IS_INVALID = 'CSRF token is invalid.';

    /**
     * @var string
     */
    protected const PARAM_ID_CUSTOMER = 'id-customer';

    /**
     * @var string
     */
    protected const URL_REDIRECT_CUSTOMER_LIST = '/customer';

    /**
     * @var string
     */
    protected const URL_CUSTOMER_REMOVE_MFA_LIST = '/multi-factor-auth/customer/remove-mfa-list';

    /**
     * @var string
     */
    protected const URL_CUSTOMER_REMOVE_MFA = '/multi-factor-auth/customer/remove-mfa';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_COLLECTION = 'multiFactorAuthTypesCollection';

    /**
     * @var string
     */
    protected const CSRF_TOKEN_DEACTIVATE = 'csrfTokenDeactivate';

    /**
     * @var string
     */
    protected const CSRF_TOKEN_ID_DEACTIVATE = 'deactivate';

    /**
     * @var string
     */
    protected const PARAM_REQUEST_TOKEN = '_csrf_token';

    /**
     * @var string
     */
    protected const DEACTIVATION_SUCCESS_MESSAGE = 'The multi-factor authentication has been deactivated.';

    /**
     * @var string
     */
    protected const CUSTOMER_HAS_NO_ACTIVE_MULTI_FACTOR_AUTHENTICATION_METHODS = 'Customer has no active multi-factor authentication methods.';

    /**
     * @var string
     */
    protected const KEY_ID_CUSTOMER = 'idCustomer';

    /**
     * @var string
     */
    protected const KEY_TYPE = 'type';

    /**
     * @var string
     */
    protected const ERROR_MFA_TYPE_NOT_FOUND = 'MFA type "%s" not found for this customer.';

    /**
     * @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface
     */
    protected CsrfTokenManagerInterface $csrfTokenManager;

    /**
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->csrfTokenManager = $this->getFactory()->getCsrfTokenManager();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|array<string, mixed>
     */
    public function removeMfaAction(Request $request): Response|array
    {
        $idCustomer = $this->castId($this->getParameterFromRequest($request, static::PARAM_ID_CUSTOMER));
        $type = $this->getParameterFromRequest($request, static::TYPE);

        $multiFactorAuthTypesCollectionTransfer = $this->getCustomerMultiFactorAuthCollection($idCustomer);
        $multiFactorAuthTypesCount = $multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes()->count();

        if ($multiFactorAuthTypesCount === 0) {
            $this->addErrorMessage(static::CUSTOMER_HAS_NO_ACTIVE_MULTI_FACTOR_AUTHENTICATION_METHODS);

            return $this->redirectResponse(static::URL_REDIRECT_CUSTOMER_LIST);
        }

        if ($type !== null) {
            if (!$this->hasCustomerMultiFactorAuthType($multiFactorAuthTypesCollectionTransfer, $type)) {
                $this->addErrorMessage(sprintf(static::ERROR_MFA_TYPE_NOT_FOUND, $type));

                return $this->redirectResponse(static::URL_REDIRECT_CUSTOMER_LIST);
            }

            return $this->createConfirmationResponse($idCustomer, $type);
        }

        if ($multiFactorAuthTypesCount > 1) {
            return $this->redirectResponse(
                sprintf(
                    '%s?%s=%d',
                    static::URL_CUSTOMER_REMOVE_MFA_LIST,
                    static::PARAM_ID_CUSTOMER,
                    $idCustomer,
                ),
            );
        }

        $type = $multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes()[0]->getType();

        return $this->createConfirmationResponse($idCustomer, $type);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|array<string, mixed>
     */
    public function removeMfaListAction(Request $request): Response|array
    {
        $idCustomer = $this->castId($this->getParameterFromRequest($request, static::PARAM_ID_CUSTOMER));
        $multiFactorAuthTypesCollectionTransfer = $this->getCustomerMultiFactorAuthCollection($idCustomer);

        if ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes()->count() <= 1) {
            return $this->redirectResponse(
                sprintf(
                    '%s?%s=%d',
                    static::URL_CUSTOMER_REMOVE_MFA,
                    static::PARAM_ID_CUSTOMER,
                    $idCustomer,
                ),
            );
        }

        return $this->viewResponse([
            static::MULTI_FACTOR_AUTH_COLLECTION => $multiFactorAuthTypesCollectionTransfer,
            static::CSRF_TOKEN_DEACTIVATE => $this->csrfTokenManager->getToken(static::CSRF_TOKEN_ID_DEACTIVATE)->getValue(),
            static::KEY_ID_CUSTOMER => $idCustomer,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmRemoveMfaAction(Request $request): Response
    {
        $idCustomer = $this->castId($this->getParameterFromRequest($request, static::PARAM_ID_CUSTOMER));
        $type = $this->getParameterFromRequest($request, static::TYPE);

        if (!$this->validateCsrfToken($request)) {
            return $this->redirectResponse(static::URL_REDIRECT_CUSTOMER_LIST);
        }

        $this->deactivateCustomerMultiFactorAuth($idCustomer, $type);

        return $this->redirectResponse(static::URL_REDIRECT_CUSTOMER_LIST);
    }

    /**
     * @param int $idCustomer
     * @param string $type
     *
     * @return void
     */
    protected function deactivateCustomerMultiFactorAuth(int $idCustomer, string $type): void
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);

        $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
            ->setCustomer($customerTransfer)
            ->setStatus(MultiFactorAuthConstants::STATUS_INACTIVE)
            ->setType($type);

        $this->getFacade()->deactivateCustomerMultiFactorAuth($multiFactorAuthTransfer);

        $this->addSuccessMessage(static::DEACTIVATION_SUCCESS_MESSAGE);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function validateCsrfToken(Request $request): bool
    {
        $token = $this->getParameterFromRequest($request, static::PARAM_REQUEST_TOKEN);
        if (!$this->isCsrfTokenValid($token, static::CSRF_TOKEN_ID_DEACTIVATE)) {
            $this->addErrorMessage(static::CSRF_TOKEN_IS_INVALID);

            return false;
        }

        return true;
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    protected function getCustomerMultiFactorAuthCollection(int $idCustomer): MultiFactorAuthTypesCollectionTransfer
    {
        $customerResponseTransfer = $this->getFactory()->getCustomerFacade()->getCustomerByCriteria(
            (new CustomerCriteriaTransfer())->setIdCustomer($idCustomer),
        );

        return $this->getRepository()->getCustomerMultiFactorAuthTypes($customerResponseTransfer->getCustomerTransferOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer
     * @param string $type
     *
     * @return bool
     */
    protected function hasCustomerMultiFactorAuthType(MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer, string $type): bool
    {
        foreach ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes() as $multiFactorAuthType) {
            if ($multiFactorAuthType->getType() === $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $idCustomer
     * @param string $type
     *
     * @return array<string, mixed>
     */
    protected function createConfirmationResponse(int $idCustomer, string $type): array
    {
        return $this->viewResponse([
            static::CSRF_TOKEN_DEACTIVATE => $this->csrfTokenManager->getToken(static::CSRF_TOKEN_ID_DEACTIVATE)->getValue(),
            static::KEY_ID_CUSTOMER => $idCustomer,
            static::KEY_TYPE => $type,
        ]);
    }

    /**
     * @param string|null $token
     * @param string $tokenId
     *
     * @return bool
     */
    protected function isCsrfTokenValid(?string $token, string $tokenId): bool
    {
        if (!$token) {
            return false;
        }

        $csrfToken = new CsrfToken($tokenId, $token);

        return $this->csrfTokenManager->isTokenValid($csrfToken);
    }
}
