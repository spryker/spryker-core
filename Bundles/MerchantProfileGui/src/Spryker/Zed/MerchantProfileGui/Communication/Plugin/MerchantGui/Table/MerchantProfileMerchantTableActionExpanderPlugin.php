<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Table;

use Generated\Shared\Transfer\ButtonTransfer;
use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableActionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
 */
class MerchantProfileMerchantTableActionExpanderPlugin extends AbstractPlugin implements MerchantTableActionExpanderPluginInterface
{
    /**
     * @uses \Spryker\Zed\MerchantProfileGui\Communication\Controller\EditController::activateAction()
     */
    protected const URL_MERCHANT_PROFILE_ACTIVATE = '/merchant-profile-gui/edit/activate';

    /**
     * @uses \Spryker\Zed\MerchantProfileGui\Communication\Controller\EditController::deactivateAction()
     */
    protected const URL_MERCHANT_PROFILE_DEACTIVATE = '/merchant-profile-gui/edit/deactivate';

    protected const REQUEST_ID_MERCHANT_PROFILE = 'id-merchant-profile';

    /**
     * {@inheritDoc}
     * - Expands merchant table actions.
     *
     * @api
     *
     * @param array $item
     *
     * @return array
     */
    public function expand(array $item): array
    {
        $buttons = [];

        $merchantProfileCriteriaFilterTransfer = new MerchantProfileCriteriaFilterTransfer();
        $merchantProfileCriteriaFilterTransfer->setFkMerchant($item[SpyMerchantTableMap::COL_ID_MERCHANT]);
        $merchantProfileTransfer = $this->getFactory()
            ->getMerchantProfileFacade()
            ->findOne($merchantProfileCriteriaFilterTransfer);

        if ($merchantProfileTransfer === null) {
            return $buttons;
        }

        $buttons[] = $this->getChangeStatusButton($merchantProfileTransfer);

        return $buttons;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    protected function getChangeStatusButton(MerchantProfileTransfer $merchantProfileTransfer): ButtonTransfer
    {
        if ($merchantProfileTransfer->getIsActive()) {
            return $this->createDeactivateButtonTransfer($merchantProfileTransfer->getIdMerchantProfile());
        }

        return $this->createActivateButtonTransfer($merchantProfileTransfer->getIdMerchantProfile());
    }

    /**
     * @param int $idMerchantProfile
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    protected function createActivateButtonTransfer(int $idMerchantProfile): ButtonTransfer
    {
        return (new ButtonTransfer())
            ->setUrl(Url::generate(
                static::URL_MERCHANT_PROFILE_ACTIVATE,
                [static::REQUEST_ID_MERCHANT_PROFILE => $idMerchantProfile]
            ))
            ->setTitle('Activate')
            ->setDefaultOptions([
                'class' => 'btn-view',
                'icon' => 'fa fa-caret-right',
            ]);
    }

    /**
     * @param int $idMerchantProfile
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    protected function createDeactivateButtonTransfer(int $idMerchantProfile): ButtonTransfer
    {
        return (new ButtonTransfer())
            ->setUrl(Url::generate(
                static::URL_MERCHANT_PROFILE_DEACTIVATE,
                [static::REQUEST_ID_MERCHANT_PROFILE => $idMerchantProfile]
            ))
            ->setTitle('Deactivate')
            ->setDefaultOptions([
                'class' => 'btn-remove',
                'icon' => 'fa fa-trash',
            ]);
    }
}
