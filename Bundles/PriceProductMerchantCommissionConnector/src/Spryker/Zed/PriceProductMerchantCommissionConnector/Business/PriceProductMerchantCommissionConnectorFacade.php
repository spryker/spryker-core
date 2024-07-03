<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantCommissionConnector\Business;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PriceProductMerchantCommissionConnector\Business\PriceProductMerchantCommissionConnectorBusinessFactory getFactory()
 */
class PriceProductMerchantCommissionConnectorFacade extends AbstractFacade implements PriceProductMerchantCommissionConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>
     */
    public function collectByProductPrice(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        RuleEngineClauseTransfer $ruleEngineClauseTransfer
    ): array {
        return $this->getFactory()
            ->createProductPriceCollectorRule()
            ->collect($merchantCommissionCalculationRequestTransfer, $ruleEngineClauseTransfer);
    }
}
