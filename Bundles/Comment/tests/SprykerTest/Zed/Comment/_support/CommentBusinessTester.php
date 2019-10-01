<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Comment;

use Codeception\Actor;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\Comment\Business\CommentFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CommentBusinessTester extends Actor
{
    use _generated\CommentBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function createComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        return $this->haveComment([
            CommentRequestTransfer::OWNER_ID => $commentRequestTransfer->getOwnerId(),
            CommentRequestTransfer::OWNER_TYPE => $commentRequestTransfer->getOwnerType(),
            CommentRequestTransfer::COMMENT => $commentRequestTransfer->getComment(),
        ]);
    }
}
