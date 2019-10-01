<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui\Communication\Expander;

use Generated\Shared\Transfer\ButtonCollectionTransfer;
use Generated\Shared\Transfer\ButtonTransfer;
use Spryker\Service\UtilText\Model\Url\Url;

class ProductListButtonsExpander implements ProductListButtonsExpanderInterface
{
    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\TemplateController::indexAction
     */
    protected const MERCHANT_RELATION_LIST_BUTTON_URL = '/merchant-relationship-gui/list-merchant-relationship';
    protected const MERCHANT_RELATION_LIST_BUTTON_TITLE = 'Merchant Relations';
    protected const MERCHANT_RELATION_LIST_BUTTON_OPTIONS = [
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
            ->setTitle(static::MERCHANT_RELATION_LIST_BUTTON_TITLE)
            ->setUrl(Url::generate(static::MERCHANT_RELATION_LIST_BUTTON_URL))
            ->setDefaultOptions(static::MERCHANT_RELATION_LIST_BUTTON_OPTIONS);
    }
}
