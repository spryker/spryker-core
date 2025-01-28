<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\BulkCreator;

use Spryker\Zed\Url\Persistence\UrlEntityManagerInterface;

class UrlBulkCreator implements UrlBulkCreatorInterface
{
    /**
     * @param array<\Spryker\Zed\Url\Business\Url\UrlCreatorBeforeSaveObserverInterface> $createBeforeSaveObservers
     * @param array<\Spryker\Zed\Url\Business\Url\UrlCreatorAfterSaveObserverInterface> $createAfterSaveObservers
     * @param \Spryker\Zed\Url\Persistence\UrlEntityManagerInterface $urlEntityManager
     */
    public function __construct(
        protected array $createBeforeSaveObservers,
        protected array $createAfterSaveObservers,
        protected UrlEntityManagerInterface $urlEntityManager
    ) {
    }

    /**
     * @param array<\Generated\Shared\Transfer\UrlTransfer> $urlTransfers
     *
     * @return array<\Generated\Shared\Transfer\UrlTransfer>
     */
    public function create(array $urlTransfers): array
    {
        foreach ($urlTransfers as $urlTransfer) {
            foreach ($this->createBeforeSaveObservers as $observer) {
                $observer->handleUrlCreation($urlTransfer);
            }
        }

        $this->urlEntityManager->saveUrlEntities($urlTransfers);

        foreach ($urlTransfers as $urlTransfer) {
            foreach ($this->createAfterSaveObservers as $observer) {
                $observer->handleUrlCreation($urlTransfer);
            }
        }

        return $urlTransfers;
    }
}
