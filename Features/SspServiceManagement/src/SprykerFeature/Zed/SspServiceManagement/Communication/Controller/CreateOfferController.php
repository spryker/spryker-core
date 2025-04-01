<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Communication\Controller;

use Generated\Shared\Transfer\ServiceConditionsTransfer;
use Generated\Shared\Transfer\ServiceCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\Communication\SspServiceManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementFacadeInterface getFacade()
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request): RedirectResponse|array
    {
        if (!$request->query->has(static::PARAM_ID_PRODUCT_CONCRETE)) {
            $productConcreteTable = $this
                ->getFactory()
                ->createProductConcreteTable();

            return $this->viewResponse([
                'productConcreteTable' => $productConcreteTable->render(),
            ]);
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

            return $this->redirectResponse('/product-offer-gui/list');
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
            $choices[$serviceTransfer->getServiceTypeOrFail()->getNameOrFail()] = $serviceTransfer->getUuidOrFail();
        }

        return $this->jsonResponse($choices);
    }
}
