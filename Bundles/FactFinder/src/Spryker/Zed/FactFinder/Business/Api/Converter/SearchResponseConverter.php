<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Converter;

use FACTFinder\Adapter\Search as FFSearchAdapter;
use Generated\Shared\Transfer\FfSearchResponseTransfer;

class SearchResponseConverter extends BaseConverter
{

    /**
     * @var \FACTFinder\Adapter\Search
     */
    protected $searchAdapter;

    /**
     * @param \FACTFinder\Adapter\Search $searchAdapter
     */
    public function __construct(FFSearchAdapter $searchAdapter)
    {
        $this->searchAdapter = $searchAdapter;
    }

    /**
     * @return \Generated\Shared\Transfer\FfSearchResponseTransfer
     */
    public function convert()
    {
        $responseTransfer = new FfSearchResponseTransfer();
//        $responseTransfer->set();

        return $responseTransfer;
    }

}
