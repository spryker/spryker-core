<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\MultiFactorAuth\MultiFactorAuthConfig getSharedConfig()
 */
class MultiFactorAuthConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const USER_PASSWORD_RESET_ROUTE = 'user:edit:password-reset';

    /**
     * @var string
     */
    protected const USER_DELETE_ROUTE = 'user:edit:delete';

    /**
     * @var string
     */
    protected const USER_UPDATE_ROUTE = 'user:edit:update';

    /**
     * @var string
     */
    protected const USER_CREATE_ROUTE = 'user:edit:create';

    /**
     * @var string
     */
    protected const API_KEY_EDIT_ROUTE = 'api-key-gui:edit';

    /**
     * @var string
     */
    protected const API_KEY_DELETE_ROUTE = 'api-key-gui:delete';

    /**
     * @var string
     */
    protected const API_KEY_CREATE_ROUTE = 'api-key-gui:create';

    /**
     * @var string
     */
    protected const API_KEY_UPDATE_ROUTE = 'api-key-gui:edit:update';

    /**
     * @uses {@link \Spryker\Zed\UserMerchantPortalGui\Communication\Controller\MyAccountController::ROUTE_MERCHANT_MY_ACCOUNT}
     *
     * @var string
     */
    protected const MERCHANT_POSTAL_CHANGE_PASSWORD_ROUTE = 'user-merchant-portal-gui:change-password';

    /**
     * @uses {@link \Spryker\Zed\User\Communication\Form\ResetPasswordForm::getBlockPrefix()}
     *
     * @var string
     */
    protected const RESET_PASSWORD_FORM_NAME = 'reset_password';

    /**
     * @uses {@link \Spryker\Zed\User\Communication\Form\UserDeleteConfirmForm::getBlockPrefix()}
     *
     * @var string
     */
    protected const DELETE_CONFIRM_FORM_NAME = 'delete_confirm_form';

    /**
     * @uses {@link \Spryker\Zed\User\Communication\Form\UserForm::getBlockPrefix()}
     *
     * @var string
     */
    protected const USER_FORM_NAME = 'user';

    /**
     * @uses {@link \Spryker\Zed\ApiKeyGui\Communication\Form\CreateApiKeyForm::getBlockPrefix()}, {@link \Spryker\Zed\ApiKeyGui\Communication\Form\EditApiKeyForm::getBlockPrefix()}
     *
     * @var string
     */
    protected const API_KEY_FORM_NAME = 'api-key';

    /**
     * @uses {@link \Spryker\Zed\ApiKeyGui\Communication\Form\DeleteApiKeyForm::getBlockPrefix()}
     *
     * @var string
     */
    protected const API_KEY_DELETE_FORM_NAME = 'delete_api_key_form';

    /**
     * @uses {@link \Spryker\Zed\UserMerchantPortalGui\Communication\Form\ChangePasswordForm::FORM_NAME}
     *
     * @var string
     */
    protected const MERCHANT_POSTAL_CHANGE_PASSWORD_FORM_NAME = 'security-merchant-portal-gui_change-password';

    /**
     * @var int
     */
    protected const CUSTOMER_CODE_VALIDITY_TTL = 30;

    /**
     * @var int
     */
    protected const USER_CODE_VALIDITY_TTL = 30;

    /**
     * @var int
     */
    protected const CUSTOMER_ATTEMPTS_LIMIT = 3;

    /**
     * @var int
     */
    protected const USER_ATTEMPTS_LIMIT = 3;

    /**
     * Specification:
     * - Returns the multi-factor authentication code length for customer.
     *
     * @api
     *
     * @return int
     */
    public function getCustomerCodeLength(): int
    {
        return $this->getSharedConfig()->getCustomerCodeLength();
    }

    /**
     * Specification:
     * - Returns the multi-factor authentication code length for user.
     *
     * @api
     *
     * @return int
     */
    public function getUserCodeLength(): int
    {
        return $this->getSharedConfig()->getUserCodeLength();
    }

    /**
     * Specification:
     * - Returns the code validity TTL in minutes for customer.
     *
     * @api
     *
     * @return int
     */
    public function getCustomerCodeValidityTtl(): int
    {
        return static::CUSTOMER_CODE_VALIDITY_TTL;
    }

    /**
     * Specification:
     * - Returns the code validity TTL in minutes for user.
     *
     * @api
     *
     * @return int
     */
    public function getUserCodeValidityTtl(): int
    {
        return static::USER_CODE_VALIDITY_TTL;
    }

    /**
     * Specification:
     * - Returns a list of enabled routes and their corresponding forms for multi-factor authentication in the following format:
     * [
     *    'routeName' => ['formName'],
     * ]
     *
     * @api
     *
     * @return array<string, array<string>>
     */
    public function getEnabledRoutesAndForms(): array
    {
        return [
            static::USER_PASSWORD_RESET_ROUTE => [static::RESET_PASSWORD_FORM_NAME],
            static::USER_DELETE_ROUTE => [static::DELETE_CONFIRM_FORM_NAME],
            static::USER_UPDATE_ROUTE => [static::USER_FORM_NAME],
            static::USER_CREATE_ROUTE => [static::USER_FORM_NAME],
            static::API_KEY_EDIT_ROUTE => [static::API_KEY_FORM_NAME],
            static::API_KEY_DELETE_ROUTE => [static::API_KEY_DELETE_FORM_NAME],
            static::API_KEY_CREATE_ROUTE => [static::API_KEY_FORM_NAME],
            static::MERCHANT_POSTAL_CHANGE_PASSWORD_ROUTE => [static::MERCHANT_POSTAL_CHANGE_PASSWORD_FORM_NAME],
        ];
    }

    /**
     * Specification:
     * - Returns the multi-factor authentication code validation attempt limit for customer.
     *
     * @api
     *
     * @return int
     */
    public function getCustomerAttemptsLimit(): int
    {
        return static::CUSTOMER_ATTEMPTS_LIMIT;
    }

    /**
     * Specification:
     * - Returns the multi-factor authentication code validation attempt limit for user.
     *
     * @api
     *
     * @return int
     */
    public function getUserAttemptsLimit(): int
    {
        return static::USER_ATTEMPTS_LIMIT;
    }
}
