<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use SprykerTest\Zed\Category\CommunicationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Communication
 * @group Controller
 * @group EditControllerCest
 * Add your own group annotations below this line
 */
class EditControllerCest
{

    /**
     * @param \SprykerTest\Zed\Category\CommunicationTester $i
     *
     * @return void
     */
    public function updateCategoryWithAlreadyExistingKeyShowsValidationMessage(CommunicationTester $i)
    {
        $categoryTransferA = $i->haveCategory();
        $categoryTransferB = $i->haveCategory();

        $this->mockGlobalGet($categoryTransferB);

        $i->amOnPage('/category/edit?id-category=' . $categoryTransferB->getIdCategory());

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
    protected function mockGlobalGet(CategoryTransfer $categoryTransferB)
    {
        $_GET['id-category'] = $categoryTransferB->getIdCategory();
    }

}
