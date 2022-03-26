<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Persistence\Propel\Formatter;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Formatter\AbstractFormatter;

class SynchronizationDataTransferObjectFormatter extends AbstractFormatter
{
    /**
     * @var array
     */
    protected $objects = [];

    /**
     * @param \Propel\Runtime\DataFetcher\DataFetcherInterface|null $dataFetcher
     *
     * @throws \Propel\Runtime\Exception\LogicException
     *
     * @return array<\Spryker\Shared\Kernel\Transfer\TransferInterface>
     */
    public function format(?DataFetcherInterface $dataFetcher = null): array
    {
        $this->checkInit();

        if ($dataFetcher) {
            $this->setDataFetcher($dataFetcher);
        } else {
            $dataFetcher = $this->getDataFetcher();
        }

        $collection = [];
        if ($this->isWithOneToMany() && $this->hasLimit) {
            throw new LogicException('Cannot use limit() in conjunction with with() on a one-to-many relationship. Please remove the with() call, or the limit() call.');
        }

        $items = [];
        foreach ($dataFetcher as $row) {
            $rowArray = &$this->hydratePropelObjectCollection($row);
            if ($rowArray) {
                $items[] = &$rowArray;
            }
        }

        foreach ($items as $item) {
            $synchronizationDataTransfer = $this->mapArrayToSynchronizationDataTransfer($item);
            $collection[] = $synchronizationDataTransfer;
        }

        $this->currentObjects = [];
        $this->alreadyHydratedObjects = [];
        $dataFetcher->close();

        return $collection;
    }

    /**
     * @param \Propel\Runtime\DataFetcher\DataFetcherInterface|null $dataFetcher
     *
     * @throws \Propel\Runtime\Exception\LogicException
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|null
     */
    public function formatOne(?DataFetcherInterface $dataFetcher = null)
    {
        $this->checkInit();
        $result = null;

        if ($this->isWithOneToMany() && $this->hasLimit) {
            throw new LogicException('Cannot use limit() in conjunction with with() on a one-to-many relationship. Please remove the with() call, or the limit() call.');
        }

        if ($dataFetcher) {
            $this->setDataFetcher($dataFetcher);
        } else {
            $dataFetcher = $this->getDataFetcher();
        }

        $item = [];
        foreach ($dataFetcher as $row) {
            $rowArray = &$this->hydratePropelObjectCollection($row);
            if ($rowArray) {
                $item = &$rowArray;
            }
        }

        if ($item) {
            $result = $this->mapArrayToSynchronizationDataTransfer($item);
        }

        $this->currentObjects = [];
        $this->alreadyHydratedObjects = [];
        $dataFetcher->close();

        return $result;
    }

    /**
     * @param array $rowArray
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function mapArrayToSynchronizationDataTransfer(array $rowArray)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->fromArray($rowArray, true);

        return $synchronizationDataTransfer;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface|null $record
     *
     * @return array The original record turned into an array
     */
    public function formatRecord(?ActiveRecordInterface $record = null): array
    {
        return $record ? $record->toArray() : [];
    }

    /**
     * @return string|null
     */
    public function getCollectionClassName(): ?string
    {
        return '\Propel\Runtime\Collection\ArrayCollection';
    }

    /**
     * @return bool
     */
    public function isObjectFormatter(): bool
    {
        return false;
    }
}
