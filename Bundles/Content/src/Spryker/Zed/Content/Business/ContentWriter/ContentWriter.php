<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Business\ContentWriter;

use Generated\Shared\Transfer\ContentTransfer;
use Spryker\Zed\Content\Business\KeyProvider\ContentKeyProviderInterface;
use Spryker\Zed\Content\Persistence\ContentEntityManagerInterface;

class ContentWriter implements ContentWriterInterface
{
    /**
     * @var \Spryker\Zed\Content\Persistence\ContentEntityManagerInterface
     */
    protected $contentEntityManager;

    /**
     * @var \Spryker\Zed\Content\Business\KeyProvider\ContentKeyProviderInterface
     */
    protected $contentKeyProvider;

    /**
     * @param \Spryker\Zed\Content\Persistence\ContentEntityManagerInterface $contentEntityManager
     * @param \Spryker\Zed\Content\Business\KeyProvider\ContentKeyProviderInterface $contentKeyProvider
     */
    public function __construct(
        ContentEntityManagerInterface $contentEntityManager,
        ContentKeyProviderInterface $contentKeyProvider
    ) {
        $this->contentEntityManager = $contentEntityManager;
        $this->contentKeyProvider = $contentKeyProvider;
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

        if (!$contentTransfer->getKey()) {
            $contentTransfer->setKey($this->contentKeyProvider->generateContentKey());
        }

        return $this->contentEntityManager->saveContent($contentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function update(ContentTransfer $contentTransfer): ContentTransfer
    {
        $contentTransfer->requireIdContent();

        $contentTransfer->setKey(
            $this->contentKeyProvider->getContentKeyByIdContent($contentTransfer->getIdContent())
        );

        return $this->contentEntityManager->saveContent($contentTransfer);
    }
}
