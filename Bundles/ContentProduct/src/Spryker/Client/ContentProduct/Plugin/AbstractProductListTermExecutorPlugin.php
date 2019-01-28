<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct\Plugin;

use Generated\Shared\Transfer\ContentAbstractProductListTransfer;
use Spryker\Client\ContentStorageExtension\Plugin\ContentTermExecutorPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\ContentProduct\ContentProductConfig;

/**
 * @method \Spryker\Client\ContentProduct\ContentProductFactory getFactory()
 */
class AbstractProductListTermExecutorPlugin extends AbstractPlugin implements ContentTermExecutorPluginInterface
{
    /**
     * {@inheritdoc}
     *
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
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getTermKey(): string
    {
        return ContentProductConfig::CONTENT_TERM_ABSTRACT_PRODUCT_LIST;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getTypeKey(): string
    {
        return ContentProductConfig::CONTENT_TYPE_PRODUCT_LIST;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\ContentAbstractProductListTransfer
     */
    protected function mapParametersToTransferObject(array $parameters): ContentAbstractProductListTransfer
    {
        $contentAbstractProductListTransfer = new ContentAbstractProductListTransfer();
        $contentAbstractProductListTransfer->fromArray($parameters, true);

        return $contentAbstractProductListTransfer;
    }
}
