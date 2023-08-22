<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CategoriesBackendApi\RestApi;

use Generated\Shared\Transfer\ApiCategoryLocalizedAttributeTransfer;
use Generated\Shared\Transfer\ApiCategoryParentTransfer;
use Generated\Shared\Transfer\CategoriesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
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
 * @group CategoriesPostRestApiCest
 * Add your own group annotations below this line
 */
class CategoriesPostRestApiCest
{
    /**
     * @param \SprykerTest\Glue\CategoriesBackendApi\CategoriesBackendApiTester $I
     *
     * @return void
     */
    public function requestCategoryPostToCreateARootCategoryReturnsHttpResponseCode201(CategoriesBackendApiTester $I): void
    {
        // Arrange
        $I->addJsonApiResourcePlugin(new CategoriesBackendApiResource());

        $categoryTransfer = $I->haveCategoryTransfer();

        $url = $I->buildCategoriesUrl();

        $categoryData = $categoryTransfer->toArray();
        $categoryData['parent'] = [
            'categoryKey' => null,
            'sortOrder' => 1,
        ];

        // Act
        $I->sendPost($url, [
            'data' => [
                'attributes' => $categoryData,
            ],
        ]);

        // Assert
        $I->seeResponseCodeIs(Response::HTTP_CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseJsonContainsCategory();
    }

    /**
     * @param \SprykerTest\Glue\CategoriesBackendApi\CategoriesBackendApiTester $I
     *
     * @return void
     */
    public function testCategoryPostAssignsToParentCategory(CategoriesBackendApiTester $I): void
    {
        // Arrange
        $I->addJsonApiResourcePlugin(new CategoriesBackendApiResource());

        $parent = $I->haveCategory();

        $categoryTransfer = $I->haveCategoryTransfer();

        $url = $I->buildCategoriesUrl();

        $sortOrder = 1;

        $requestPayload = $categoryTransfer->toArray();
        $requestPayload[CategoriesBackendApiAttributesTransfer::PARENT] = [
            ApiCategoryParentTransfer::CATEGORY_KEY => $parent->getCategoryKeyOrFail(),
            ApiCategoryParentTransfer::SORT_ORDER => $sortOrder,
        ];

        // Act
        $I->sendPost($url, [
            'data' => [
                'attributes' => $requestPayload,
            ],
        ]);

        // Assert
        $I->seeResponseCodeIs(Response::HTTP_CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseJsonHasCategoryParent($parent->getCategoryKeyOrFail(), $sortOrder);
    }

    /**
     * @param \SprykerTest\Glue\CategoriesBackendApi\CategoriesBackendApiTester $I
     *
     * @return void
     */
    public function testCategoryPostSavesLocalizedAttribute(CategoriesBackendApiTester $I): void
    {
        // Arrange
        $I->addJsonApiResourcePlugin(new CategoriesBackendApiResource());

        $categoryTransfer = $I->haveLocalizedCategoryTransfer();

        $url = $I->buildCategoriesUrl();

        /** @var \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $localizedAttribute */
        $localizedAttribute = $categoryTransfer->getLocalizedAttributes()->getIterator()->current();

        $apiLocalizedAttributeData = [
            ApiCategoryLocalizedAttributeTransfer::LOCALE => $localizedAttribute->getLocaleOrFail()->getLocaleNameOrFail(),
            ApiCategoryLocalizedAttributeTransfer::NAME => $localizedAttribute->getNameOrFail(),
        ];

        $requestPayload = $categoryTransfer->toArray();
        $requestPayload[CategoriesBackendApiAttributesTransfer::PARENT] = [
            ApiCategoryParentTransfer::CATEGORY_KEY => null,
            ApiCategoryParentTransfer::SORT_ORDER => 0,
        ];
        $requestPayload[CategoryTransfer::LOCALIZED_ATTRIBUTES] = [
            $apiLocalizedAttributeData,
        ];

        // Act
        $I->sendPost($url, [
            'data' => [
                'attributes' => $requestPayload,
            ],
        ]);

        // Assert
        $I->seeResponseCodeIs(Response::HTTP_CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseJsonContainsLocalizedAttribute(
            $apiLocalizedAttributeData,
            $localizedAttribute->getLocaleOrFail()->getLocaleNameOrFail(),
        );
    }
}
