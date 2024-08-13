<?php
// phpcs:ignoreFile
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\KernelApp\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\AppConfigUpdatedTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @method \Spryker\Zed\KernelApp\Business\KernelAppFacadeInterface getFacade()
 */
class AppConfigMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\AppConfigUpdatedTransfer $appConfigChangedTransfer
     *
     * @return void
     */
    public function onAppConfigUpdated(AppConfigUpdatedTransfer $appConfigChangedTransfer): void
    {
        $appConfigTransfer = (new AppConfigTransfer())->fromArray($appConfigChangedTransfer->toArray(), true);

        $this->getFacade()->writeAppConfig($appConfigTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return iterable<string, callable>
     */
    public function handles(): iterable
    {
        return [
            AppConfigUpdatedTransfer::class => [$this, 'onAppConfigUpdated'],
        ];
    }
}
