<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner\Mapper;

use Generated\Shared\Transfer\ContentBannerTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;
use Spryker\Client\ContentBanner\Exception\MissingBannerTermException;

class ContentBannerTypeMapper implements ContentBannerTypeMapperInterface
{
    /**
     * @var \Spryker\Client\ContentBanner\Executor\ContentBannerTermExecutorInterface[]
     */
    protected $contentBannerTermExecutors;

    /**
     * @param \Spryker\Client\ContentBanner\Executor\ContentBannerTermExecutorInterface[] $contentBannerTermExecutors
     */
    public function __construct(array $contentBannerTermExecutors)
    {
        $this->contentBannerTermExecutors = $contentBannerTermExecutors;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @throws \Spryker\Client\ContentBanner\Exception\MissingBannerTermException
     *
     * @return \Generated\Shared\Transfer\ContentBannerTypeTransfer
     */
    public function execute(ContentTypeContextTransfer $contentTypeContextTransfer): ContentBannerTypeTransfer
    {
        $term = $contentTypeContextTransfer->getTerm();

        if (!isset($this->contentBannerTermExecutors[$term])) {
            throw new MissingBannerTermException(sprintf('There is no ContentBanner Term which can work with the term %s.', $term));
        }

        $bannerTermToBannerTypeExecutor = $this->contentBannerTermExecutors[$term];

        return $bannerTermToBannerTypeExecutor->execute($contentTypeContextTransfer);
    }
}
