<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Form\MerchantRelationRequestListTableFiltersForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantRelationRequestGui\Communication\MerchantRelationRequestGuiCommunicationFactory getFactory()
 */
class ListController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    public function indexAction(Request $request): array
    {
        $merchantRelationRequestConditionsTransfer = $this->getMerchantRelationRequestConditions($request);

        $merchantRelationRequestListTable = $this->getFactory()
            ->createMerchantRelationRequestListTable($merchantRelationRequestConditionsTransfer);
        $merchantRelationRequestListTableFiltersForm = $this->getFactory()
            ->createMerchantRelationRequestListTableFiltersForm(
                $request->query->all(),
                $this->getFactory()->createMerchantRelationRequestListTableFiltersFormDataProvider()->getOptions(),
            );

        return $this->viewResponse([
            'merchantRelationRequestListTable' => $merchantRelationRequestListTable->render(),
            'merchantRelationRequestListTableFiltersForm' => $merchantRelationRequestListTableFiltersForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableDataAction(Request $request): JsonResponse
    {
        $merchantRelationRequestConditionsTransfer = $this->getMerchantRelationRequestConditions($request);

        $merchantRelationRequestListTable = $this->getFactory()
            ->createMerchantRelationRequestListTable($merchantRelationRequestConditionsTransfer);

        return $this->jsonResponse($merchantRelationRequestListTable->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer
     */
    protected function getMerchantRelationRequestConditions(Request $request): MerchantRelationRequestConditionsTransfer
    {
        $idCompany = $request->get(MerchantRelationRequestListTableFiltersForm::FIELD_COMPANY, null);
        $idMerchant = $request->get(MerchantRelationRequestListTableFiltersForm::FIELD_MERCHANT, null);

        $merchantRelationRequestConditionsTransfer = new MerchantRelationRequestConditionsTransfer();

        if ($idCompany) {
            $merchantRelationRequestConditionsTransfer->addIdCompany($idCompany);
        }

        if ($idMerchant) {
            $merchantRelationRequestConditionsTransfer->addIdMerchant($idMerchant);
        }

        return $merchantRelationRequestConditionsTransfer;
    }
}
