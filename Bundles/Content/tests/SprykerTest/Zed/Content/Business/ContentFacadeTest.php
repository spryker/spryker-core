<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Content\Business;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\ContentBuilder;
use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\LocalizedContentTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Content
 * @group Business
 * @group Facade
 * @group ContentFacadeTest
 * Add your own group annotations below this line
 */
class ContentFacadeTest extends Test
{
    private const NAME = 'New name';
    private const PARAMETERS = '{"sku"}';
    private const DESCRIPTION = 'Test description';
    protected const KEY = 'name-1';

    /**
     * @var \SprykerTest\Zed\Content\ContentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindContentById(): void
    {
        $contentTransfer = $this->tester->haveContent();
        $foundContentTransfer = $this->tester->getFacade()->findContentById($contentTransfer->getIdContent());

        $this->assertNotNull($foundContentTransfer->getIdContent());
    }

    /**
     * @return void
     */
    public function testFindContentByKey(): void
    {
        $contentTransfer = $this->tester->haveContent();
        $foundContentTransfer = $this->tester->getFacade()->findContentByKey($contentTransfer->getKey());

        $this->assertNotNull($foundContentTransfer->getIdContent());
    }

    /**
     * @return void
     */
    public function testCreate(): void
    {
        $contentTransfer = (new ContentBuilder(
            [
                ContentTransfer::LOCALIZED_CONTENTS => [
                    [
                        LocalizedContentTransfer::PARAMETERS => '{}',
                    ],
                ],
                ContentTransfer::KEY => static::KEY,
            ]
        ))->build();
        $createdContentTransfer = $this->tester->getFacade()->create($contentTransfer);

        $this->assertNotNull($createdContentTransfer->getIdContent());
    }

    /**
     * @return void
     */
    public function testCreateContentWithProvidedKeyHasSameKey(): void
    {
        $contentTransfer = (new ContentBuilder(
            [
                ContentTransfer::LOCALIZED_CONTENTS => [
                    [
                        LocalizedContentTransfer::PARAMETERS => '{}',
                    ],
                ],
                ContentTransfer::KEY => static::KEY,
            ]
        ))->build();
        $createdContentTransfer = $this->tester->getFacade()->create($contentTransfer);

        $this->assertEquals($createdContentTransfer->getKey(), static::KEY);
    }

    /**
     * @return void
     */
    public function testCreateContentWithEmptyKeyGeneratesKey(): void
    {
        $contentTransfer = (new ContentBuilder(
            [
                ContentTransfer::LOCALIZED_CONTENTS => [
                    [
                        LocalizedContentTransfer::PARAMETERS => '{}',
                    ],
                ],
            ]
        ))->build();
        $createdContentTransfer = $this->tester->getFacade()->create($contentTransfer);

        $this->assertNotNull($createdContentTransfer->getKey());
    }

    /**
     * @return void
     */
    public function testUpdate(): void
    {
        $contentTransfer = $this->tester->haveContent();

        $contentTransfer->setName(static::NAME);
        $contentTransfer->getLocalizedContents()[0]->setParameters(static::PARAMETERS);

        $this->tester->getFacade()->update($contentTransfer);

        $updatedContentTransfer = $this->tester->getFacade()->findContentById($contentTransfer->getIdContent());

        $this->assertEquals($contentTransfer->getName(), $updatedContentTransfer->getName());
        $this->assertEquals(
            $contentTransfer->getLocalizedContents()[0]->getParameters(),
            $updatedContentTransfer->getLocalizedContents()[0]->getParameters()
        );
    }

    /**
     * @return void
     */
    public function testValidateSuccess(): void
    {
        $contentTransfer = new ContentTransfer();
        $contentTransfer->setName(static::NAME);
        $contentTransfer->setDescription(static::DESCRIPTION);
        $contentTransfer->setKey(static::KEY);

        $validationResponse = $this->tester->getFacade()->validateContent($contentTransfer);

        $this->assertTrue($validationResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateFailsOnEmptyName(): void
    {
        $contentTransfer = new ContentTransfer();
        $contentTransfer->setName('');
        $contentTransfer->setDescription(static::DESCRIPTION);
        $contentTransfer->setKey(static::KEY);

        $validationResponse = $this->tester->getFacade()->validateContent($contentTransfer);

        $this->assertFalse($validationResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateFailsOnVeryLongDescription(): void
    {
        $contentTransfer = new ContentTransfer();
        $contentTransfer->setName(static::NAME);
        $contentTransfer->setDescription(str_repeat(static::DESCRIPTION, 100));
        $contentTransfer->setKey(static::KEY);

        $validationResponse = $this->tester->getFacade()->validateContent($contentTransfer);

        $this->assertFalse($validationResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateFailsOnIncorrectlyFormattedKey(): void
    {
        $contentTransfer = new ContentTransfer();
        $contentTransfer->setName(static::NAME);
        $contentTransfer->setDescription(static::DESCRIPTION);
        $contentTransfer->setKey('Wrong-key');

        $validationResponse = $this->tester->getFacade()->validateContent($contentTransfer);

        $this->assertFalse($validationResponse->getIsSuccess());
    }
}
