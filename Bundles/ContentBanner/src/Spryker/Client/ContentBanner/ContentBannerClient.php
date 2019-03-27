<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner;

use Generated\Shared\Transfer\ContentBannerTransfer;
use Spryker\Client\ContentBanner\Exception\MissingBannerTermException;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\ContentBanner\ContentBannerConfig;

/**
 * @method \Spryker\Client\ContentBanner\ContentBannerFactory getFactory()
 */
class ContentBannerClient extends AbstractClient implements ContentBannerClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\ContentBannerTransfer $contentBannerTransfer
     *
     * @return array
     */
    public function execute(ContentBannerTransfer $contentBannerTransfer): array
    {
        return $this->getFactory()
            ->createBannerTermExecutor()
            ->execute($contentBannerTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idContent
     * @param string $localeName
     *
     * @throws \Spryker\Client\ContentBanner\Exception\MissingBannerTermException
     *
     * @return \Generated\Shared\Transfer\ExecutedBannerTransfer|null
     */
    public function getExecutedBannerById(int $idContent, string $localeName): ?ExecutedBannerTransfer
    {
        $unexecutedContent = $this->getFactory()
            ->getContentStorageClient()->findUnexecutedContentById($idContent, $localeName);

        $term = $unexecutedContent->getTerm();

        if ($term === ContentBannerConfig::CONTENT_TERM_BANNER) {
            $contentBannerTransfer = new ContentBannerTransfer();
            $contentBannerTransfer->fromArray($unexecutedContent->getContent(), true);

            return $this->getFactory()
                ->createBannerTermExecutor()
                ->execute($contentBannerTransfer);
        }

        throw new MissingBannerTermException(sprintf('There is no ContentBanner Term which can work with the term %s.', $termKey));
    }
}
