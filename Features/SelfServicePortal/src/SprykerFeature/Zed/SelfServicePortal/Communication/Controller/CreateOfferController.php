<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\ServiceConditionsTransfer;
use Generated\Shared\Transfer\ServiceCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 */
class CreateOfferController extends AbstractController
{
    /**
     * @var string
     */
    public const PARAM_ID_PRODUCT_CONCRETE = 'id-product-concrete';

    /**
     * @var string
     */
    public const PARAM_ID_SERVICE_POINT = 'id-service-point';

    /**
     * @uses \Spryker\Zed\ProductOfferGui\Communication\Controller\ListController::indexAction()
     *
     * @var string
     */
    protected const URL_PATH_PRODUCT_OFFER_GUI_LIST = '/product-offer-gui/list';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\CreateOfferController::indexAction()
     *
     * @var string
     */
    protected const URL_PATH_SELF_SERVICE_PORTAL_CREATE_OFFER = '/self-service-portal/create-offer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    public function indexAction(Request $request): array
    {
        $productConcreteTable = $this
            ->getFactory()
            ->createProductConcreteTable();

        return $this->viewResponse([
            'productConcreteTable' => $productConcreteTable->render(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function formAction(Request $request): RedirectResponse|array
    {
        if (!$request->query->has(static::PARAM_ID_PRODUCT_CONCRETE)) {
            return $this->redirectResponse(static::URL_PATH_SELF_SERVICE_PORTAL_CREATE_OFFER);
        }

        $productConcreteTransfer = $this->getFactory()->createProductReader()->getProductConcrete(
            $this->castId($request->query->get(static::PARAM_ID_PRODUCT_CONCRETE)),
        );

        $createOfferForm = $this->getFactory()
            ->createCreateOfferForm($productConcreteTransfer);

        $createOfferForm->handleRequest($request);

        if ($createOfferForm->isSubmitted() && $createOfferForm->isValid()) {
            $productOfferTransfer = $this->getFactory()->getProductOfferFacade()
                ->create($createOfferForm->getData());

            $this->addSuccessMessage(sprintf('Offer %s has been created successfully.', $productOfferTransfer->getProductOfferReference()));

            return $this->redirectResponse(static::URL_PATH_PRODUCT_OFFER_GUI_LIST);
        }

        return $this->viewResponse([
            'createOfferForm' => $createOfferForm->createView(),
            'productConcreteTransfer' => $productConcreteTransfer,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $productTable = $this
            ->getFactory()
            ->createProductConcreteTable();

        return $this->jsonResponse(
            $productTable->fetchData(),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function serviceChoicesAction(Request $request): JsonResponse
    {
        $idServicePoint = $this->castId($request->query->get(static::PARAM_ID_SERVICE_POINT));

        $servicePointCollectionTransfer = $this->getFactory()->getServicePointFacade()->getServiceCollection(
            (new ServiceCriteriaTransfer())
                ->setServiceConditions(
                    (new ServiceConditionsTransfer())
                        ->setServicePointIds([$idServicePoint])
                        ->setIsActive(true),
                ),
        );

        $choices = [];
        foreach ($servicePointCollectionTransfer->getServices() as $serviceTransfer) {
            $activityString = $serviceTransfer->getIsActive() ? 'Active' : 'Inactive';
            $serviceKey = sprintf('%s (%s)', $serviceTransfer->getServiceTypeOrFail()->getNameOrFail(), $activityString);
            $choices[$serviceKey] = $serviceTransfer->getUuidOrFail();
        }

        return $this->jsonResponse($choices);
    }
}
