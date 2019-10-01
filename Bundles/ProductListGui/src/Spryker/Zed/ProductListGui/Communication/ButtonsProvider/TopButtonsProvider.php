<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\ButtonsProvider;

use Generated\Shared\Transfer\ButtonCollectionTransfer;
use Generated\Shared\Transfer\ButtonTransfer;

class TopButtonsProvider implements TopButtonsProviderInterface
{
    /**
     * @uses \Spryker\Zed\ProductListGui\Communication\Controller\CreateController::indexAction
     */
    protected const CREATE_PRODUCT_LIST_BUTTON_URL = '/product-list-gui/create';
    protected const CREATE_PRODUCT_LIST_BUTTON_TITLE = 'Create a Product List';
    protected const CREATE_PRODUCT_LIST_BUTTON_OPTIONS = [
        'class' => 'btn-create',
        'iconClass' => 'fa fa-plus',
    ];

    /**
     * @var \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTopButtonsExpanderPluginInterface[]
     */
    protected $productListTopButtonsExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTopButtonsExpanderPluginInterface[] $productListTopButtonsExpanderPlugins
     */
    public function __construct(array $productListTopButtonsExpanderPlugins)
    {
        $this->productListTopButtonsExpanderPlugins = $productListTopButtonsExpanderPlugins;
    }

    /**
     * @return \Generated\Shared\Transfer\ButtonCollectionTransfer
     */
    public function getTopButtons(): ButtonCollectionTransfer
    {
        $buttonCollectionTransfer = $this->addDefaultButtons();

        return $this->expandButtonCollectionTransfer($buttonCollectionTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ButtonCollectionTransfer
     */
    protected function addDefaultButtons(): ButtonCollectionTransfer
    {
        $buttonCollectionTransfer = new ButtonCollectionTransfer();

        $buttonCollectionTransfer = $this->addCreateProductListButton($buttonCollectionTransfer);

        return $buttonCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ButtonCollectionTransfer $buttonCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonCollectionTransfer
     */
    protected function addCreateProductListButton(ButtonCollectionTransfer $buttonCollectionTransfer): ButtonCollectionTransfer
    {
        $buttonTransfer = (new ButtonTransfer())
            ->setTitle(static::CREATE_PRODUCT_LIST_BUTTON_TITLE)
            ->setUrl(static::CREATE_PRODUCT_LIST_BUTTON_URL)
            ->setDefaultOptions(static::CREATE_PRODUCT_LIST_BUTTON_OPTIONS);

        return $buttonCollectionTransfer->addButton($buttonTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ButtonCollectionTransfer $buttonCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonCollectionTransfer
     */
    protected function expandButtonCollectionTransfer(ButtonCollectionTransfer $buttonCollectionTransfer): ButtonCollectionTransfer
    {
        foreach ($this->productListTopButtonsExpanderPlugins as $productListTopButtonsExpanderPlugin) {
            $buttonCollectionTransfer = $productListTopButtonsExpanderPlugin->expand($buttonCollectionTransfer);
        }

        return $buttonCollectionTransfer;
    }
}
