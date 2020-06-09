<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Expander;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

class MerchantListDataExpander implements MerchantListDataExpanderInterface
{
    protected const URL_PARAM_ID_PRODUCT = 'id-merchant';

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(
        Request $request,
        MerchantGuiToMerchantFacadeInterface $merchantFacade
    ) {
        $this->request = $request;
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @phpstan-param array<string, mixed> $viewData
     *
     * @phpstan-return array<string, mixed>
     *
     * @param array $viewData
     *
     * @return array
     */
    public function expandData(array $viewData): array
    {
        $viewData['idMerchant'] = $this->request->get(static::URL_PARAM_ID_PRODUCT);

        $viewData['merchants'] = [];
        $merchantCollectionTransfer = $this->merchantFacade
            ->get(new MerchantCriteriaTransfer());

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $viewData['merchants'][$merchantTransfer->getIdMerchant()] = $merchantTransfer;
        }

        return $viewData;
    }
}
