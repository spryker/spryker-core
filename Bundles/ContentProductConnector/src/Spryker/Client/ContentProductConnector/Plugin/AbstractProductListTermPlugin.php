<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProductConnector\Plugin;

use Generated\Shared\Transfer\ContentAbstractProductListTransfer;
use Spryker\Client\ContentStorageExtension\Plugin\ContentTermExecutorPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\ContentProductConnector\ContentProductConnectorConfig;

/**
 * @method \Spryker\Client\ContentProductConnector\ContentProductConnectorFactory getFactory()
 */
class AbstractProductListTermPlugin extends AbstractPlugin implements ContentTermExecutorPluginInterface
{
    /**
     * @api
     *
     * @param array $parameters
     *
     * @return array
     */
    public function execute(array $parameters): array
    {
        return $this->getFactory()
            ->createAbstractProductListTermExecutor()
            ->execute(
                $this->mapParametersToTransferObject($parameters)
            );
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTermKey(): string
    {
        return ContentProductConnectorConfig::CONTENT_TERM_ABSTRACT_PRODUCT_LIST;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTypeKey(): string
    {
        return ContentProductConnectorConfig::CONTENT_TYPE_PRODUCT_LIST;
    }

    /**
     * @api
     *
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\ContentAbstractProductListTransfer
     */
    public function mapParametersToTransferObject(array $parameters): ContentAbstractProductListTransfer
    {
        $contentAbstractProductListTransfer = new ContentAbstractProductListTransfer();
        $contentAbstractProductListTransfer->fromArray($parameters, true);

        return $contentAbstractProductListTransfer;
    }
}
