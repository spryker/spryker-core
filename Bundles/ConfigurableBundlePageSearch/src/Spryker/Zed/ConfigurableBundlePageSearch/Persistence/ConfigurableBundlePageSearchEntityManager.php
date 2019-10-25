<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Persistence;

use Generated\Shared\Transfer\ConfigurableBundlePageSearchTransfer;
use Orm\Zed\ConfigurableBundlePageSearch\Persistence\SpyConfigurableBundlePageSearch;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchPersistenceFactory getFactory()
 */
class ConfigurableBundlePageSearchEntityManager extends AbstractEntityManager implements ConfigurableBundlePageSearchEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundlePageSearchTransfer $configurableBundlePageSearchTransfer
     *
     * @return void
     */
    public function createConfigurableBundlePageSearch(ConfigurableBundlePageSearchTransfer $configurableBundlePageSearchTransfer): void
    {
        $configurableBundlePageSearchEntity = new SpyConfigurableBundlePageSearch();
        $configurableBundlePageSearchEntity->fromArray($configurableBundlePageSearchTransfer->toArray());

        $configurableBundlePageSearchEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundlePageSearchTransfer $configurableBundlePageSearchTransfer
     *
     * @return void
     */
    public function updateConfigurableBundlePageSearch(ConfigurableBundlePageSearchTransfer $configurableBundlePageSearchTransfer): void
    {
        $configurableBundlePageSearchEntity = $this->getFactory()
            ->createConfigurableBundlePageSearchQuery()
            ->findOneByIdProductSetPageSearch($configurableBundlePageSearchTransfer->getIdConfigurableBundlePageSearch());

        if (!$configurableBundlePageSearchEntity) {
            return;
        }

        $configurableBundlePageSearchEntity->fromArray($configurableBundlePageSearchTransfer->toArray());
        $configurableBundlePageSearchEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundlePageSearchTransfer $configurableBundlePageSearchTransfer
     *
     * @return void
     */
    public function deleteConfigurableBundlePageSearch(ConfigurableBundlePageSearchTransfer $configurableBundlePageSearchTransfer): void
    {
        $configurableBundlePageSearchEntity = $this->getFactory()
            ->createConfigurableBundlePageSearchQuery()
            ->findOneByIdProductSetPageSearch($configurableBundlePageSearchTransfer->getIdConfigurableBundlePageSearch());

        if (!$configurableBundlePageSearchEntity) {
            return;
        }

        $configurableBundlePageSearchEntity->delete();
    }
}
