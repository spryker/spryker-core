<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Vault;

use Codeception\Actor;
use Spryker\Shared\Vault\VaultConfig as SharedVaultConfig;
use Spryker\Zed\Vault\Business\VaultFacadeInterface;
use Spryker\Zed\Vault\VaultConfig;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\Vault\Business\VaultFacade getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class VaultBusinessTester extends Actor
{
    use _generated\VaultBusinessTesterActions;

    /**
     * @param \Spryker\Shared\Vault\VaultConfig $sharedVaultConfig
     *
     * @return \Spryker\Zed\Vault\Business\VaultFacadeInterface
     */
    public function getVaultFacadeWithSharedConfig(SharedVaultConfig $sharedVaultConfig): VaultFacadeInterface
    {
        $vaultConfig = (new VaultConfig())
            ->setSharedConfig($sharedVaultConfig);

        $vaultBusinessFactory = $this->getFactory()
            ->setConfig($vaultConfig);

        $vaultFacade = $this->getFacade()
            ->setFactory($vaultBusinessFactory);

        return $vaultFacade;
    }

    /**
     * @param \Spryker\Zed\Vault\VaultConfig $vaultConfig
     *
     * @return \Spryker\Zed\Vault\Business\VaultFacadeInterface
     */
    public function getVaultFacadeWithConfig(VaultConfig $vaultConfig): VaultFacadeInterface
    {
        $vaultBusinessFactory = $this->getFactory()
            ->setConfig($vaultConfig);

        $vaultFacade = $this->getFacade()
            ->setFactory($vaultBusinessFactory);

        return $vaultFacade;
    }
}
