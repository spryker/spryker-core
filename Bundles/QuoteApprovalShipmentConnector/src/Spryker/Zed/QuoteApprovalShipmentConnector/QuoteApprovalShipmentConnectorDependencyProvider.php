<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApprovalShipmentConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\QuoteApprovalShipmentConnector\Dependency\Facade\QuoteApprovalShipmentConnectorToQuoteApprovalFacadeBridge;

/**
 * @method \Spryker\Zed\QuoteApprovalShipmentConnector\QuoteApprovalShipmentConnectorConfig getConfig()
 */
class QuoteApprovalShipmentConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_QUOTE_APPROVAL = 'FACADE_QUOTE_APPROVAL';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addQuoteApprovalFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteApprovalFacade(Container $container): Container
    {
        $container->set(static::FACADE_QUOTE_APPROVAL, function (Container $container) {
            return new QuoteApprovalShipmentConnectorToQuoteApprovalFacadeBridge(
                $container->getLocator()->quoteApproval()->facade()
            );
        });

        return $container;
    }
}
