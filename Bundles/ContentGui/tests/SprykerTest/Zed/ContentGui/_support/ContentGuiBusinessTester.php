<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentGui;

use Codeception\Actor;
use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\LocalizedContentTransfer;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 *
 * @method \Spryker\Zed\ContentGui\Business\ContentGuiFacade getFacade()
 */
class ContentGuiBusinessTester extends Actor
{
    use _generated\ContentGuiBusinessTesterActions;

    /**
     * @param string|null $key
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function createBannerContentItem(?string $key = null): ContentTransfer
    {
        $data = [
            ContentTransfer::CONTENT_TERM_KEY => 'Banner',
            ContentTransfer::CONTENT_TYPE_KEY => 'Banner',
            ContentTransfer::DESCRIPTION => 'Test Banner',
            ContentTransfer::NAME => 'Test Banner',
            ContentTransfer::KEY => $key ?: 'br-test',
            ContentTransfer::LOCALIZED_CONTENTS => [
                [
                    LocalizedContentTransfer::PARAMETERS => '{}',
                ],
            ],
        ];

        return $this->haveContent($data);
    }

    /**
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function createAbstractProductListContentItem(): ContentTransfer
    {
        $data = [
            ContentTransfer::CONTENT_TERM_KEY => 'Abstract Product List',
            ContentTransfer::CONTENT_TYPE_KEY => 'Abstract Product List',
            ContentTransfer::DESCRIPTION => 'Test Product List',
            ContentTransfer::NAME => 'Test Product List',
            ContentTransfer::KEY => 'apl-test',
            ContentTransfer::LOCALIZED_CONTENTS => [
                [
                    LocalizedContentTransfer::PARAMETERS => '{}',
                ],
            ],
        ];

        return $this->haveContent($data);
    }
}
