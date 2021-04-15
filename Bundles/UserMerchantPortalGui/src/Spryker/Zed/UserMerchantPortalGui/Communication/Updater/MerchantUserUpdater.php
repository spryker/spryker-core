<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Updater;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\UserMerchantPortalGui\Communication\Exception\MerchantUserNotFoundException;
use Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface;

class MerchantUserUpdater implements MerchantUserUpdaterInterface
{
    /**
     * @var \Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\UserMerchantPortalGuiExtension\Dependency\Plugin\MerchantUserPostChangePluginInterface[]
     */
    protected $merchantUserPostChangePlugins;

    /**
     * @param \Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\UserMerchantPortalGuiExtension\Dependency\Plugin\MerchantUserPostChangePluginInterface[] $merchantUserPostChangePlugins
     */
    public function __construct(
        UserMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        array $merchantUserPostChangePlugins
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->merchantUserPostChangePlugins = $merchantUserPostChangePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return void
     */
    public function updateMerchantUser(MerchantUserTransfer $merchantUserTransfer): void
    {
        $this->merchantUserFacade->updateMerchantUser($merchantUserTransfer);

        $this->executePostChangePlugins($merchantUserTransfer);
    }

    /**
     * @param string $newPassword
     *
     * @return void
     */
    public function updateCurrentMerchantUserPassword(string $newPassword): void
    {
        $currentMerchantUserTransfer = $this->updateMerchantUserPassword(
            $this->merchantUserFacade->getCurrentMerchantUser(),
            $newPassword
        );

        $this->merchantUserFacade->setCurrentMerchantUser($currentMerchantUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param string $newPassword
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    protected function updateMerchantUserPassword(
        MerchantUserTransfer $merchantUserTransfer,
        string $newPassword
    ): MerchantUserTransfer {
        $merchantUserTransfer->getUserOrFail()->setPassword($newPassword);
        $this->merchantUserFacade->updateMerchantUser($merchantUserTransfer);

        $merchantUserTransfer = $this->reloadMerchantUser($merchantUserTransfer);

        return $this->executePostChangePlugins($merchantUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    protected function executePostChangePlugins(
        MerchantUserTransfer $merchantUserTransfer
    ): MerchantUserTransfer {
        foreach ($this->merchantUserPostChangePlugins as $merchantUserPostChangePlugin) {
            $merchantUserTransfer = $merchantUserPostChangePlugin->execute($merchantUserTransfer);
        }

        return $merchantUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @throws \Spryker\Zed\UserMerchantPortalGui\Communication\Exception\MerchantUserNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    protected function reloadMerchantUser(MerchantUserTransfer $merchantUserTransfer): MerchantUserTransfer
    {
        $idUser = $merchantUserTransfer->getIdUserOrFail();
        $merchantUserCriteriaTransfer = (new MerchantUserCriteriaTransfer())
            ->setWithUser(true)
            ->setIdUser($idUser);

        $merchantUserTransfer = $this->merchantUserFacade
            ->findMerchantUser($merchantUserCriteriaTransfer);

        if (!$merchantUserTransfer) {
            throw new MerchantUserNotFoundException((string)$idUser);
        }

        return $merchantUserTransfer;
    }
}
