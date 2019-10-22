<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\ButtonCreator;

use Generated\Shared\Transfer\ButtonTransfer;
use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToMerchantProfileFacadeInterface;

class MerchantProfileChangeStatusButtonCreator implements MerchantProfileChangeStatusButtonCreatorInterface
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
     * @var \Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToMerchantProfileFacadeInterface
     */
    protected $merchantProfileFacade;

    /**
     * @param \Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToMerchantProfileFacadeInterface $merchantProfileFacade
     */
    public function __construct(MerchantProfileGuiToMerchantProfileFacadeInterface $merchantProfileFacade)
    {
        $this->merchantProfileFacade = $merchantProfileFacade;
    }

    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer|null
     */
    public function getChangeStatusButton(int $idMerchant): ?ButtonTransfer
    {
        $merchantProfileTransfer = $this->findMerchantProfileByIdMerchant($idMerchant);

        if ($merchantProfileTransfer === null) {
            return null;
        }

        if ($merchantProfileTransfer->getIsActive()) {
            return $this->createDeactivateButtonTransfer($merchantProfileTransfer->getIdMerchantProfile());
        }

        return $this->createActivateButtonTransfer($merchantProfileTransfer->getIdMerchantProfile());
    }

    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer|null
     */
    protected function findMerchantProfileByIdMerchant(int $idMerchant): ?MerchantProfileTransfer
    {
        $merchantProfileCriteriaFilterTransfer = new MerchantProfileCriteriaFilterTransfer();
        $merchantProfileCriteriaFilterTransfer->setFkMerchant($idMerchant);
        $merchantProfileTransfer = $this->merchantProfileFacade
            ->findOne($merchantProfileCriteriaFilterTransfer);

        return $merchantProfileTransfer;
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
