<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CategoriesBackendApi;

use Generated\Shared\Transfer\ApiCategoryAttributesTransfer;
use Generated\Shared\Transfer\ApiCategoryParentTransfer;
use SprykerTest\Glue\Testify\Tester\ApiEndToEndTester;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class CategoriesBackendApiTester extends ApiEndToEndTester
{
    use _generated\CategoriesBackendApiTesterActions;

    /**
     * @var string
     */
    public const PATH_DATA_ATTRIBUTES_LOCALIZED_ATTRIBUTES_LOCALE = '$.data.attributes.localizedAttributes[?(@.locale == "%s")]';

    /**
     * @param string $identifier
     *
     * @return void
     */
    public function seeResponseJsonContainsCategoryKey(string $identifier): void
    {
        $this->seeResponseJsonPathContains(['data' => ['type' => 'categories', 'id' => $identifier, 'attributes' => ['categoryKey' => $identifier]]]);
    }

    /**
     * @return void
     */
    public function seeResponseJsonContainsCategory(): void
    {
        $this->seeResponseJsonPathContains(['data' => ['type' => 'categories']]);
    }

    /**
     * @param int $categoryId
     * @param string $uuid
     *
     * @return void
     */
    public function seeResponseJsonContainsCategoryIdAndUuid(int $categoryId, string $uuid): void
    {
        $this->seeResponseJsonPathContains(['data' => ['type' => 'categories', 'id' => $categoryId, 'attributes' => ['idCategory' => $categoryId, 'uuid' => $uuid]]]);
    }

    /**
     * @param array $localizedAttribute
     * @param string $locale
     *
     * @return void
     */
    public function seeResponseJsonContainsLocalizedAttribute(array $localizedAttribute, string $locale): void
    {
        $this->seeResponseJsonPathContains($localizedAttribute, sprintf(static::PATH_DATA_ATTRIBUTES_LOCALIZED_ATTRIBUTES_LOCALE, $locale));
    }

    /**
     * @param array<string> $storeNames
     *
     * @return void
     */
    public function seeResponseJsonContainsStoreRelations(array $storeNames): void
    {
        $this->seeResponseJsonPathContains($storeNames, '$.data.attributes.stores');
    }

    /**
     * @param int $int
     *
     * @return void
     */
    public function seeResponseJsonContainsDataCount(int $int): void
    {
        $this->seeResponseMatchesJsonPath('$.data[' . $int - 1 . ']');
        $this->dontSeeResponseMatchesJsonPath('$.data[' . $int . ']');
    }

    /**
     * @param string $parentCategoryKey
     * @param int $sortOrder
     *
     * @return void
     */
    public function seeResponseJsonHasCategoryParent(string $parentCategoryKey, int $sortOrder): void
    {
        $this->seeResponseJsonPathContains([
            ApiCategoryAttributesTransfer::PARENT => [
                ApiCategoryParentTransfer::CATEGORY_KEY => $parentCategoryKey,
                ApiCategoryParentTransfer::SORT_ORDER => $sortOrder,
            ],
        ], '$.data.attributes');
    }
}
