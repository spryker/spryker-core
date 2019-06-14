<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui\Communication\Plugin\ContentGui;

use Spryker\Shared\ContentFileGui\ContentFileGuiConfig;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\ContentFileGui\Communication\Form\FileListContentTermForm;
use Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentFileGui\Communication\ContentFileGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentFileGui\ContentFileGuiConfig getConfig()
 */
class ContentFileListFormPlugin extends AbstractPlugin implements ContentPluginInterface
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
        return ContentFileGuiConfig::CONTENT_TERM_FILE_LIST;
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
        return ContentFileGuiConfig::CONTENT_TYPE_FILE_LIST;
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
        return FileListContentTermForm::class;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array|null $params
     *
     * @return \Generated\Shared\Transfer\ContentFileListTermTransfer
     */
    public function getTransferObject(?array $params = null): TransferInterface
    {
        return $this->getFactory()
            ->createContentFileListGuiFormDataMapper()
            ->mapDataToContentFileListTermTransfer($params);
    }
}
