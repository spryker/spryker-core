<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinderGui\Controller;

use Generated\Shared\Transfer\FactFinderTrackingRequestTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\FactFinderGui\FactFinderGuiFactory getFactory()
 */
class TrackController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $factFinderTrackingRequestTransfer = new FactFinderTrackingRequestTransfer();
        $factFinderTrackingRequestTransfer->fromArray($request->query->all());

        $sessionId = $request->getSession()->getId();
        $factFinderTrackingRequestTransfer->setSid($sessionId);

        $factFinderTrackingResponseTransfer = $this->getFactory()
            ->getFactFinderClient()
            ->track($factFinderTrackingRequestTransfer);

        if (!$factFinderTrackingResponseTransfer->getResult()) {
            return $this->jsonResponse(null, 400);
        }

        return $this->jsonResponse();
    }

}
