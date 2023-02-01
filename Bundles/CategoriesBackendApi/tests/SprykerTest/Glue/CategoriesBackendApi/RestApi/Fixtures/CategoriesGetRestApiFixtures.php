<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CategoriesBackendApi\RestApi\Fixtures;

use Generated\Shared\Transfer\CategoryTransfer;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use SprykerTest\Glue\CategoriesBackendApi\CategoriesBackendApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

class CategoriesGetRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @var \Generated\Shared\Transfer\CategoryTransfer
     */
    protected $categoryTransfer;

    /**
     * @param \SprykerTest\Glue\CategoriesBackendApi\CategoriesBackendApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CategoriesBackendApiTester $I): FixturesContainerInterface
    {
        $this->categoryTransfer = $this->buildCategoryTransferFixture($I);

        return $this;
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function getCategoryTransfer(): CategoryTransfer
    {
        return $this->categoryTransfer;
    }

    /**
     * @param \SprykerTest\Glue\CategoriesBackendApi\CategoriesBackendApiTester $I
     *
     * @throws \RuntimeException
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function buildCategoryTransferFixture(CategoriesBackendApiTester $I): CategoryTransfer
    {
        $seed = ['uuid' => (Uuid::uuid4())->toString()];
        $categoryTransfer = $I->haveCategoryTransferPersisted($seed);

        if ($categoryTransfer === null) {
            throw new RuntimeException(sprintf('Unable to create fixture data %s', json_encode($seed, \JSON_THROW_ON_ERROR)));
        }

        return $categoryTransfer;
    }
}
