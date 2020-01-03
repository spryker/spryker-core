<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Business\ContentReader;

use Generated\Shared\Transfer\ContentTransfer;
use Spryker\Zed\Content\Persistence\ContentRepositoryInterface;

class ContentReader implements ContentReaderInterface
{
    /**
     * @var \Spryker\Zed\Content\Persistence\ContentRepositoryInterface
     */
    protected $contentRepository;

    /**
     * @param \Spryker\Zed\Content\Persistence\ContentRepositoryInterface $contentRepository
     */
    public function __construct(ContentRepositoryInterface $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    /**
     * @param int $idContent
     *
     * @return \Generated\Shared\Transfer\ContentTransfer|null
     */
    public function findContentById(int $idContent): ?ContentTransfer
    {
        return $this->contentRepository->findContentById($idContent);
    }

    /**
     * @param string $contentKey
     *
     * @return \Generated\Shared\Transfer\ContentTransfer|null
     */
    public function findContentByKey(string $contentKey): ?ContentTransfer
    {
        return $this->contentRepository->findContentByKey($contentKey);
    }
}
