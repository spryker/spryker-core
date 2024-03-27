<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\MerchantRelationRequestMerchantPortalGuiCommunicationFactory getFactory()
 */
class MerchantRelationRequestsController extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    public function indexAction(): array
    {
        return $this->viewResponse([
            'merchantRelationRequestTableConfiguration' => $this->getFactory()
                ->createMerchantRelationRequestGuiTableConfigurationProvider()
                ->getConfiguration(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableDataAction(Request $request): Response
    {
        return $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createMerchantRelationRequestGuiTableDataProvider(),
            $this->getFactory()->createMerchantRelationRequestGuiTableConfigurationProvider()->getConfiguration(),
        );
    }
}
