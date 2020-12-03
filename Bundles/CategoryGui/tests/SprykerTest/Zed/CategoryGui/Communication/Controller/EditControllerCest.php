<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryGui\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use SprykerTest\Zed\CategoryGui\CategoryCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryGui
 * @group Communication
 * @group Controller
 * @group EditControllerCest
 * Add your own group annotations below this line
 */
class EditControllerCest
{
    /**
     * @param \SprykerTest\Zed\CategoryGui\CategoryCommunicationTester $i
     *
     * @return void
     */
    public function updateCategoryWithAlreadyExistingKeyShowsValidationMessage(CategoryCommunicationTester $i): void
    {
        $categoryTransferA = $i->haveLocalizedCategory();
        $categoryTransferB = $i->haveLocalizedCategory();

        $this->mockGlobalGet($categoryTransferB);

        $i->amOnPage('/category-gui/edit?id-category=' . $categoryTransferB->getIdCategory());

        $formData = [
            'category' => [
                'category_key' => $categoryTransferA->getCategoryKey(),
            ],
        ];

        $i->submitForm(['name' => 'category'], $formData);

        $message = sprintf('Category with key "%s" already in use, please choose another one.', $categoryTransferA->getCategoryKey());

        $i->see($message);
    }

    /**
     * Needed to use Request::createFromGlobals()
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransferB
     *
     * @return void
     */
    protected function mockGlobalGet(CategoryTransfer $categoryTransferB): void
    {
        $_GET['id-category'] = $categoryTransferB->getIdCategory();
    }
}
