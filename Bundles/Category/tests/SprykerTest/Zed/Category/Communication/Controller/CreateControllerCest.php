<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Communication\Controller;

use SprykerTest\Zed\Category\CategoryCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Communication
 * @group Controller
 * @group CreateControllerCest
 * Add your own group annotations below this line
 */
class CreateControllerCest
{
    /**
     * @param \SprykerTest\Zed\Category\CategoryCommunicationTester $i
     *
     * @return void
     */
    public function openCreatePage(CategoryCommunicationTester $i)
    {
        $i->amOnPage('/category/create');
        $i->seeResponseCodeIs(200);
        $i->see('Create category', 'h5');
    }

    /**
     * @param \SprykerTest\Zed\Category\CategoryCommunicationTester $i
     *
     * @return void
     */
    public function createCategoryWithAlreadyExistingKeyShowsValidationMessage(CategoryCommunicationTester $i)
    {
        $categoryTransfer = $i->haveCategory();

        $formData = [
            'category' => [
                'category_key' => $categoryTransfer->getCategoryKey(),
            ],
        ];

        $i->amOnPage('/category/create');
        $i->submitForm(['name' => 'category'], $formData);

        $message = sprintf('Category with key "%s" already in use, please choose another one.', $categoryTransfer->getCategoryKey());

        $i->see($message);
    }
}
