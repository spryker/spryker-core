<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearch;

use Codeception\Actor;
use Codeception\Scenario;
use SprykerTest\Zed\ProductSearch\PageObject\FilterPreferencesPage;
use SprykerTest\Zed\ProductSearch\PageObject\SearchPreferencesPage;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductSearchPresentationTester extends Actor
{
    use _generated\ProductSearchPresentationTesterActions;

    /**
     * @param \Codeception\Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);

        $this->amZed();
        $this->amLoggedInUser();
    }

    /**
     * @param string $filterName
     *
     * @return int
     */
    public function createFilter($filterName)
    {
        $this->amOnPage(FilterPreferencesPage::URL_CREATE);

        $this->fillField(FilterPreferencesPage::SELECTOR_ATTRIBUTE_KEY, $filterName);
        $this->selectOption(FilterPreferencesPage::SELECTOR_INPUT_FILTER_TYPE, 'multi-select');
        $this->fillField(FilterPreferencesPage::SELECTOR_INPUT_ATTRIBUTE_NAME_TRANSLATION, $filterName . ' name');
        $this->click(FilterPreferencesPage::SELECTOR_COPY_ATTRIBUTE_NAME_TRANSLATION_BUTTON); // copy translation to all languages

        $this->click(FilterPreferencesPage::SELECTOR_ATTRIBUTE_FORM_SUBMIT);

        $regexp = '/' . preg_quote(FilterPreferencesPage::URL_VIEW, '/') . '(\d+)/';
        $this->canSeeCurrentUrlMatches($regexp);

        return $this->grabFromCurrentUrl($regexp);
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function updateFilter($id)
    {
        $this->amOnPage(FilterPreferencesPage::URL_VIEW . $id);

        $this->click(FilterPreferencesPage::SELECTOR_BUTTON_EDIT);

        $this->canSeeCurrentUrlMatches('/' . preg_quote(FilterPreferencesPage::URL_EDIT, '/') . '(\d+)/');

        $this->selectOption(FilterPreferencesPage::SELECTOR_INPUT_FILTER_TYPE, 'single-select');

        $this->click(FilterPreferencesPage::SELECTOR_ATTRIBUTE_FORM_SUBMIT);

        $this->canSeeCurrentUrlMatches('/' . preg_quote(FilterPreferencesPage::URL_VIEW, '/') . '(\d+)/');
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function deleteFilter($id)
    {
        $this->amOnPage(FilterPreferencesPage::URL_VIEW . $id);

        $this->click(FilterPreferencesPage::SELECTOR_BUTTON_DELETE);

        $this->canSeeCurrentUrlEquals(FilterPreferencesPage::URL_LIST);

        $this->seeInPageSource('Filter successfully deleted.');
    }

    /**
     * @param string $attributeKey
     *
     * @return void
     */
    public function addAttributeToSearch($attributeKey)
    {
        $this->amOnPage(SearchPreferencesPage::URL_CREATE);

        $this->fillField(SearchPreferencesPage::SELECTOR_INPUT_SEARCH_PREFERENCES_KEY, $attributeKey);
        $this->selectOption(SearchPreferencesPage::SELECTOR_INPUT_SEARCH_PREFERENCES_FULL_TEXT, 'yes');
        $this->selectOption(SearchPreferencesPage::SELECTOR_INPUT_SEARCH_PREFERENCES_SUGGESTION_TERMS, 'yes');
        $this->selectOption(SearchPreferencesPage::SELECTOR_INPUT_SEARCH_PREFERENCES_COMPLETION_TERMS, 'yes');

        $this->click(SearchPreferencesPage::SELECTOR_SEARCH_PREFERENCES_SUBMIT);

        $this->canSeeCurrentUrlEquals(SearchPreferencesPage::URL_LIST);

        $this->seeInPageSource('Attribute to search was added successfully.');
    }

    /**
     * @param string $attributeKey
     *
     * @return void
     */
    public function updateAttributeToSearch($attributeKey)
    {
        $this->searchTableByAttributeKey($attributeKey);

        $this->click(SearchPreferencesPage::SELECTOR_FIRST_ROW_UPDATE);

        $this->canSeeCurrentUrlMatches('/' . preg_quote(SearchPreferencesPage::URL_EDIT, '/') . '(\d+)/');

        $this->selectOption(SearchPreferencesPage::SELECTOR_INPUT_SEARCH_PREFERENCES_FULL_TEXT, 'no');
        $this->selectOption(SearchPreferencesPage::SELECTOR_INPUT_SEARCH_PREFERENCES_FULL_TEXT_BOOSTED, 'yes');

        $this->click(SearchPreferencesPage::SELECTOR_SEARCH_PREFERENCES_SUBMIT);

        $this->canSeeCurrentUrlEquals(SearchPreferencesPage::URL_LIST);

        $this->seeInPageSource('Attribute to search was successfully updated.');
    }

    /**
     * @param string $attributeKey
     *
     * @return void
     */
    public function deactivateAttributeToSearch($attributeKey)
    {
        $this->searchTableByAttributeKey($attributeKey);

        $this->click(SearchPreferencesPage::SELECTOR_FIRST_ROW_DELETE);

        $this->canSeeCurrentUrlEquals(SearchPreferencesPage::URL_LIST);

        $this->seeInPageSource('Attribute to search was successfully deactivated.');
    }

    /**
     * @param string $attributeKey
     *
     * @return void
     */
    protected function searchTableByAttributeKey($attributeKey)
    {
        $this->amOnPage(SearchPreferencesPage::URL_LIST);
        $this->fillField(SearchPreferencesPage::SELECTOR_TABLE_SEARCH, $attributeKey);
        $this->wait(3);

        $this->canSee($attributeKey, SearchPreferencesPage::SELECTOR_TABLE_FIRST_CELL);
    }
}
