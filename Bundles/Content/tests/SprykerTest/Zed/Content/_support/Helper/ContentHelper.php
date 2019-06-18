<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Content\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ContentBuilder;
use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\LocalizedContentTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ContentHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function haveContent(array $override = []): ContentTransfer
    {
        $data = [
            ContentTransfer::KEY => 'test-key',
            ContentTransfer::CONTENT_TERM_KEY => 'test-term',
            ContentTransfer::CONTENT_TYPE_KEY => 'test-type',
            ContentTransfer::DESCRIPTION => 'description',
            ContentTransfer::NAME => 'name',
            ContentTransfer::LOCALIZED_CONTENTS => [
                [
                    LocalizedContentTransfer::PARAMETERS => '{}',
                ],
            ],
        ];

        $contentTransfer = (new ContentBuilder(array_merge($data, $override)))->build();
        $contentTransfer = $this->getLocator()->content()->facade()->create($contentTransfer);

        return $contentTransfer;
    }
}
