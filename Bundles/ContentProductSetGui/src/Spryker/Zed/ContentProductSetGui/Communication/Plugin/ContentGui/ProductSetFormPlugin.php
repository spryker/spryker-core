<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetGui\Communication\Plugin\ContentGui;

use Generated\Shared\Transfer\ContentProductSetTermTransfer;
use Spryker\Shared\ContentProductSetGui\ContentProductSetGuiConfig;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentPluginInterface;
use Spryker\Zed\ContentProductSetGui\Communication\Form\ProductSetContentTermForm;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentProductSetGui\Communication\ContentProductSetGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentProductSetGui\ContentProductSetGuiConfig getConfig()
 */
class ProductSetFormPlugin extends AbstractPlugin implements ContentPluginInterface
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
        return ContentProductSetGuiConfig::CONTENT_TERM_PRODUCT_SET;
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
        return ContentProductSetGuiConfig::CONTENT_TYPE_PRODUCT_SET;
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
        return ProductSetContentTermForm::class;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array|null $params
     *
     * @return \Generated\Shared\Transfer\ContentProductSetTermTransfer
     */
    public function getTransferObject(?array $params = null): TransferInterface
    {
        $contentProductSetTermTransfer = new ContentProductSetTermTransfer();

        if ($params) {
            $contentProductSetTermTransfer->fromArray($params);
        }

        return $contentProductSetTermTransfer;
    }
}
