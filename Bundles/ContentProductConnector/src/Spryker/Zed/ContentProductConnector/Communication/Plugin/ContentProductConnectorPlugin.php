<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductConnector\Communication\Plugin;

use Generated\Shared\Transfer\ContentAbstractProductListTransfer;
use Spryker\Shared\ContentProductConnector\ContentProductConnectorConfig;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ContentGuiExtension\Plugin\ContentPluginInterface;
use Spryker\Zed\ContentProductConnector\Communication\Form\AbstractProductListContentTermForm;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class ContentProductConnectorPlugin extends AbstractPlugin implements ContentPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getTermKey(): string
    {
        return ContentProductConnectorConfig::CONTENT_TERM_ABSTRACT_PRODUCT_LIST;
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
        return ContentProductConnectorConfig::CONTENT_TYPE_PRODUCT_LIST;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getForm(): string
    {
        return AbstractProductListContentTermForm::class;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array|null $params
     *
     * @return \Generated\Shared\Transfer\ContentAbstractProductListTransfer
     */
    public function getTransferObject(?array $params = null): AbstractTransfer
    {
        $contentAbstractProductListTransfer = new ContentAbstractProductListTransfer();

        if (empty($params) || empty($params['skus'])) {
            $contentAbstractProductListTransfer->setSkus(['']);

            return $contentAbstractProductListTransfer;
        }

        $contentAbstractProductListTransfer->fromArray($params);

        return $contentAbstractProductListTransfer;
    }
}
