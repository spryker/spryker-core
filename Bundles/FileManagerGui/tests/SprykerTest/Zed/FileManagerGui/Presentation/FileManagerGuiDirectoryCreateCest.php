<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileManagerGui\Presentation;

use SprykerTest\Zed\FileManagerGui\FileManagerGuiPresentationTester;
use SprykerTest\Zed\FileManagerGui\PageObject\FileManagerGuiDirectoryCreatePage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group FileManagerGui
 * @group Presentation
 * @group FileManagerGuiDirectoryCreateCest
 * Add your own group annotations below this line
 */
class FileManagerGuiDirectoryCreateCest
{
    /**
     * @var string
     */
    protected const MESSAGE_ERROR_BLANK_VALUE = 'This value should not be blank.';

    /**
     * @var string
     */
    protected const MESSAGE_ERROR_MAX_LENGTH_VALUE = 'This value is too long.';

    /**
     * @var string
     */
    protected const MESSAGE_ERROR_WITH_SQL_QUERY = 'Unable to execute INSERT statement';

    /**
     * @var string
     */
    protected const MESSAGE_SUCCESS = 'The file directory was added successfully.';

    /**
     * @param \SprykerTest\Zed\FileManagerGui\FileManagerGuiPresentationTester $i
     * @param \SprykerTest\Zed\FileManagerGui\PageObject\FileManagerGuiDirectoryCreatePage $directoryCreatePage
     *
     * @return void
     */
    public function createDirectoryWithEmptyName(
        FileManagerGuiPresentationTester $i,
        FileManagerGuiDirectoryCreatePage $directoryCreatePage
    ): void {
        // Act
        $directoryCreatePage->createDirectory('');

        // Assert
        $i->see(
            static::MESSAGE_ERROR_BLANK_VALUE,
            FileManagerGuiDirectoryCreatePage::SELECTOR_FILE_DIRECTORY_NAME_ERROR_BLOCK,
        );

        foreach ($directoryCreatePage->getLocalizedTitleFieldIds() as $idLocalizedTitleField) {
            $i->see(
                static::MESSAGE_ERROR_BLANK_VALUE,
                $directoryCreatePage->getLocalizedTitleErrorBlockSelectorByIdLocalizedTitleField($idLocalizedTitleField),
            );
        }

        $i->dontSee(static::MESSAGE_ERROR_WITH_SQL_QUERY);
    }

    /**
     * @param \SprykerTest\Zed\FileManagerGui\FileManagerGuiPresentationTester $i
     * @param \SprykerTest\Zed\FileManagerGui\PageObject\FileManagerGuiDirectoryCreatePage $directoryCreatePage
     *
     * @return void
     */
    public function createDirectoryWithInvalidName(
        FileManagerGuiPresentationTester $i,
        FileManagerGuiDirectoryCreatePage $directoryCreatePage
    ): void {
        // Act
        $directoryCreatePage->createDirectory(
            $i->generateRandomString(FileManagerGuiDirectoryCreatePage::FIELD_FILE_DIRECTORY_NAME_MAX_LENGTH + 1),
            $i->generateRandomString(FileManagerGuiDirectoryCreatePage::FIELD_LOCALIZED_ATTRIBUTE_TITLE_MAX_LENGTH + 1),
        );

        // Assert
        $i->see(
            static::MESSAGE_ERROR_MAX_LENGTH_VALUE,
            FileManagerGuiDirectoryCreatePage::SELECTOR_FILE_DIRECTORY_NAME_ERROR_BLOCK,
        );

        foreach ($directoryCreatePage->getLocalizedTitleFieldIds() as $idLocalizedTitleField) {
            $i->see(
                static::MESSAGE_ERROR_MAX_LENGTH_VALUE,
                $directoryCreatePage->getLocalizedTitleErrorBlockSelectorByIdLocalizedTitleField($idLocalizedTitleField),
            );
        }

        $i->dontSee(static::MESSAGE_ERROR_WITH_SQL_QUERY);
    }

    /**
     * @param \SprykerTest\Zed\FileManagerGui\FileManagerGuiPresentationTester $i
     * @param \SprykerTest\Zed\FileManagerGui\PageObject\FileManagerGuiDirectoryCreatePage $directoryCreatePage
     *
     * @return void
     */
    public function createDirectoryWithValidName(
        FileManagerGuiPresentationTester $i,
        FileManagerGuiDirectoryCreatePage $directoryCreatePage
    ): void {
        // Act
        $directoryCreatePage->createDirectory(
            $i->generateRandomString(FileManagerGuiDirectoryCreatePage::FIELD_FILE_DIRECTORY_NAME_MAX_LENGTH),
            $i->generateRandomString(FileManagerGuiDirectoryCreatePage::FIELD_LOCALIZED_ATTRIBUTE_TITLE_MAX_LENGTH),
        );

        // Assert
        $i->see(static::MESSAGE_SUCCESS);
        $i->dontSee(static::MESSAGE_ERROR_WITH_SQL_QUERY);
    }
}
