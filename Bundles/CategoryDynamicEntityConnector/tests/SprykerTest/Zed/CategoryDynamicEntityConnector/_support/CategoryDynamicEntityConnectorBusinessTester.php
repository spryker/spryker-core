<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryDynamicEntityConnector;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CategoryLocalizedAttributesBuilder;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\CategoryDynamicEntityConnector\Business\CategoryDynamicEntityConnectorFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(\SprykerTest\Zed\CategoryDynamicEntityConnector\PHPMD)
 */
class CategoryDynamicEntityConnectorBusinessTester extends Actor
{
    use _generated\CategoryDynamicEntityConnectorBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $idCategory
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer
     */
    public function createCategoryLocalizedAttributesForLocale(
        LocaleTransfer $localeTransfer,
        int $idCategory,
        array $seedData = []
    ): CategoryLocalizedAttributesTransfer {
        $categoryLocalizedAttributesData = (new CategoryLocalizedAttributesBuilder($seedData))->build()->toArray();
        $categoryLocalizedAttributesData[LocalizedAttributesTransfer::LOCALE] = $localeTransfer;

        return $this->haveCategoryLocalizedAttributeForCategory($idCategory, $categoryLocalizedAttributesData);
    }
}
