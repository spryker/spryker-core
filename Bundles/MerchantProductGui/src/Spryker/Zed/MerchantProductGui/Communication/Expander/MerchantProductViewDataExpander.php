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
     * @phpstan-param array<string, mixed> $viewData
     *
     * @phpstan-return array<string, mixed>
     *
     * @param array $viewData
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function expandDataWithMerchantByIdProductAbstract(array $viewData, int $idProductAbstract): array
    {
        $viewData['merchant'] = $this->merchantProductFacade->findMerchant((new MerchantProductCriteriaTransfer())->setIdProductAbstract($idProductAbstract));

        return $viewData;
    }
}
