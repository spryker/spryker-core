<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest;

use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CustomerDataChangeRequestConfig extends AbstractBundleConfig
{
    /**
     *  Specification:
     *  - Defines the name of the email template for the customer email change verification.
     *
     * @api
     *
     * @var string
     */
    public const CUSTOMER_EMAIL_CHANGE_VERIFICATION_MAIL_TYPE = 'CUSTOMER_EMAIL_CHANGE_VERIFICATION_MAIL';

    /**
     * Specification:
     * - Defines the name of the email template for the customer email change notification.
     *
     * @api
     *
     * @var string
     */
    public const CUSTOMER_EMAIL_CHANGE_NOTIFICATION_MAIL_TYPE = 'CUSTOMER_EMAIL_CHANGE_NOTIFICATION_MAIL';

    /**
     * @var string
     */
    protected const EMAIL_CHANGE_TOKEN_URL = '/customer-data-change-request/change-email?verification_token=%s&_store=%s';

    /**
     * @var string
     */
    protected const EMAIL_CHANGE_TOKEN_URL_WITHOUT_STORE = '/customer-data-change-request/change-email?verification_token=%s';

    /**
     * @var int
     */
    protected const DEFAULT_EMAIL_CHANGE_VERIFICATION_EXPIRATION_MINUTES = 30;

    /**
     * Specification:
     * - Returns the expiration time in minutes for the email change verification token.
     *
     * @api
     *
     * @return int
     */
    public function getEmailChangeVerificationExpirationMinutes(): int
    {
        return static::DEFAULT_EMAIL_CHANGE_VERIFICATION_EXPIRATION_MINUTES;
    }

    /**
     * Specification:
     * - Provides a email change token url.
     *
     * @api
     *
     * @param string $token
     * @param string|null $storeName
     *
     * @return string
     */
    public function getEmailChaneTokenUrl(string $token, ?string $storeName = null): string
    {
        if ($storeName === null) {
            return sprintf($this->getHostYves() . static::EMAIL_CHANGE_TOKEN_URL_WITHOUT_STORE, $token);
        }

        return sprintf($this->getHostYves() . static::EMAIL_CHANGE_TOKEN_URL, $token, $storeName);
    }

    /**
     * @api
     *
     * @return string
     */
    protected function getHostYves(): string
    {
        return $this->get(CustomerConstants::BASE_URL_YVES);
    }
}
