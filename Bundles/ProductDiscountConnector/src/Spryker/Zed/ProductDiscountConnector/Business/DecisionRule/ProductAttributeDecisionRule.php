<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnector\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
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
     * @var array<\Spryker\Zed\ProductDiscountConnectorExtension\Dependency\Plugin\ProductAttributeDecisionRuleExpanderPluginInterface>
     */
    protected $productAttributeDecisionRuleExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToProductInterface $productFacade
     * @param \Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToDiscountInterface $discountFacade
     * @param \Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToLocaleInterface $localeFacade
     * @param array<\Spryker\Zed\ProductDiscountConnectorExtension\Dependency\Plugin\ProductAttributeDecisionRuleExpanderPluginInterface> $productAttributeDecisionRuleExpanderPlugins
     */
    public function __construct(
        ProductDiscountConnectorToProductInterface $productFacade,
        ProductDiscountConnectorToDiscountInterface $discountFacade,
        ProductDiscountConnectorToLocaleInterface $localeFacade,
        array $productAttributeDecisionRuleExpanderPlugins
    ) {
        $this->discountFacade = $discountFacade;
        $this->localeFacade = $localeFacade;
        $this->productFacade = $productFacade;
        $this->productAttributeDecisionRuleExpanderPlugins = $productAttributeDecisionRuleExpanderPlugins;
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
        $productConcreteTransfer = $this->createProductConcreteTransfer($currentItemTransfer);

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $attributes = $this->productFacade->getCombinedConcreteAttributes($productConcreteTransfer, $localeTransfer);
            foreach ($attributes as $attribute => $value) {
                if ($clauseTransfer->getAttribute() !== $attribute) {
                    continue;
                }

                if ($this->discountFacade->queryStringCompare($clauseTransfer, $value)) {
                    return $this->executeProductAttributeDecisionRuleExpanderPlugins($quoteTransfer, $currentItemTransfer, $clauseTransfer);
                }
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $currentItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductConcreteTransfer(ItemTransfer $currentItemTransfer)
    {
        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setIdProductConcrete($currentItemTransfer->requireId()->getId());

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $currentItemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    protected function executeProductAttributeDecisionRuleExpanderPlugins(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $currentItemTransfer,
        ClauseTransfer $clauseTransfer
    ): bool {
        foreach ($this->productAttributeDecisionRuleExpanderPlugins as $attributeDecisionRuleExpanderPlugin) {
            if (!$attributeDecisionRuleExpanderPlugin->isSatisfiedBy($quoteTransfer, $currentItemTransfer, $clauseTransfer)) {
                return false;
            }
        }

        return true;
    }
}
