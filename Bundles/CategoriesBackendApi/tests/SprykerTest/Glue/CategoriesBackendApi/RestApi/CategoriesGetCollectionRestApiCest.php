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
 * @group CategoriesGetCollectionRestApiCest
 * Add your own group annotations below this line
 */
class CategoriesGetCollectionRestApiCest
{
    /**
     * @param \SprykerTest\Glue\CategoriesBackendApi\CategoriesBackendApiTester $I
     *
     * @return void
     */
    public function testCategoryGetReturnsPaginatedResults(CategoriesBackendApiTester $I): void
    {
        // Arrange
        $I->addJsonApiResourcePlugin(new CategoriesBackendApiResource());

        $I->haveCategory();
        $I->haveCategory();
        $I->haveCategory();
        $I->haveCategory();

        $url = $I->buildCategoriesUrl();

        $queryParams = http_build_query([
            'page' => [
                'offset' => 1,
                'limit' => 2,
            ],
        ]);
        $url .= '?' . $queryParams;

        // Act
        $I->sendGet($url);

        // Assert
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseIsJson();

        $I->seeResponseJsonContainsDataCount(2);
    }
}
