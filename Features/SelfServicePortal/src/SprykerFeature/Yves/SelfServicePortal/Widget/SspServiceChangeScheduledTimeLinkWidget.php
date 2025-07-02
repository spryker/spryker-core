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
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SspServiceChangeScheduledTimeLinkWidget extends AbstractWidget
{
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
        return 'SspServiceChangeScheduledTimeLinkWidget';
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
        return '@SelfServicePortal/views/service-change-scheduled-time-link/service-change-scheduled-time-link.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addIsVisibleParameter(ItemTransfer $itemTransfer): void
    {
        $productClasses = $itemTransfer->getProductClasses()->getArrayCopy();
        $isScheduled = $this->hasProductClassName($productClasses, $this->getConfig()->getScheduledProductClassName());
        $isService = $this->hasProductClassName($productClasses, $this->getConfig()->getServiceProductClassName());

        $isVisible = $isService && $isScheduled;

        $this->addParameter(static::PARAMETER_IS_VISIBLE, $isVisible);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductClassTransfer> $productClasses
     * @param string $className
     *
     * @return bool
     */
    protected function hasProductClassName(array $productClasses, string $className): bool
    {
        if (!$productClasses) {
            return false;
        }

        foreach ($productClasses as $productClass) {
            if ($productClass->getName() === $className) {
                return true;
            }
        }

        return false;
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
}
