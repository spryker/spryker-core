<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;
use Spryker\Zed\Collector\Business\Exporter\Exception\DependencyException;
use Spryker\Zed\Collector\Business\Exporter\Writer\KeyValue\TouchUpdaterSet;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Collector\Business\Exporter\Exception\DependencyException;
use Spryker\Zed\Collector\Persistence\Exporter\AbstractPropelCollectorQuery;

abstract class AbstractPropelCollectorPlugin extends AbstractCollectorPlugin
{

    /**
     * @var AbstractPropelCollectorQuery
     */
    protected $queryBuilder;

    /**
     * @return void
     */
    protected function validateDependencies()
    {
        parent::validateDependencies();
        if (!($this->queryBuilder instanceof AbstractPropelCollectorQuery)) {
            throw new DependencyException(sprintf('queryBuilder does not implement AbstractPropelCollectorQuery in %s', get_class($this)));
        }
    }

    /**
     * @return PropelBatchIterator
     */
    protected function generateBatchIterator()
    {
        return new PropelBatchIterator($this->queryBuilder->getTouchQuery(), $this->chunkSize);
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

        $this->queryBuilder->setTouchQuery($touchQuery);

        $this->queryBuilder
            ->setLocale($locale)
            ->prepare();
    }

}
