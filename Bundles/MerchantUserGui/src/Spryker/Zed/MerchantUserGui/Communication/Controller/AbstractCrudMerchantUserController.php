<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\MerchantUserGui\Communication\MerchantUserGuiCommunicationFactory getFactory()
 */
abstract class AbstractCrudMerchantUserController extends AbstractController
{
    /**
     * @param int $idMerchant
     *
     * @return string
     */
    protected function getMerchantUserListUrl(int $idMerchant): string
    {
        return $this->getFactory()
            ->getRouterFacade()
            ->getRouter()
            ->generate(
                'merchant-gui:edit-merchant',
                [
                    'id-merchant' => $idMerchant,
                    '_fragment' => 'tab-content-merchant-user',
                ]
            );
    }

    /**
     * @return string
     */
    protected function getMerchantListUrl(): string
    {
        return $this->getFactory()
            ->getRouterFacade()
            ->getRouter()
            ->generate('merchant-gui:list-merchant');
    }
}
