<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Collector;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Shared\SqlCriteriaBuilder\CriteriaBuilder\CriteriaBuilderInterface;
use Spryker\Zed\Collector\Business\Exporter\Exception\DependencyException;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPdoCollectorQuery;

abstract class AbstractPdoCollector extends AbstractDatabaseCollector
{
    /**
     * @var \Spryker\Zed\Collector\Persistence\Collector\AbstractPdoCollectorQuery
     */
    protected $queryBuilder;

    /**
     * @var \Spryker\Shared\SqlCriteriaBuilder\CriteriaBuilder\CriteriaBuilderInterface
     */
    protected $criteriaBuilder;

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
     * @param \Spryker\Shared\SqlCriteriaBuilder\CriteriaBuilder\CriteriaBuilderInterface $criteriaBuilder
     *
     * @return void
     */
    public function setCriteriaBuilder(CriteriaBuilderInterface $criteriaBuilder)
    {
        $this->criteriaBuilder = $criteriaBuilder;
    }

    /**
     * @throws \Spryker\Zed\Collector\Business\Exporter\Exception\DependencyException
     *
     * @return void
     */
    protected function validateDependencies()
    {
        parent::validateDependencies();
        if (!($this->criteriaBuilder instanceof CriteriaBuilderInterface)) {
            throw new DependencyException(sprintf(
                'criteriaBuilder does not implement CriteriaBuilder\CriteriaBuilderInterface in %s',
                static::class
            ));
        }

        if (!($this->queryBuilder instanceof AbstractPdoCollectorQuery)) {
            throw new DependencyException(sprintf(
                'queryBuilder does not implement AbstractPdoCollectorQuery in %s',
                static::class
            ));
        }
    }

    /**
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    protected function generateBatchIterator()
    {
        return $this->utilDataReaderService->getBatchIteratorOrdered(
            $this->criteriaBuilder,
            $this->touchQueryContainer,
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

        $touchParameters = $this->getTouchQueryParameters($touchQuery);

        $this->criteriaBuilder
            ->setParameterCollection($touchParameters);

        $this->queryBuilder
            ->setStoreTransfer($this->getCurrentStore())
            ->setCriteriaBuilder($this->criteriaBuilder)
            ->setLocale($locale)
            ->prepare();

        $this->ensureCollectorColumnsAreSelected();
    }

    /**
     * @return void
     */
    protected function ensureCollectorColumnsAreSelected()
    {
        $sql = sprintf(
            $this->criteriaBuilder->getSqlTemplate(),
            CollectorConfig::COLLECTOR_TOUCH_ID,
            CollectorConfig::COLLECTOR_RESOURCE_ID,
            CollectorConfig::COLLECTOR_STORAGE_KEY,
            CollectorConfig::COLLECTOR_SEARCH_KEY
        );

        $this->criteriaBuilder->sql($sql);
    }
}
