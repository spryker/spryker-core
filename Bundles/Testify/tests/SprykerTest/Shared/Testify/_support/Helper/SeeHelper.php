<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Module;
use PHPUnit\Framework\Assert;

class SeeHelper extends Module
{
    /**
     * @var bool
     */
    protected $isPresentationSuite = true;

    /**
     * @param array $settings
     *
     * @return void
     */
    public function _beforeSuite($settings = [])
    {
        $className = $settings['class_name'];
        if (preg_match('/CommunicationTester/', $className)) {
            $this->isPresentationSuite = false;
        }
    }

    /**
     * @param string $pattern
     * @param string $selector
     *
     * @return void
     */
    public function seeMatches($pattern, $selector)
    {
        Assert::assertRegExp($pattern, $this->grabMultipleAsText($selector));
    }

    /**
     * @param string $pattern
     * @param string $selector
     *
     * @return void
     */
    public function dontSeeMatches($pattern, $selector)
    {
        Assert::assertNotRegExp($pattern, $this->grabMultipleAsText($selector));
    }

    /**
     * Retrieves concatenated text of all matched nodes.
     *
     * @param string $selector
     *
     * @return string
     */
    protected function grabMultipleAsText($selector)
    {
        $nodes = $this->getDriver()->grabMultiple($selector);

        return implode('', $nodes);
    }

    /**
     * @return \Codeception\Module|\Codeception\Module\WebDriver|\Codeception\Lib\Framework
     */
    protected function getDriver()
    {
        if ($this->isPresentationSuite) {
            return $this->getModule('WebDriver');
        }

        return $this->getModule('\\' . ZedBootstrap::class);
    }
}
