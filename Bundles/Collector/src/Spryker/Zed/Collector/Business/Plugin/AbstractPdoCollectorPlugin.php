<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Business\Plugin;

use Everon\Component\CriteriaBuilder\CriteriaBuilderInterface;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\Collector\Business\Exporter\Exception\DependencyException;
use Spryker\Zed\Collector\Business\Model\CountableIteratorInterface;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Collector\Persistence\Exporter\AbstractPdoCollectorQuery;
use Spryker\Zed\Collector\Persistence\PdoBatchIterator;

abstract class AbstractPdoCollectorPlugin extends AbstractCollectorPlugin
{

    /**
     * @var AbstractPdoCollectorQuery
     */
    protected $queryBuilder;

    /**
     * @var CriteriaBuilderInterface
     */
    protected $criteriaBuilder;

    /**
     * @param CriteriaBuilderInterface $criteriaBuilder
     */
    public function setCriteriaBuilder(CriteriaBuilderInterface $criteriaBuilder)
    {
        $this->criteriaBuilder = $criteriaBuilder;
    }

    /**
     * @return void
     */
    protected function validateDependencies()
    {
        parent::validateDependencies();
        if (!($this->criteriaBuilder instanceof CriteriaBuilderInterface)) {
            throw new DependencyException(sprintf('criteriaBuilder does not implement CriteriaBuilder\CriteriaBuilderInterface in %s', get_class($this)));
        }

        if (!($this->queryBuilder instanceof AbstractPdoCollectorQuery)) {
            throw new DependencyException(sprintf('queryBuilder does not implement AbstractPdoCollectorQuery in %s', get_class($this)));
        }
    }

    /**
     * @return CountableIteratorInterface
     */
    protected function generateBatchIterator()
    {
        return new PdoBatchIterator($this->criteriaBuilder, $this->touchQueryContainer, $this->collectResourceType(), $this->chunkSize);
    }

    /**
     * @param SpyTouchQuery $touchQuery
     * @param LocaleTransfer $locale
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
        $sql = sprintf($this->criteriaBuilder->getSqlTemplate(),
            CollectorConfig::COLLECTOR_TOUCH_ID,
            CollectorConfig::COLLECTOR_RESOURCE_ID,
            CollectorConfig::COLLECTOR_STORAGE_KEY,
            CollectorConfig::COLLECTOR_SEARCH_KEY
        );

        $this->criteriaBuilder->sql($sql);
    }

}
