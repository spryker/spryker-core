<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\ProductSearch\Zed\FilterPreferences\Tester;

use Acceptance\ProductSearch\Zed\FilterPreferences\PageObject\FilterPreferencesPage;
use ProductSearch\ZedAcceptanceTester;

class FilterPreferencesTester extends ZedAcceptanceTester
{

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

        $this->canSee('Filter successfully deleted.');
    }

}
