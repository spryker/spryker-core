<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\FactFinder\Business\FactFinderBusinessFactory getFactory()
 */
class FactFinderFacade extends AbstractFacade implements FactFinderFacadeInterface
{

    /**
     * Specification:
     * - search request
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FfSearchResponseTransfer
     */
    public function search(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()
            ->createSearchRequest()
            ->request($quoteTransfer);
    }

    /**
     * @param string $locale
     * @param string $type
     *
     * @return mixed
     */
    public function getFactFinderCsv($locale, $type)
    {
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName($locale);
        
        return file_get_contents(
            $this->getFactory()
                ->getCollectorFacade()
                ->getCsvFileName($type, $localeTransfer)
        );
    }


}
