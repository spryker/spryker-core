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
 * @group CategoriesGetRestApiCest
 * Add your own group annotations below this line
 */
class CategoriesGetRestApiCest
{
    /**
     * @param \SprykerTest\Glue\CategoriesBackendApi\CategoriesBackendApiTester $I
     *
     * @return void
     */
    public function requestCategoryGetReturnsHttpResponseCode200(CategoriesBackendApiTester $I): void
    {
        // Arrange
        $I->addJsonApiResourcePlugin(new CategoriesBackendApiResource());

        $categoryTransfer = $I->haveCategory();
        $identifier = $categoryTransfer->getCategoryKey();

        $url = $I->buildCategoriesUrl($identifier);

        // Act
        $I->sendGet($url);

        // Assert
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseIsJson();
        $I->seeResponseJsonContainsCategoryKey($identifier);
    }

    /**
     * @param \SprykerTest\Glue\CategoriesBackendApi\CategoriesBackendApiTester $I
     *
     * @return void
     */
    public function requestCategoryGetReturnsHttpResponseCode404(CategoriesBackendApiTester $I): void
    {
        // Arrange
        $I->addJsonApiResourcePlugin(new CategoriesBackendApiResource());

        $categoryTransfer = $I->haveCategoryTransfer();
        $identifier = $categoryTransfer->getCategoryKey();

        $url = $I->buildCategoriesUrl($identifier);

        // Act
        $I->sendGet($url);

        // Assert
        $I->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
