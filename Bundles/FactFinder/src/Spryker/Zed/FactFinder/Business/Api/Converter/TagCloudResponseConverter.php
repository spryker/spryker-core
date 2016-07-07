<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Converter;

use FACTFinder\Adapter\TagCloud as FFTagCloudAdapter;
use Generated\Shared\Transfer\FFTagCloudResponseTransfer;

class TagCloudResponseConverter extends BaseConverter
{

    /**
     * @var \FACTFinder\Adapter\TagCloud
     */
    protected $tagCloudAdapter;

    /**
     * @param \FACTFinder\Adapter\TagCloud $tagCloudAdapter
     */
    public function __construct(FFTagCloudAdapter $tagCloudAdapter)
    {
        $this->tagCloudAdapter = $tagCloudAdapter;
    }

    /**
     * @return \Generated\Shared\Transfer\FFTagCloudResponseTransfer
     */
    public function convert()
    {
        $responseTransfer = new FFTagCloudResponseTransfer();
//        $responseTransfer->set();

        return $responseTransfer;
    }

}
