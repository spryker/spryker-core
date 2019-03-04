<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct\Plugin\ContentStorage;

use Generated\Shared\Transfer\ContentProductAbstractListTransfer;
use Spryker\Client\ContentStorageExtension\Dependency\Plugin\ContentTermExecutorPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\ContentProduct\ContentProductConfig;

/**
 * @method \Spryker\Client\ContentProduct\ContentProductFactory getFactory()
 */
class ProductAbstractListTermExecutorPlugin extends AbstractPlugin implements ContentTermExecutorPluginInterface
{
    /**
     * {@inheritdoc}
     * - Creates needed format.
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
            ->createProductAbstractListTermExecutor()
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
        return ContentProductConfig::CONTENT_TERM_PRODUCT_ABSTRACT_LIST;
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
        return ContentProductConfig::CONTENT_TYPE_PRODUCT_ABSTRACT_LIST;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTransfer
     */
    protected function mapParametersToTransferObject(array $parameters): ContentProductAbstractListTransfer
    {
        return (new ContentProductAbstractListTransfer())->fromArray($parameters, true);
    }
}
