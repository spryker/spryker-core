<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence\Repository;

use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Formatter\ArrayFormatter;

class TransferObjectFormatter extends ArrayFormatter
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
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface[]
     */
    public function format(?DataFetcherInterface $dataFetcher = null)
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
            $rowArray = &$this->getStructuredArrayFromRow($row);
            if ($rowArray) {
                $items[] = &$rowArray;
            }
        }

        foreach ($items as $item) {
            $entityName = $this->getTableMap()->getPhpName();
            $entityTransfer = $this->mapArrayToEntityTransfer($entityName, $item);
            $collection[] = $entityTransfer;
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
            $rowArray = &$this->getStructuredArrayFromRow($row);
            if ($rowArray) {
                $item = &$rowArray;
            }
        }

        if ($item) {
            $entityName = $this->getTableMap()->getPhpName();
            $result = $this->mapArrayToEntityTransfer($entityName, $item);
        }

        $this->currentObjects = [];
        $this->alreadyHydratedObjects = [];
        $dataFetcher->close();

        return $result;
    }

    /**
     * @param string $entityName
     * @param array $rowArray
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function mapArrayToEntityTransfer($entityName, array $rowArray)
    {
        $entityTransferName = '\Generated\Shared\Transfer\\' . $entityName . 'EntityTransfer';

        $entityTransfer = new $entityTransferName;
        $entityTransfer->fromArray($rowArray, true);

        return $entityTransfer;
    }

    /**
     * @return bool
     */
    public function isObjectFormatter()
    {
        return false;
    }
}
