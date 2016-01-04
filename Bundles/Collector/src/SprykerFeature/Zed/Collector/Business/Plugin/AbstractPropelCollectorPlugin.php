<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use SprykerFeature\Zed\Collector\Business\Exporter\Exception\DependencyException;
use SprykerFeature\Zed\Collector\Business\Model\CountableIteratorInterface;
use SprykerFeature\Zed\Collector\Persistence\Exporter\AbstractPropelCollectorQuery;
use SprykerFeature\Zed\Collector\Persistence\PropelBatchIterator;

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
     * @return CountableIteratorInterface
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
