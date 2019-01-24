<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Plugin;

use Generated\Shared\Transfer\ContentAbstractProductListTransfer;
use Spryker\Shared\ContentProduct\ContentProductConfig;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ContentGuiExtension\Plugin\ContentPluginInterface;
use Spryker\Zed\ContentProductGui\Communication\Form\AbstractProductListContentTermForm;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class ContentProductFormPlugin extends AbstractPlugin implements ContentPluginInterface
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
        $params['skus'] = array_values($params['skus']);
        $contentAbstractProductListTransfer->fromArray($params);

        return $contentAbstractProductListTransfer;
    }
}
