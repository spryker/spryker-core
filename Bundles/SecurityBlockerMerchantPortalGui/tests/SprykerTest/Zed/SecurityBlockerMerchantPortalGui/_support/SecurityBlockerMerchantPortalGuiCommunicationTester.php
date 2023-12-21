<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityBlockerMerchantPortalGui;

use Codeception\Actor;
use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(\SprykerTest\Zed\SecurityBlockerMerchantPortalGui\PHPMD)
 */
class SecurityBlockerMerchantPortalGuiCommunicationTester extends Actor
{
    use _generated\SecurityBlockerMerchantPortalGuiCommunicationTesterActions;

    /**
     * @uses \Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\EventSubscriber\SecurityBlockerMerchantPortalUserEventSubscriber::FORM_NAME
     *
     * @var string
     */
    protected const FORM_NAME = 'security-merchant-portal-gui';

    /**
     * @uses \Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\EventSubscriber\SecurityBlockerMerchantPortalUserEventSubscriber::FIELD_USERNAME
     *
     * @var string
     */
    protected const FIELD_USERNAME = 'username';

    /**
     * @uses \Spryker\Zed\SecurityBlockerMerchantPortalGui\SecurityBlockerMerchantPortalGuiConfig::MERCHANT_PORTAL_LOGIN_CHECK_URL
     *
     * @var string
     */
    protected const MERCHANT_PORTAL_LOGIN_CHECK_URL = 'security-merchant-portal-gui/login_check';

    /**
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer
     */
    public function createSecurityCheckAuthContextTransfer(string $type): SecurityCheckAuthContextTransfer
    {
        return (new SecurityCheckAuthContextTransfer())
            ->setType($type)
            ->setIp('');
    }

    /**
     * @param string $method
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function createRequest(string $method, SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): Request
    {
        $request = Request::create(
            '/' . static::MERCHANT_PORTAL_LOGIN_CHECK_URL,
            $method,
            [
                static::FORM_NAME => [
                    static::FIELD_USERNAME => $securityCheckAuthContextTransfer->getAccount(),
                ],
            ],
            [],
            [],
            [
                'REMOTE_ADDR' => $securityCheckAuthContextTransfer->getIp(),
            ],
        );
        $request->attributes->add(['_route' => '/' . static::MERCHANT_PORTAL_LOGIN_CHECK_URL]);

        return $request;
    }
}
