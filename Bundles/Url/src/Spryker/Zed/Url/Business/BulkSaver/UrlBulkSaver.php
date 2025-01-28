<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\BulkSaver;

use Spryker\Zed\Url\Business\BulkCreator\UrlBulkCreatorInterface;
use Spryker\Zed\Url\Business\BulkUpdater\UrlBulkUpdaterInterface;

class UrlBulkSaver implements UrlBulkSaverInterface
{
    /**
     * @param \Spryker\Zed\Url\Business\BulkCreator\UrlBulkCreatorInterface $urlBulkCreator
     * @param \Spryker\Zed\Url\Business\BulkUpdater\UrlBulkUpdaterInterface $urlBulkUpdater
     */
    public function __construct(
        protected UrlBulkCreatorInterface $urlBulkCreator,
        protected UrlBulkUpdaterInterface $urlBulkUpdater
    ) {
    }

    /**
     * @param array<\Generated\Shared\Transfer\UrlTransfer> $urlTransfers
     *
     * @return array<\Generated\Shared\Transfer\UrlTransfer>
     */
    public function save(array $urlTransfers): array
    {
        [$newUrlTransfers, $existingUrlTransfers] = $this->separateNewTransferFromExisting($urlTransfers);

        $urlTransfers = $this->urlBulkCreator->create($newUrlTransfers);

        return array_merge($urlTransfers, $this->urlBulkUpdater->update($existingUrlTransfers));
    }

    /**
     * @param array<\Generated\Shared\Transfer\UrlTransfer> $urlTransfers
     *
     * @return array<array<\Generated\Shared\Transfer\UrlTransfer>>
     */
    protected function separateNewTransferFromExisting(array $urlTransfers): array
    {
        $newUrlTransfers = [];
        $existingUrlTransfers = [];

        foreach ($urlTransfers as $urlTransfer) {
            if ($urlTransfer->getIdUrl() === null) {
                $newUrlTransfers[] = $urlTransfer;

                continue;
            }
                $existingUrlTransfers[] = $urlTransfer;
        }

        return [$newUrlTransfers, $existingUrlTransfers];
    }
}
