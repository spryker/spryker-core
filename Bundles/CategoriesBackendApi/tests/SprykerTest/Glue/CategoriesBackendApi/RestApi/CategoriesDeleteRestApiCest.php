<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CategoriesBackendApi\RestApi;

use Spryker\Glue\CategoriesBackendApi\Plugin\GlueApplication\CategoriesBackendApiResource;
use SprykerTest\Glue\CategoriesBackendApi\CategoriesBackendApiTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group CategoriesBackendApi
 * @group RestApi
 * @group CategoriesDeleteRestApiCest
 * Add your own group annotations below this line
 */
class CategoriesDeleteRestApiCest
{
    /**
     * @param \SprykerTest\Glue\CategoriesBackendApi\CategoriesBackendApiTester $I
     *
     * @return void
     */
    public function requestCategoryDeleteReturnsHttpResponseCode204(CategoriesBackendApiTester $I): void
    {
        // Arrange
        $I->addJsonApiResourcePlugin(new CategoriesBackendApiResource());

        $categoryTransfer = $I->haveCategory();
        $url = $I->buildCategoriesUrl($categoryTransfer->getCategoryKey());

        // Act
        $I->sendDelete($url);

        // Assert
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
    }

    /**
     * @param \SprykerTest\Glue\CategoriesBackendApi\CategoriesBackendApiTester $I
     *
     * @return void
     */
    public function requestCategoryDeleteReturnsHttpResponseCode404(CategoriesBackendApiTester $I): void
    {
        // Arrange
        $I->addJsonApiResourcePlugin(new CategoriesBackendApiResource());

        $categoryTransfer = $I->haveCategoryTransfer();
        $url = $I->buildCategoriesUrl($categoryTransfer->getCategoryKey());

        // Act
        $I->sendDelete($url);

        // Assert
        $I->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
    }
}
