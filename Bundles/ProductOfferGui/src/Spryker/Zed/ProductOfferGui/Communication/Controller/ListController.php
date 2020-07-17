<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\ProductOfferGui\Communication\ProductOfferGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferGui\Business\ProductOfferGuiFacadeInterface getFacade()
 */
class ListController extends AbstractController
{
    /**
     * @phpstan-return array<string, mixed>
     *
     * @return array
     */
    public function indexAction(): array
    {
        $offerTable = $this->getFactory()
            ->createOfferTable();

        return $this->viewResponse([
            'offerTable' => $offerTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $table = $this->getFactory()
            ->createOfferTable();

        return $this->jsonResponse($table->fetchData());
    }
}
