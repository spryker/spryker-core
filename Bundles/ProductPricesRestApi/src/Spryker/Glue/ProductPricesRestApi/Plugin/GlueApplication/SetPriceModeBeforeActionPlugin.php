<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Plugin\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerBeforeActionPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiFactory getFactory()
 */
class SetPriceModeBeforeActionPlugin extends AbstractPlugin implements ControllerBeforeActionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sets the current priceMode from request.
     * - Uses PriceClient to set priceMode.
     *
     * @param string $action
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function beforeAction(string $action, RestRequestInterface $restRequest): void
    {
        $this->getFactory()->createPriceModeUpdater()->switchPriceMode($restRequest);
    }
}
