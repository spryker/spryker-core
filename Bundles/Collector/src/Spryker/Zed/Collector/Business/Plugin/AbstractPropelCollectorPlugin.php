<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Business\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\Collector\Business\Exporter\Exception\DependencyException;
use Spryker\Zed\Collector\Persistence\Exporter\AbstractPropelCollectorQuery;
use Spryker\Zed\Collector\Persistence\PropelBatchIterator;

abstract class AbstractPropelCollectorPlugin extends AbstractCollectorPlugin
{

    /**
     * @var \Spryker\Zed\Collector\Persistence\Exporter\AbstractPropelCollectorQuery
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
     * @return \Spryker\Zed\Collector\Business\Model\CountableIteratorInterface
     */
    protected function generateBatchIterator()
    {
        return new PropelBatchIterator($this->queryBuilder->getTouchQuery(), $this->chunkSize);
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
            ->setLocale($locale)
            ->prepare();
    }

}
