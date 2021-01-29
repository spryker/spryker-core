<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageStorage\Plugin;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface;
use Spryker\Shared\ProductImageStorage\ProductImageStorageConfig;

/**
 * @method \Spryker\Client\ProductImageStorage\ProductImageStorageFactory getFactory()
 */
class ProductViewImageExpanderPlugin extends AbstractPlugin implements ProductViewExpanderPluginInterface
{
    /**
     * @var string
     */
    protected $imageSetName;

    /**
     * @param string $imageSetName
     */
    public function __construct($imageSetName = ProductImageStorageConfig::DEFAULT_IMAGE_SET_NAME)
    {
        $this->imageSetName = $imageSetName;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(ProductViewTransfer $productViewTransfer, array $productData, $localeName)
    {
        return $this->getFactory()
            ->createProductViewImageExpander()
            ->expandProductViewImageData($productViewTransfer, $localeName, $this->imageSetName);
    }
}
