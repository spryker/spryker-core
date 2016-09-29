<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnector\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToDiscountInterface;
use Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToLocaleInterface;
use Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToProductInterface;

class ProductAttributeDecisionRule implements ProductAttributeDecisionRuleInterface
{

    /**
     * @var \Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToDiscountInterface
     */
    protected $discountFacade;

    /**
     * @var \Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToProductInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToProductInterface $productFacade
     * @param \Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToDiscountInterface $discountFacade
     * @param \Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToLocaleInterface $localeFacade
     */
    public function __construct(
        ProductDiscountConnectorToProductInterface $productFacade,
        ProductDiscountConnectorToDiscountInterface $discountFacade,
        ProductDiscountConnectorToLocaleInterface $localeFacade
    ) {
        $this->productFacade = $productFacade;
        $this->discountFacade = $discountFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $currentItemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $currentItemTransfer,
        ClauseTransfer $clauseTransfer
    ) {
        $attributeProcessor = $this->productFacade->getProductAttributeProcessorByAbstractSku(
            $currentItemTransfer->getAbstractSku()
        );

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $attributes = $attributeProcessor->mergeAttributes($localeTransfer->getLocaleName());
            foreach ($attributes as $attribute => $value) {
                if ($clauseTransfer->getAttribute() !== $attribute) {
                    continue;
                }

                if ($this->discountFacade->queryStringCompare($clauseTransfer, $value)) {
                    return true;
                }
            }
        }

        return false;
    }

}
