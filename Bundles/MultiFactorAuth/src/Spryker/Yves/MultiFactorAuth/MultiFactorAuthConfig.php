<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class MultiFactorAuthConfig extends AbstractBundleConfig
{
    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_PROFILE
     *
     * @var string
     */
    protected const ROUTE_CUSTOMER_PROFILE = 'customer/profile';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_DELETE
     *
     * @var string
     */
    protected const ROUTE_NAME_CUSTOMER_DELETE = 'customer/delete';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_DELETE_CONFIRM
     *
     * @var string
     */
    protected const ROUTE_NAME_CUSTOMER_DELETE_CONFIRM = 'customer/delete/confirm';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\PasswordForm::getBlockPrefix()
     *
     * @var string
     */
    protected const PASSWORD_FORM_NAME = 'passwordForm';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\ProfileForm::getBlockPrefix()
     *
     * @var string
     */
    protected const PROFILE_FORM_NAME = 'profileForm';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\CustomerDeleteForm::getBlockPrefix()
     *
     * @var string
     */
    protected const CUSTOMER_DELETE_FORM_NAME = 'customer_delete_form';

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
            static::ROUTE_CUSTOMER_PROFILE => [static::PASSWORD_FORM_NAME, static::PROFILE_FORM_NAME],
            static::ROUTE_NAME_CUSTOMER_DELETE => [static::CUSTOMER_DELETE_FORM_NAME],
            static::ROUTE_NAME_CUSTOMER_DELETE_CONFIRM => [static::CUSTOMER_DELETE_FORM_NAME],
        ];
    }
}
