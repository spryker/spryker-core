<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentNavigationDataImport;

use Codeception\Actor;
use Orm\Zed\Content\Persistence\SpyContentLocalizedQuery;
use Orm\Zed\Content\Persistence\SpyContentQuery;
use Orm\Zed\Navigation\Persistence\SpyNavigationQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ContentNavigationDataImportCommunicationTester extends Actor
{
    use _generated\ContentNavigationDataImportCommunicationTesterActions;

    /**
     * @return int
     */
    public function getContentTableCount(): int
    {
        return $this->getContentQuery()->count();
    }

    /**
     * @return void
     */
    public function ensureContentTablesAreEmpty(): void
    {
        $this->getContentLocalizedQuery()->deleteAll();
        $this->getContentQuery()->deleteAll();
    }

    /**
     * @return void
     */
    public function ensureNavigationTableIsEmpty(): void
    {
        $this->getNavigationQuery()->deleteAll();
    }

    /**
     * @return \Orm\Zed\Content\Persistence\SpyContentQuery
     */
    protected function getContentQuery(): SpyContentQuery
    {
        return SpyContentQuery::create();
    }

    /**
     * @return \Orm\Zed\Content\Persistence\SpyContentLocalizedQuery
     */
    protected function getContentLocalizedQuery(): SpyContentLocalizedQuery
    {
        return SpyContentLocalizedQuery::create();
    }

    /**
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery
     */
    protected function getNavigationQuery(): SpyNavigationQuery
    {
        return SpyNavigationQuery::create();
    }
}
