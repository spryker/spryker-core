<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileManagerGui\PageObject;

use SprykerTest\Zed\FileManagerGui\FileManagerGuiPresentationTester;

class FileManagerGuiDirectoryCreatePage
{
    /**
     * @uses \Spryker\Zed\FileManagerGui\Communication\Form\FileDirectoryForm::FIELD_NAME_MAX_LENGTH
     *
     * @var int
     */
    public const FIELD_FILE_DIRECTORY_NAME_MAX_LENGTH = 255;

    /**
     * @uses \Spryker\Zed\FileManagerGui\Communication\Form\FileDirectoryLocalizedAttributesForm::FIELD_TITLE_MAX_LENGTH
     *
     * @var int
     */
    public const FIELD_LOCALIZED_ATTRIBUTE_TITLE_MAX_LENGTH = 255;

    /**
     * @var string
     */
    public const SELECTOR_FILE_DIRECTORY_NAME_ERROR_BLOCK = '#file_directory_name+span.help-block';

    /**
     * @var string
     */
    protected const URL = '/file-manager-gui/add-directory';

    /**
     * @var string
     */
    protected const SELECTOR_FILE_DIRECTORY_NAME_FIELD = '#file_directory_name';

    /**
     * @var string
     */
    protected const SELECTOR_LOCALIZED_TITLES = 'div.ibox.collapsed input[type="text"]';

    /**
     * @var string
     */
    protected const SELECTOR_FORM_SUBMIT = 'form[name="file_directory"] input[type="submit"]';

    /**
     * @var \SprykerTest\Zed\FileManagerGui\FileManagerGuiPresentationTester
     */
    protected FileManagerGuiPresentationTester $tester;

    /**
     * @param \SprykerTest\Zed\FileManagerGui\FileManagerGuiPresentationTester $tester
     */
    public function __construct(FileManagerGuiPresentationTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * @param string $directoryName
     * @param string $localizedTitle
     *
     * @return void
     */
    public function createDirectory(string $directoryName, string $localizedTitle = ''): void
    {
        $i = $this->tester;

        $i->amZed();
        $i->amLoggedInUser();

        $i->amOnPage(static::URL);

        $i->waitForElementVisible(static::SELECTOR_FILE_DIRECTORY_NAME_FIELD);
        $i->fillField(static::SELECTOR_FILE_DIRECTORY_NAME_FIELD, $directoryName);

        $this->fillLocalizedTitles($localizedTitle);

        $i->click(static::SELECTOR_FORM_SUBMIT);
        $i->wait(20);
    }

    /**
     * @return list<string>
     */
    public function getLocalizedTitleFieldIds(): array
    {
        return $this->tester->grabMultiple(static::SELECTOR_LOCALIZED_TITLES, 'id');
    }

    /**
     * @param string $idLocalizedTitleField
     *
     * @return string
     */
    public function getLocalizedTitleErrorBlockSelectorByIdLocalizedTitleField(string $idLocalizedTitleField): string
    {
        return sprintf('#%s+span.help-block', $idLocalizedTitleField);
    }

    /**
     * @param string $localizedTitleData
     *
     * @return void
     */
    protected function fillLocalizedTitles(string $localizedTitleData): void
    {
        $i = $this->tester;

        foreach ($this->getLocalizedTitleFieldIds() as $idLocalizedTitleField) {
            $localizedTitleParentSelector = sprintf('//div[@class="ibox nested collapsed" and .//input[@id="%s"]]', $idLocalizedTitleField);
            $i->waitForElementVisible($localizedTitleParentSelector);
            $i->click($localizedTitleParentSelector);

            $localizedTitleSelector = sprintf('#%s', $idLocalizedTitleField);
            $i->waitForElementVisible($localizedTitleSelector);
            $i->fillField($localizedTitleSelector, $localizedTitleData);
        }
    }
}
