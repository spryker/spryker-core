<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityMerchantPortalGui;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\GroupCriteriaTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Spryker\Zed\SecurityMerchantPortalGuiExtension\Dependency\Plugin\MerchantUserLoginRestrictionPluginInterface;

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
 * @SuppressWarnings(PHPMD)
 */
class SecurityMerchantPortalGuiCommunicationTester extends Actor
{
    use _generated\SecurityMerchantPortalGuiCommunicationTesterActions;

    /**
     * @uses \Spryker\Shared\Acl\AclConstants::ROOT_GROUP
     *
     * @var string
     */
    protected const ROOT_GROUP_NAME = 'root_group';

    /**
     * @param array<string, callable> $params
     *
     * @return \Spryker\Zed\SecurityMerchantPortalGuiExtension\Dependency\Plugin\MerchantUserLoginRestrictionPluginInterface
     */
    public function createMerchantUserLoginRestrictionPluginMock(array $params): MerchantUserLoginRestrictionPluginInterface
    {
        return Stub::makeEmpty(
            MerchantUserLoginRestrictionPluginInterface::class,
            $params,
        );
    }

    /**
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function haveRootGroup(): GroupTransfer
    {
        $groupCriteriaTransfer = (new GroupCriteriaTransfer())->setName(static::ROOT_GROUP_NAME);
        $groupTransfer = $this->getLocator()->acl()->facade()->findGroup($groupCriteriaTransfer);
        if ($groupTransfer) {
            return $groupTransfer;
        }

        return $this->haveGroup([GroupTransfer::NAME => static::ROOT_GROUP_NAME]);
    }
}
