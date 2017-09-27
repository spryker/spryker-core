<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Module;
use PHPUnit_Framework_Assert;

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
        $nodes = $this->getDriver()->grabMultiple($selector);
        PHPUnit_Framework_Assert::assertRegExp($pattern, implode('', $nodes));
    }

    /**
     * @param string $pattern
     * @param string $selector
     *
     * @return void
     */
    public function dontSeeMatches($pattern, $selector)
    {
        $nodes = $this->getDriver()->grabMultiple($selector);
        PHPUnit_Framework_Assert::assertNotRegExp($pattern, implode('', $nodes));
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
