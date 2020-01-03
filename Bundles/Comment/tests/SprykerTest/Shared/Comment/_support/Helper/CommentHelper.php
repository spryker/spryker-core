<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Comment\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CommentRequestBuilder;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CommentHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function haveComment(array $seed = []): CommentThreadResponseTransfer
    {
        $commentRequestTransfer = (new CommentRequestBuilder($seed))->build();

        return $this->getLocator()->comment()->facade()->addComment($commentRequestTransfer);
    }
}
