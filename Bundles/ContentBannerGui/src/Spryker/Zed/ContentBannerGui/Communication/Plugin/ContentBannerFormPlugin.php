<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerGui\Communication\Plugin;

use Generated\Shared\Transfer\ContentBannerTransfer;
use Spryker\Shared\ContentBannerGui\ContentBannerGuiConfig;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ContentBannerGui\Communication\Form\BannerContentTermForm;
use Spryker\Zed\ContentGuiExtension\Plugin\ContentPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class ContentBannerFormPlugin extends AbstractPlugin implements ContentPluginInterface
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
        return ContentBannerGuiConfig::CONTENT_TERM_BANNER;
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
        return ContentBannerGuiConfig::CONTENT_TYPE_BANNER;
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
        return BannerContentTermForm::class;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array|null $params
     *
     * @return \Generated\Shared\Transfer\ContentBannerTransfer
     */
    public function getTransferObject(?array $params = null): AbstractTransfer
    {
        $contentBannerTransfer = new ContentBannerTransfer();

        return $contentBannerTransfer;
    }
}
