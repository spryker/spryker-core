<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductSetPageSearch\Business\ProductSetPageSearchBusinessFactory getFactory()
 */
class ProductSetPageSearchFacade extends AbstractFacade implements ProductSetPageSearchFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productSetIds
     *
     * @return void
     */
    public function publish(array $productSetIds)
    {
        $this->getFactory()->createProductSetPageSearchWriter()->publish($productSetIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productSetIds
     *
     * @return void
     */
    public function unpublish(array $productSetIds)
    {
        $this->getFactory()->createProductSetPageSearchWriter()->unpublish($productSetIds);
    }

    /**
     * @api
     *
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapProductSetDataToSearchData(array $data, LocaleTransfer $localeTransfer): array
    {
        return $this->getFactory()->createProductSetSearchDataMapper()->mapProductSetDataToSearchData($data, $localeTransfer);
    }
}
