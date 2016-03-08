<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Collector;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Shared\Library\BatchIterator\PropelBatchIterator;
use Spryker\Zed\Collector\Business\Exporter\Exception\DependencyException;
use Spryker\Zed\Collector\Persistence\Exporter\AbstractPropelCollectorQuery;

abstract class AbstractPropelCollector extends AbstractCollector
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
     * @return \Spryker\Shared\Library\BatchIterator\CountableIteratorInterface
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
