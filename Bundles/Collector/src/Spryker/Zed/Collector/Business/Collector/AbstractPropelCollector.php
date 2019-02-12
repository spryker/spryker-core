<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Collector;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Zed\Collector\Business\Exporter\Exception\DependencyException;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

abstract class AbstractPropelCollector extends AbstractDatabaseCollector
{
    /**
     * @var \Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery
     */
    protected $queryBuilder;

    /**
     * @var \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected $utilDataReaderService;

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     */
    public function __construct(UtilDataReaderServiceInterface $utilDataReaderService)
    {
        $this->utilDataReaderService = $utilDataReaderService;
    }

    /**
     * @throws \Spryker\Zed\Collector\Business\Exporter\Exception\DependencyException
     *
     * @return void
     */
    protected function validateDependencies()
    {
        parent::validateDependencies();

        if (!($this->queryBuilder instanceof AbstractPropelCollectorQuery)) {
            throw new DependencyException(sprintf(
                'queryBuilder does not implement AbstractPropelCollectorQuery in %s',
                static::class
            ));
        }
    }

    /**
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    protected function generateBatchIterator()
    {
        return $this->utilDataReaderService->getPropelBatchIteratorOrdered(
            $this->queryBuilder->getTouchQuery(),
            $this->chunkSize,
            CollectorConfig::COLLECTOR_TOUCH_ID,
            Criteria::ASC
        );
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $touchQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    protected function prepareCollectorScope(SpyTouchQuery $touchQuery, LocaleTransfer $locale)
    {
        $this->locale = $locale;

        $this->queryBuilder->setTouchQuery($touchQuery);

        $this->queryBuilder
            ->setStoreTransfer($this->getCurrentStore())
            ->setLocale($locale)
            ->prepare();
    }
}
