<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Communication\Plugin\ResourceShareExtension;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface;

/**
 * @method \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface getFacade()
 * @method \Spryker\Zed\SharedCart\SharedCartConfig getConfig()
 * @method \Spryker\Zed\SharedCart\Communication\SharedCartCommunicationFactory getFactory()
 */
class SharedCartActivatorStrategyPlugin extends AbstractPlugin implements ResourceShareActivatorStrategyPluginInterface
{
    /**
     * {@inheritdoc}
     * - TODO
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function execute(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        return $this->getFacade()->applyResourceShareActivatorStrategy($resourceShareRequestTransfer);
    }

    /**
     * {@inheritdoc}
     * - TODO
     *
     * @api
     *
     * @return bool
     */
    public function isLoginRequired(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     * - TODO
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return bool
     */
    public function isApplicable(ResourceShareTransfer $resourceShareTransfer): bool
    {
        return $this->getFacade()->isResourceShareActivatorStrategyApplicable($resourceShareTransfer);
    }
}
