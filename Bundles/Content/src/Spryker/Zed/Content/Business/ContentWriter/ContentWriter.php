<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Business\ContentWriter;

use Generated\Shared\Transfer\ContentTransfer;
use Spryker\Zed\Content\Dependency\Service\ContentToUtilUuidGeneratorServiceInterface;
use Spryker\Zed\Content\Persistence\ContentEntityManagerInterface;

class ContentWriter implements ContentWriterInterface
{
    /**
     * @var \Spryker\Zed\Content\Persistence\ContentEntityManagerInterface
     */
    protected $contentEntityManager;

    /**
     * @var \Spryker\Zed\Content\Dependency\Service\ContentToUtilUuidGeneratorServiceInterface
     */
    protected $utilUuidGeneratorService;

    /**
     * @param \Spryker\Zed\Content\Persistence\ContentEntityManagerInterface $contentEntityManager
     * @param \Spryker\Zed\Content\Dependency\Service\ContentToUtilUuidGeneratorServiceInterface $utilUuidGeneratorService
     */
    public function __construct(
        ContentEntityManagerInterface $contentEntityManager,
        ContentToUtilUuidGeneratorServiceInterface $utilUuidGeneratorService
    ) {
        $this->contentEntityManager = $contentEntityManager;
        $this->utilUuidGeneratorService = $utilUuidGeneratorService;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function create(ContentTransfer $contentTransfer): ContentTransfer
    {
        $contentTransfer->requireName()
            ->requireContentTypeKey()
            ->requireContentTermKey()
            ->requireLocalizedContents();
        $contentTransfer = $this->contentEntityManager->saveContent($contentTransfer);

        return $this->persistContentKey($contentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function update(ContentTransfer $contentTransfer): ContentTransfer
    {
        $contentTransfer->requireIdContent();
        $contentTransfer = $this->contentEntityManager->saveContent($contentTransfer);

        return $this->persistContentKey($contentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    protected function persistContentKey(ContentTransfer $contentTransfer): ContentTransfer
    {
        if ($contentTransfer->getKey()) {
            return $contentTransfer;
        }

        $contentTransfer->setKey(
            $this->utilUuidGeneratorService->generateUuid5FromObjectId((string)$contentTransfer->getIdContent())
        );

        return $this->contentEntityManager->saveContent($contentTransfer);
    }
}
