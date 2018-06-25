<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\Rest;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerBeforeActionPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Shared\Kernel\Store;

/**
 * @metod \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class SetStoreCurrentLocaleBeforeActionPlugin extends AbstractPlugin implements ControllerBeforeActionPluginInterface
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
        //sets locale to whole current store, RPC calls to ZED will also receive this locale.
        Store::getInstance()
            ->setCurrentLocale(
                $restRequest->getMetadata()->getLocale()
            );
    }
}
