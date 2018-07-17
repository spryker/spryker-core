<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi\Plugin;

use InvalidArgumentException;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerBeforeActionPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\SearchRestApi\SearchRestApiConfig;
use Spryker\Shared\Kernel\Store;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class SetStoreCurrentCurrencyBeforeActionPlugin extends AbstractPlugin implements ControllerBeforeActionPluginInterface
{
    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @param string $action
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function beforeAction(string $action, RestRequestInterface $restRequest): void
    {
        $currency = $restRequest->getHttpRequest()->query->get(SearchRestApiConfig::CURRENCY_STRING_PARAMETER);
        if ($currency) {
            try {
                //sets currency to whole current store, RPC calls to ZED will also receive this currency.
                Store::getInstance()
                    ->setCurrencyIsoCode(
                        $currency
                    );
            } catch (InvalidArgumentException $ex) {
                //left default currency
            }
        }
    }
}
