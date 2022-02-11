<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductGui\Communication\Expander;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Spryker\Zed\MerchantProductGui\Dependency\Facade\MerchantProductGuiToMerchantProductFacadeInterface;

class MerchantProductViewDataExpander implements MerchantProductViewDataExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductGui\Dependency\Facade\MerchantProductGuiToMerchantProductFacadeInterface
     */
    protected $merchantProductFacade;

    /**
     * @param \Spryker\Zed\MerchantProductGui\Dependency\Facade\MerchantProductGuiToMerchantProductFacadeInterface $merchantProductFacade
     */
    public function __construct(MerchantProductGuiToMerchantProductFacadeInterface $merchantProductFacade)
    {
        $this->merchantProductFacade = $merchantProductFacade;
    }

    /**
     * @param array<string, mixed> $viewData
     * @param int $idProductAbstract
     *
     * @return array<string, mixed>
     */
    public function expandDataWithMerchantByIdProductAbstract(array $viewData, int $idProductAbstract): array
    {
        $viewData['merchant'] = $this->merchantProductFacade->findMerchant((new MerchantProductCriteriaTransfer())->setIdProductAbstract($idProductAbstract));

        return $viewData;
    }

    /**
     * @param array<string, mixed> $viewData
     *
     * @return array<string, mixed>
     */
    public function expandDataWithMerchant(array $viewData): array
    {
        if (!isset($viewData['currentProduct']['id_product_abstract'])) {
            return $viewData;
        }

        return $this->expandDataWithMerchantByIdProductAbstract($viewData, (int)$viewData['currentProduct']['id_product_abstract']);
    }
}
