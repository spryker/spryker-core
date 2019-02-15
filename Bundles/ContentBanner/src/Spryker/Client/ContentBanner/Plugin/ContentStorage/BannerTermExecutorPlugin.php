<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner\Plugin\ContentStorage;

use Generated\Shared\Transfer\ContentBannerTransfer;
use Spryker\Client\ContentStorageExtension\Plugin\ContentTermExecutorPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\ContentBanner\ContentBannerConfig;

/**
 * @method \Spryker\Client\ContentBanner\ContentBannerFactory getFactory()
 */
class BannerTermExecutorPlugin extends AbstractPlugin implements ContentTermExecutorPluginInterface
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
            ->createBannerTermExecutor()
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
        return ContentBannerConfig::CONTENT_TERM_BANNER;
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
        return ContentBannerConfig::CONTENT_TYPE_BANNER;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\ContentBannerTransfer
     */
    protected function mapParametersToTransferObject(array $parameters): ContentBannerTransfer
    {
        $contentBannerTransfer = new ContentBannerTransfer();
        $contentBannerTransfer->fromArray($parameters, true);

        return $contentBannerTransfer;
    }
}
