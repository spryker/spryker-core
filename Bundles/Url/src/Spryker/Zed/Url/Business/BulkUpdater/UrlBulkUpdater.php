<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\BulkUpdater;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Url\Business\Url\UrlReaderInterface;
use Spryker\Zed\Url\Persistence\UrlEntityManagerInterface;

class UrlBulkUpdater implements UrlBulkUpdaterInterface
{
    /**
     * @param \Spryker\Zed\Url\Business\Url\UrlReaderInterface $urlReader
     * @param array<\Spryker\Zed\Url\Business\Url\UrlUpdaterBeforeSaveObserverInterface> $updateBeforeSaveObservers
     * @param array<\Spryker\Zed\Url\Business\Url\UrlUpdaterAfterSaveObserverInterface> $updateAfterSaveObservers
     * @param \Spryker\Zed\Url\Persistence\UrlEntityManagerInterface $urlEntityManager
     */
    public function __construct(
        protected UrlReaderInterface $urlReader,
        protected array $updateBeforeSaveObservers,
        protected array $updateAfterSaveObservers,
        protected UrlEntityManagerInterface $urlEntityManager
    ) {
    }

    /**
     * @param array<\Generated\Shared\Transfer\UrlTransfer> $urlTransfers
     *
     * @return array<\Generated\Shared\Transfer\UrlTransfer>
     */
    public function update(array $urlTransfers): array
    {
        [$urlTransfers, $originalTransfers] = $this->filterExistingUrlTransfers($urlTransfers);

        foreach ($urlTransfers as $urlTransfer) {
            $this->notifyUpdateBeforeSaveObservers($urlTransfer, $originalTransfers[$urlTransfer->getIdUrl()]);
        }

        $this->urlEntityManager->saveUrlEntities($urlTransfers, false);

        foreach ($urlTransfers as $urlTransfer) {
            $this->notifyUpdateAfterSaveObservers($urlTransfer, $originalTransfers[$urlTransfer->getIdUrl()]);
        }

        return $urlTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer $originalTransfer
     *
     * @return void
     */
    protected function notifyUpdateBeforeSaveObservers(UrlTransfer $urlTransfer, UrlTransfer $originalTransfer): void
    {
        foreach ($this->updateBeforeSaveObservers as $observer) {
            $observer->handleUrlUpdate($urlTransfer, $originalTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer $originalTransfer
     *
     * @return void
     */
    protected function notifyUpdateAfterSaveObservers(UrlTransfer $urlTransfer, UrlTransfer $originalTransfer): void
    {
        foreach ($this->updateAfterSaveObservers as $observer) {
            $observer->handleUrlUpdate($urlTransfer, $originalTransfer);
        }
    }

    /**
     * @param array<\Generated\Shared\Transfer\UrlTransfer> $urlTransfers
     *
     * @return array<array<\Generated\Shared\Transfer\UrlTransfer>>
     */
    protected function filterExistingUrlTransfers(array $urlTransfers): array
    {
        $filteredUrlTransfers = [];
        $originalTransfers = [];
        foreach ($urlTransfers as $urlTransfer) {
            if ($urlTransfer->getOriginalUrl() === $urlTransfer->getUrl() || $this->urlReader->hasUrl($urlTransfer)) {
                continue;
            }

            $filteredUrlTransfers[$urlTransfer->getIdUrl()] = $urlTransfer;
            $originalTransfer = clone $urlTransfer;
            $originalTransfer->setUrl($urlTransfer->getOriginalUrl());
            $originalTransfers[$urlTransfer->getIdUrl()] = $originalTransfer;
        }

        return [$filteredUrlTransfers, $originalTransfers];
    }
}
