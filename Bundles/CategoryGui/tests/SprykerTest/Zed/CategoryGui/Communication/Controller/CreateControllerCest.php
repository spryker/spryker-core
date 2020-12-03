<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryGui\Communication\Controller;

use SprykerTest\Zed\CategoryGui\CategoryCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryGui
 * @group Communication
 * @group Controller
 * @group CreateControllerCest
 * Add your own group annotations below this line
 */
class CreateControllerCest
{
    /**
     * @param \SprykerTest\Zed\CategoryGui\CategoryCommunicationTester $i
     *
     * @return void
     */
    public function openCreatePage(CategoryCommunicationTester $i): void
    {
        $i->amOnPage('/category-gui/create');
        $i->seeResponseCodeIs(200);
        $i->see('Create category', 'h5');
    }

    /**
     * @param \SprykerTest\Zed\CategoryGui\CategoryCommunicationTester $i
     *
     * @return void
     */
    public function createCategoryWithAlreadyExistingKeyShowsValidationMessage(CategoryCommunicationTester $i): void
    {
        $categoryTransfer = $i->haveCategory();

        $formData = [
            'category' => [
                'category_key' => $categoryTransfer->getCategoryKey(),
            ],
        ];

        $i->amOnPage('/category-gui/create');
        $i->submitForm(['name' => 'category'], $formData);

        $message = sprintf('Category with key "%s" already in use, please choose another one.', $categoryTransfer->getCategoryKey());

        $i->see($message);
    }
}
