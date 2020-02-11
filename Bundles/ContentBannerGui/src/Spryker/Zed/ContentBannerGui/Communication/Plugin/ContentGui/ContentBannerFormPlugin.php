<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerGui\Communication\Plugin\ContentGui;

use Generated\Shared\Transfer\ContentBannerTermTransfer;
use Spryker\Shared\ContentBannerGui\ContentBannerGuiConfig;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\ContentBannerGui\Communication\Form\BannerContentTermForm;
use Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentBannerGui\Communication\ContentBannerGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentBannerGui\ContentBannerGuiConfig getConfig()
 */
class ContentBannerFormPlugin extends AbstractPlugin implements ContentPluginInterface
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
        return ContentBannerGuiConfig::CONTENT_TERM_BANNER;
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
        return ContentBannerGuiConfig::CONTENT_TYPE_BANNER;
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
        return BannerContentTermForm::class;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array|null $params
     *
     * @return \Generated\Shared\Transfer\ContentBannerTermTransfer
     */
    public function getTransferObject(?array $params = null): TransferInterface
    {
        $contentBannerTermTransfer = new ContentBannerTermTransfer();

        if ($params) {
            $contentBannerTermTransfer->fromArray($params);
        }

        return $contentBannerTermTransfer;
    }
}
