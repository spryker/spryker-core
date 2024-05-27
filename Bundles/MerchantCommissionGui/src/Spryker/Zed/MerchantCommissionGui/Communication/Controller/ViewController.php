<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantCommissionConditionsTransfer;
use Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantCommissionGui\Communication\MerchantCommissionGuiCommunicationFactory getFactory()
 */
class ViewController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_ID_MERCHANT_COMMISSION = 'id-merchant-commission';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MERCHANT_COMMISSION_DOES_NOT_EXIST = 'Merchant Commission with ID "%id%" doesn\'t exist.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_ID = '%id%';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\Communication\Controller\ListController::indexAction()
     *
     * @var string
     */
    protected const URL_MERCHANT_RELATION_REQUEST_LIST = '/merchant-commission-gui/list';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request): array|RedirectResponse
    {
        $idMerchantCommission = $this->castId($request->get(static::REQUEST_PARAM_ID_MERCHANT_COMMISSION));
        $merchantCommissionCriteriaTransfer = $this->createMerchantCommissionCriteriaTransfer($idMerchantCommission);
        $merchantCommissionTransfer = $this->getFactory()
            ->getMerchantCommissionFacade()
            ->getMerchantCommissionCollection($merchantCommissionCriteriaTransfer)
            ->getMerchantCommissions()
            ->getIterator()
            ->current();

        if ($merchantCommissionTransfer === null) {
            $this->addErrorMessage(static::ERROR_MESSAGE_MERCHANT_COMMISSION_DOES_NOT_EXIST, [
                static::ERROR_MESSAGE_PARAMETER_ID => $idMerchantCommission,
            ]);

            return $this->redirectResponse(static::URL_MERCHANT_RELATION_REQUEST_LIST);
        }

        return $this->viewResponse([
            'merchantCommission' => $merchantCommissionTransfer,
            'urlMerchantCommissionList' => static::URL_MERCHANT_RELATION_REQUEST_LIST,
        ]);
    }

    /**
     * @param int $idMerchantCommission
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer
     */
    protected function createMerchantCommissionCriteriaTransfer(int $idMerchantCommission): MerchantCommissionCriteriaTransfer
    {
        $merchantCommissionConditionsTransfer = (new MerchantCommissionConditionsTransfer())
            ->setWithMerchantRelations(true)
            ->setWithStoreRelations(true)
            ->setWithCommissionMerchantAmountRelations(true)
            ->addIdMerchantCommission($idMerchantCommission);

        return (new MerchantCommissionCriteriaTransfer())
            ->setMerchantCommissionConditions($merchantCommissionConditionsTransfer);
    }
}
