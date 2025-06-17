<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()()
 */
class SspServiceCancelWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_FORM = 'form';

    /**
     * @var string
     */
    protected const PARAMETER_ORDER_ITEM = 'orderItem';

    /**
     * @var string
     */
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     */
    public function __construct(ItemTransfer $itemTransfer)
    {
        $this->addFormParameter($itemTransfer);
        $this->addOrderItemParameter($itemTransfer);
        $this->addIsVisibleParameter($itemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'SspServiceCancelWidget';
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/service-cancel/service-cancel.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addFormParameter(ItemTransfer $itemTransfer): void
    {
        $form = $this->getFactory()->getSspServiceCancelForm($itemTransfer);

        $this->addParameter(static::PARAMETER_FORM, $form->createView());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addOrderItemParameter(ItemTransfer $itemTransfer): void
    {
        $this->addParameter(static::PARAMETER_ORDER_ITEM, $itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addIsVisibleParameter(ItemTransfer $itemTransfer): void
    {
        $isService = in_array($this->getConfig()->getServiceProductTypeName(), $itemTransfer->getProductTypes());

        $this->addParameter(static::PARAMETER_IS_VISIBLE, $isService);
    }
}
