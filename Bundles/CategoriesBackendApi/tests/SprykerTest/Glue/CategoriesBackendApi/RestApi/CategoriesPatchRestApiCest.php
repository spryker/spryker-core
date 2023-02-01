<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CategoriesBackendApi\RestApi;

use Generated\Shared\Transfer\ApiCategoryAttributesTransfer;
use Generated\Shared\Transfer\ApiCategoryParentTransfer;
use Generated\Shared\Transfer\StoreTransfer;
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
 * @group CategoriesPatchRestApiCest
 * Add your own group annotations below this line
 */
class CategoriesPatchRestApiCest
{
    /**
     * @var string
     */
    public const STORE_NAME_DE = 'DE';

    /**
     * @param \SprykerTest\Glue\CategoriesBackendApi\CategoriesBackendApiTester $I
     *
     * @return void
     */
    public function requestCategoryPatchRootCategoryReturnsHttpResponseCode200(CategoriesBackendApiTester $I): void
    {
        // Arrange
        $I->addJsonApiResourcePlugin(new CategoriesBackendApiResource());

        $categoryTransfer = $I->haveCategory();
        $categoryTransfer->setIsActive(false);

        $url = $I->buildCategoriesUrl($categoryTransfer->getCategoryKey());

        $categoryData = $categoryTransfer->toArray();

        // Act
        $I->sendPatch($url, [
            'data' => [
                'attributes' => $categoryData,
            ],
        ]);

        // Assert
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseIsJson();
        $I->seeResponseJsonContainsCategory();
    }

    /**
     * @param \SprykerTest\Glue\CategoriesBackendApi\CategoriesBackendApiTester $I
     *
     * @return void
     */
    public function requestCategoryPatchWithLocalizedAttributesKeepsUnspecifiedFields(CategoriesBackendApiTester $I): void
    {
        // Arrange
        $I->addJsonApiResourcePlugin(new CategoriesBackendApiResource());

        $categoryTransfer = $I->haveLocalizedCategory();
        $url = $I->buildCategoriesUrl($categoryTransfer->getCategoryKey());

        /** @var \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $localizedAttribute */
        $localizedAttribute = $categoryTransfer->getLocalizedAttributes()->getIterator()->current();
        $localeName = $localizedAttribute->getLocaleOrFail()->getLocaleNameOrFail();

        $requestPayload = [
            'localizedAttributes' => [
                [
                    'locale' => $localeName,
                    'metaTitle' => 'metaTitle is an optional field',
                ],
            ],
        ];

        // Act
        $I->sendPatch($url, [
            'data' => [
                'attributes' => $requestPayload,
            ],
        ]);

        // Assert
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseIsJson();
        $I->seeResponseJsonContainsCategory();
        $I->seeResponseJsonContainsLocalizedAttribute([
            'metaTitle' => 'metaTitle is an optional field',
            'name' => $localizedAttribute->getNameOrFail(),
        ], $localeName);
    }

    /**
     * @param \SprykerTest\Glue\CategoriesBackendApi\CategoriesBackendApiTester $I
     *
     * @return void
     */
    public function requestCategoryPatchCanSpecifyNewLocalizedAttributesForNewLocales(CategoriesBackendApiTester $I): void
    {
        // Arrange
        $I->addJsonApiResourcePlugin(new CategoriesBackendApiResource());

        $categoryTransfer = $I->haveLocalizedCategory();
        $url = $I->buildCategoriesUrl($categoryTransfer->getCategoryKey());

        $locales = $I->getLocator()->locale()->facade()->getLocaleCollection();
        if (count($locales) < 2) {
            $I->markTestSkipped('Needs more than one active locale to work');
        }

        /** @var \Generated\Shared\Transfer\LocaleTransfer $primaryLocale */
        /** @var \Generated\Shared\Transfer\LocaleTransfer $secondaryLocale */
        [$primaryLocale, $secondaryLocale] = array_values($locales);

        $primaryLocaleLocalizedAttribute = $I->haveApiCategoryLocalizedAttributeTransfer([
            'locale' => $primaryLocale->getLocaleNameOrFail(),
        ])->toArray(true, true);

        $secondaryLocaleLocalizedAttribute = $I->haveApiCategoryLocalizedAttributeTransfer([
            'locale' => $secondaryLocale->getLocaleNameOrFail(),
        ])->toArray(true, true);

        $requestPayload = [
            'localizedAttributes' => [
                $primaryLocaleLocalizedAttribute,
                $secondaryLocaleLocalizedAttribute,
            ],
        ];

        // Act
        $I->sendPatch($url, [
            'data' => [
                'attributes' => $requestPayload,
            ],
        ]);

        // Assert
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseIsJson();
        $I->seeResponseJsonContainsCategory();

        $I->seeResponseJsonContainsLocalizedAttribute($primaryLocaleLocalizedAttribute, $primaryLocale->getLocaleNameOrFail());
        $I->seeResponseJsonContainsLocalizedAttribute($secondaryLocaleLocalizedAttribute, $secondaryLocale->getLocaleNameOrFail());
    }

    /**
     * @param \SprykerTest\Glue\CategoriesBackendApi\CategoriesBackendApiTester $I
     *
     * @return void
     */
    public function requestCategoryPatchCanAddCategoryToNewStore(CategoriesBackendApiTester $I): void
    {
        $I->havePluginForSavingCategoryStoreRelations();

        // Arrange
        $I->addJsonApiResourcePlugin(new CategoriesBackendApiResource());

        $categoryTransfer = $I->haveCategory();
        $url = $I->buildCategoriesUrl($categoryTransfer->getCategoryKey());

        $store = $I->haveStore([
            StoreTransfer::NAME => static::STORE_NAME_DE,
        ]);

        $requestPayload = [
            'stores' => [
                $store->getNameOrFail(),
            ],
        ];

        // Act
        $I->sendPatch($url, [
            'data' => [
                'attributes' => $requestPayload,
            ],
        ]);

        // Assert
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseIsJson();
        $I->seeResponseJsonContainsStoreRelations([
            $store->getNameOrFail(),
        ]);
    }

    /**
     * @param \SprykerTest\Glue\CategoriesBackendApi\CategoriesBackendApiTester $I
     *
     * @return void
     */
    public function testCategoryPatchRequestCanMoveCategoryToNewParent(CategoriesBackendApiTester $I): void
    {
        // Arrange
        $I->addJsonApiResourcePlugin(new CategoriesBackendApiResource());

        $oldParentCategory = $I->haveCategory();
        $newParentCategory = $I->haveCategory();

        $categoryTransfer = $I->haveCategory([
            'parentCategoryNode' => $oldParentCategory->getCategoryNodeOrFail(),
        ]);

        $url = $I->buildCategoriesUrl($categoryTransfer->getCategoryKey());

        $sortOrder = 1;

        $requestPayload = [
            ApiCategoryAttributesTransfer::PARENT => [
                ApiCategoryParentTransfer::CATEGORY_KEY => $newParentCategory->getCategoryKeyOrFail(),
                ApiCategoryParentTransfer::SORT_ORDER => $sortOrder,
            ],
        ];

        // Act
        $I->sendPatch($url, [
            'data' => [
                'attributes' => $requestPayload,
            ],
        ]);

        // Assert
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseIsJson();
        $I->seeResponseJsonHasCategoryParent($newParentCategory->getCategoryKeyOrFail(), $sortOrder);
    }
}
