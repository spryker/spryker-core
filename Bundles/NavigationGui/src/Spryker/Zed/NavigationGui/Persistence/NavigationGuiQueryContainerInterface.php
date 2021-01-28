<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Persistence;

/**
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiPersistenceFactory getFactory()
 */
interface NavigationGuiQueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery
     */
    public function queryNavigation();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $searchText
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryCmsPageUrlSuggestions($searchText, $idLocale);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $searchText
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryCategoryNodeUrlSuggestions($searchText, $idLocale);
}
