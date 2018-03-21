<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\OfferGui\Communication\OfferGuiCommunicationFactory getFactory()
 */
class ViewController extends AbstractController
{
    /**
     * @return array
     */
    public function listAction()
    {
        return $this->viewResponse([
            'offers' => $this->getFactory()
                ->createOffersTable()
                ->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()->createOffersTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }
}
