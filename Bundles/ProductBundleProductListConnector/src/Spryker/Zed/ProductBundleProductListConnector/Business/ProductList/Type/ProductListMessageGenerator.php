<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToLocaleFacadeInterface;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductFacadeInterface;

class ProductListMessageGenerator implements ProductListMessageGeneratorInterface
{
    protected const SEPARATION_SIGN = '+';
    protected const PRODUCT_BUNDLE_PARAMETER_NAME = 'product_bundle';
    protected const PRODUCTS_BUNDLED_PARAMETER_NAME = 'products_bundled';

    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductBundleProductListConnectorToProductFacadeInterface $productFacade,
        ProductBundleProductListConnectorToLocaleFacadeInterface $localeFacade
    ) {
        $this->productFacade = $productFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param string $value
     * @param int $idProductConcreteBundle
     * @param int[] $idsProductConcreteAssigned
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    public function generateMessageTransfer(string $value, int $idProductConcreteBundle, array $idsProductConcreteAssigned): MessageTransfer
    {
        $currentLocale = $this->localeFacade->getCurrentLocale();

        $productConcreteMessageTextArray = [];
        foreach ($idsProductConcreteAssigned as $idProductConcreteAssigned) {
            $productConcreteMessageTextArray[] = $this->getProductConcreteMessageText($idProductConcreteAssigned, $currentLocale);
        }

        return (new MessageTransfer())
            ->setValue($this->generateValue($value))
            ->setParameters([
                static::PRODUCT_BUNDLE_PARAMETER_NAME => $this->getProductConcreteMessageText($idProductConcreteBundle, $currentLocale),
                static::PRODUCTS_BUNDLED_PARAMETER_NAME => implode(' ,', $productConcreteMessageTextArray),
            ]);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function generateValue(string $value): string
    {
        return sprintf('%s %s %s.', static::PRODUCT_BUNDLE_PARAMETER_NAME, $value, static::PRODUCTS_BUNDLED_PARAMETER_NAME);
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\LocaleTransfer $currentLocale
     *
     * @return string
     */
    protected function getProductConcreteMessageText(int $idProductConcrete, LocaleTransfer $currentLocale): string
    {
        $productConcreteTransfer = $this->productFacade->findProductConcreteById($idProductConcrete);
        $productConcreteName = '';

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributeTransfer) {
            if ($localizedAttributeTransfer->getLocale()->getIdLocale() === $currentLocale->getIdLocale()) {
                $productConcreteName = $localizedAttributeTransfer->getName();

                break;
            }
        }

        return sprintf('"%s %s %s"', $productConcreteName, static::SEPARATION_SIGN, $productConcreteTransfer->getSku());
    }
}
