<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Expander;

use Generated\Shared\Transfer\ButtonCollectionTransfer;
use Generated\Shared\Transfer\ButtonTransfer;

class ProductListButtonsExpander implements ProductListButtonsExpanderInterface
{
    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\TemplateController::indexAction
     */
    protected const CONFIGURABLE_BUNDLE_TEMPLATE_LIST_BUTTON_URL = '/configurable-bundle-gui/template';
    protected const CONFIGURABLE_BUNDLE_TEMPLATE_LIST_BUTTON_TITLE = 'Configurable Bundle Templates';
    protected const CONFIGURABLE_BUNDLE_TEMPLATE_LIST_BUTTON_OPTIONS = [
        'class' => 'btn-view',
        'iconClass' => 'fa fa-caret-right',
    ];

    /**
     * @param \Generated\Shared\Transfer\ButtonCollectionTransfer $buttonCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonCollectionTransfer
     */
    public function expandButtonCollection(ButtonCollectionTransfer $buttonCollectionTransfer): ButtonCollectionTransfer
    {
        $buttonCollectionTransfer->addButton($this->createTemplateListButtonTransfer());

        return $buttonCollectionTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    protected function createTemplateListButtonTransfer(): ButtonTransfer
    {
        return (new ButtonTransfer())
            ->setTitle(static::CONFIGURABLE_BUNDLE_TEMPLATE_LIST_BUTTON_TITLE)
            ->setUrl(static::CONFIGURABLE_BUNDLE_TEMPLATE_LIST_BUTTON_URL)
            ->setDefaultOptions(static::CONFIGURABLE_BUNDLE_TEMPLATE_LIST_BUTTON_OPTIONS);
    }
}
