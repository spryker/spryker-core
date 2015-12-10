<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Everon\Component\CriteriaBuilder\CriteriaBuilderInterface;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use SprykerFeature\Zed\Collector\Business\Exporter\Exception\DependencyException;
use SprykerFeature\Zed\Collector\Persistence\Exporter\AbstractPdoCollectorQuery;
use SprykerFeature\Zed\Distributor\Business\Distributor\BatchIteratorInterface;

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
     * @return BatchIteratorInterface
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
        $touchParameters = $this->getTouchQueryParameters($touchQuery);
        $this->criteriaBuilder
            ->setExtraParameterCollection($touchParameters);

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
            static::COLLECTOR_TOUCH_ID,
            static::COLLECTOR_RESOURCE_ID,
            static::COLLECTOR_STORAGE_KEY_ID,
            static::COLLECTOR_SEARCH_KEY_ID
        );

        $this->criteriaBuilder->sql($sql);
    }

}
