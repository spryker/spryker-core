<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Plugin\GlueApplication;

use Generated\Shared\Transfer\RouteTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\TableColumnExpanderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Symfony\Component\Routing\Route;

/**
 * @method \Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorClient getClient()
 */
class IsProtectedTableColumnExpanderPlugin extends AbstractPlugin implements TableColumnExpanderPluginInterface
{
    /**
     * @var string
     */
    protected const APPLICATION_NAME = 'GlueStorefrontApiApplication';

    /**
     * @var string
     */
    protected const ANSWER_NO = 'No';

    /**
     * @var string
     */
    protected const ANSWER_YES = 'Yes';

    /**
     * @var string
     */
    protected const DEFAULT_METHOD = '_method';

    /**
     * @var string
     */
    protected const HEADER = 'Is Protected';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getHeader(): string
    {
        return static::HEADER;
    }

    /**
     * {@inheritDoc}
     *
     * @param \Symfony\Component\Routing\Route $route
     *
     * @return string
     */
    public function getRowData(Route $route): string
    {
        $routeTransfer = (new RouteTransfer())
            ->setRoute($route->getPath())
            ->setMethod($route->getDefault(static::DEFAULT_METHOD));

        if (!$this->getClient()->isProtected($routeTransfer)) {
            return static::ANSWER_NO;
        }

        return static::ANSWER_YES;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getApiApplicationName(): string
    {
        return static::APPLICATION_NAME;
    }
}
