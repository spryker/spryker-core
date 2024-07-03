<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantCommissionConnector\Business;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductMerchantCommissionConnector\Business\ProductMerchantCommissionConnectorBusinessFactory getFactory()
 */
class ProductMerchantCommissionConnectorFacade extends AbstractFacade implements ProductMerchantCommissionConnectorFacadeInterface
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
    public function collectByProductAttribute(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        RuleEngineClauseTransfer $ruleEngineClauseTransfer
    ): array {
        return $this->getFactory()
            ->createProductAttributeCollectorRule()
            ->collect($merchantCommissionCalculationRequestTransfer, $ruleEngineClauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return list<string>
     */
    public function getProductAttributeKeys(): array
    {
        return $this->getFactory()
            ->createProductAttributeReader()
            ->getProductAttributeKeys();
    }
}
