<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Controller;

use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\PriceProductMerchantRelationshipMerchantPortalGuiCommunicationFactory getFactory()
 */
class PriceProductMerchantRelationshipController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_MERCHANT_RELATIONSHIP = 'idMerchantRelationship';

    /**
     * @var string
     */
    protected const PARAM_VOLUME_QUANTITY = 'volumeQuantity';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function volumePriceDataAction(Request $request): Response
    {
        $inputType = GuiTableConfigurationBuilderInterface::COLUMN_TYPE_INPUT;
        $typeOptions = ['value' => 1, 'type' => 'number'];

        $merchantRelationshipId = $request->get(static::PARAM_MERCHANT_RELATIONSHIP);

        if (!$merchantRelationshipId || $merchantRelationshipId === 'null') {
            return new JsonResponse(['type' => $inputType, 'typeOptions' => $typeOptions]);
        }

        $inputType = GuiTableConfigurationBuilderInterface::COLUMN_TYPE_TEXT;
        $typeOptions = ['text' => '1', 'type' => 'text'];

        return new JsonResponse(['type' => $inputType, 'typeOptions' => $typeOptions]);
    }
}
