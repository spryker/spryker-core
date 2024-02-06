<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\AclMerchantAgent;

use Codeception\Actor;
use Codeception\Stub;
use Exception;
use Spryker\Zed\AclMerchantAgent\AclMerchantAgentConfig;
use Spryker\Zed\AclMerchantAgent\AclMerchantAgentDependencyProvider;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(\SprykerTest\Zed\AclMerchantAgent\PHPMD)
 */
class AclMerchantAgentBusinessTester extends Actor
{
    use _generated\AclMerchantAgentBusinessTesterActions;

    /**
     * @param bool $isGranted
     * @param bool $isExceptionThrown
     *
     * @return void
     */
    public function mockAuthorizationChecker(bool $isGranted = true, bool $isExceptionThrown = false): void
    {
        $authorizationCheckerMock = Stub::makeEmpty(
            AuthorizationCheckerInterface::class,
            [
                'isGranted' => function () use ($isGranted, $isExceptionThrown) {
                    if ($isExceptionThrown) {
                        throw new Exception();
                    }

                    return $isGranted;
                },
            ],
        );
        $this->setDependency(AclMerchantAgentDependencyProvider::SERVICE_SECURITY_AUTHORIZATION_CHECKER, $authorizationCheckerMock);
    }

    /**
     * @param list<string> $merchantAgentAclBundlesWhitelist
     *
     * @return void
     */
    public function mockConfig(array $merchantAgentAclBundlesWhitelist): void
    {
        $configMock = Stub::makeEmpty(AclMerchantAgentConfig::class, [
            'getMerchantAgentAclBundleAllowedList' => $merchantAgentAclBundlesWhitelist,
        ]);

        $this->mockFactoryMethod('getConfig', $configMock);
    }
}
