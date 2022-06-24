<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleDiscountConnector\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToDiscountFacadeInterface;
use Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToLocaleFacadeInterface;
use Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToProductFacadeInterface;

class ProductBundleAttributeDecisionRule implements ProductBundleAttributeDecisionRuleInterface
{
    /**
     * @var \Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToDiscountFacadeInterface
     */
    protected $discountFacade;

    /**
     * @var \Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToDiscountFacadeInterface $discountFacade
     * @param \Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductBundleDiscountConnectorToDiscountFacadeInterface $discountFacade,
        ProductBundleDiscountConnectorToLocaleFacadeInterface $localeFacade,
        ProductBundleDiscountConnectorToProductFacadeInterface $productFacade
    ) {
        $this->discountFacade = $discountFacade;
        $this->localeFacade = $localeFacade;
        $this->productFacade = $productFacade;
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
    ): bool {
        if ($currentItemTransfer->getRelatedBundleItemIdentifier() !== null) {
            return false;
        }

        $productConcreteTransfer = $this->createProductConcreteTransfer($currentItemTransfer);

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $attributes = $this->productFacade->getCombinedConcreteAttributes($productConcreteTransfer, $localeTransfer);
            if ($this->isProductBundleAttributesSatisfyClause($attributes, $clauseTransfer)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string, string> $attributes
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    protected function isProductBundleAttributesSatisfyClause(array $attributes, ClauseTransfer $clauseTransfer): bool
    {
        foreach ($attributes as $attribute => $value) {
            if ($clauseTransfer->getAttribute() !== $attribute) {
                continue;
            }

            if ($this->discountFacade->queryStringCompare($clauseTransfer, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $currentItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductConcreteTransfer(ItemTransfer $currentItemTransfer): ProductConcreteTransfer
    {
        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setIdProductConcrete($currentItemTransfer->getIdOrFail());

        return $productConcreteTransfer;
    }
}
