<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Plugin\ContentGui;

use Generated\Shared\Transfer\ContentProductAbstractListTermTransfer;
use Spryker\Shared\ContentProductGui\ContentProductGuiConfig;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentPluginInterface;
use Spryker\Zed\ContentProductGui\Communication\Form\ProductAbstractListContentTermForm;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentProductGui\Communication\ContentProductGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentProductGui\ContentProductGuiConfig getConfig()
 */
class ProductAbstractListFormPlugin extends AbstractPlugin implements ContentPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getTermKey(): string
    {
        return ContentProductGuiConfig::CONTENT_TERM_PRODUCT_ABSTRACT_LIST;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getTypeKey(): string
    {
        return ContentProductGuiConfig::CONTENT_TYPE_PRODUCT_ABSTRACT_LIST;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getForm(): string
    {
        return ProductAbstractListContentTermForm::class;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array|null $params
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTermTransfer
     */
    public function getTransferObject(?array $params = null): TransferInterface
    {
        $contentProductAbstractListTermTransfer = new ContentProductAbstractListTermTransfer();

        if ($params) {
            $contentProductAbstractListTermTransfer->fromArray($params);
            $contentProductAbstractListTermTransfer->setIdProductAbstracts(
                array_values($contentProductAbstractListTermTransfer->getIdProductAbstracts())
            );
        }

        return $contentProductAbstractListTermTransfer;
    }
}
