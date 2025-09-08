<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\SspModelConditionsTransfer;
use Generated\Shared\Transfer\SspModelCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class ViewModelController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_ID_SSP_MODEL = 'id-ssp-model';

    /**
     * @var string
     */
    protected const MESSAGE_SSP_MODEL_NOT_FOUND = 'Model not found.';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ListModelController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_SSP_MODEL_LIST = '/self-service-portal/list-model';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request)
    {
        $idSspModel = $this->castId($request->query->get(static::PARAM_ID_SSP_MODEL));

        $sspModelCriteriaTransfer = (new SspModelCriteriaTransfer())
            ->setSspModelConditions(
                (new SspModelConditionsTransfer())->setSspModelIds([$idSspModel]),
            );

        $sspModelCollectionTransfer = $this->getFacade()->getSspModelCollection($sspModelCriteriaTransfer);

        if ($sspModelCollectionTransfer->getSspModels()->count() === 0) {
            $this->addErrorMessage(static::MESSAGE_SSP_MODEL_NOT_FOUND);

            return $this->redirectResponse(static::ROUTE_SSP_MODEL_LIST);
        }

        /** @var \Generated\Shared\Transfer\SspModelTransfer $sspModelTransfer */
        $sspModelTransfer = $sspModelCollectionTransfer->getSspModels()->getIterator()->current();

        return [
            'sspModel' => $sspModelTransfer,
            'imageUrl' => $this->getFactory()->createModelImageUrlProvider()->getImageUrl($sspModelTransfer),
            'listModelRoute' => static::ROUTE_SSP_MODEL_LIST,
        ];
    }
}
