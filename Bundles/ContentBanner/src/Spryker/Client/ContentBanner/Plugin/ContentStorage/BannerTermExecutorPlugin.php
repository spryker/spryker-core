<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner\Plugin\ContentStorage;

use Generated\Shared\Transfer\ContentBannerTermTransfer;
use Spryker\Client\ContentStorageExtension\Dependency\Plugin\ContentTermExecutorPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\ContentBanner\ContentBannerConfig;

/**
 * @method \Spryker\Client\ContentBanner\ContentBannerClient getClient()
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
        return $this->getClient()
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
     * @return \Generated\Shared\Transfer\ContentBannerTermTransfer
     */
    protected function mapParametersToTransferObject(array $parameters): ContentBannerTermTransfer
    {
        $contentBannerTermTransfer = new ContentBannerTermTransfer();
        $contentBannerTermTransfer->fromArray($parameters, true);

        return $contentBannerTermTransfer;
    }
}
