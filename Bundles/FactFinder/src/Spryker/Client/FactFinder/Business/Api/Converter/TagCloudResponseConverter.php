<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Converter;

use FACTFinder\Adapter\TagCloud as FactFinderTagCloudAdapter;
use Generated\Shared\Transfer\FactFinderTagCloudResponseTransfer;

class TagCloudResponseConverter extends BaseConverter
{

    /**
     * @var \FACTFinder\Adapter\TagCloud
     */
    protected $tagCloudAdapter;

    /**
     * @param \FACTFinder\Adapter\TagCloud $tagCloudAdapter
     */
    public function __construct(FactFinderTagCloudAdapter $tagCloudAdapter)
    {
        $this->tagCloudAdapter = $tagCloudAdapter;
    }

    /**
     * @return \Generated\Shared\Transfer\FactFinderTagCloudResponseTransfer
     */
    public function convert()
    {
        $responseTransfer = new FactFinderTagCloudResponseTransfer();

        return $responseTransfer;
    }

}
