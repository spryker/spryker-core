<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinder\Controller;

use Generated\Shared\Transfer\FactFinderTrackingRequestTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\FactFinder\FactFinderFactory getFactory()
 * @method \Spryker\Client\FactFinder\FactFinderClientInterface getClient()
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

        $factFinderTrackingRequestTransfer = $this->addSessionId($factFinderTrackingRequestTransfer);

        $factFinderTrackingResponseTransfer = $this->getClient()
            ->track($factFinderTrackingRequestTransfer);

        if (!$factFinderTrackingResponseTransfer->getResult()) {
            return $this->jsonResponse(nul, 400);
        }

        return $this->jsonResponse();
    }

    /**
     * @param FactFinderTrackingRequestTransfer $factFinderTrackingRequestTransfer
     *
     * @return FactFinderTrackingRequestTransfer
     */
    protected function addSessionId(FactFinderTrackingRequestTransfer $factFinderTrackingRequestTransfer)
    {
        $sessionId = $this->getClient()->getSession()->getId();
        $factFinderTrackingRequestTransfer->setSid($sessionId);

        return $factFinderTrackingRequestTransfer;
    }
}
