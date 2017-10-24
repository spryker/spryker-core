<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilValidate;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group UtilValidate
 * @group UtilValidateServiceTest
 * Add your own group annotations below this line
 */
class UtilValidateServiceTest extends Unit
{
    /**
     * @var \SprykerTest\Service\UtilValidate\UtilValidateServiceTester
     */
    protected $tester;

    /**
     * @var \Spryker\Service\UtilValidate\UtilValidateServiceInterface
     */
    protected $utilValidateService;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->utilValidateService = $this->tester->getLocator()->utilValidate()->service();
    }

    /**
     * @dataProvider invalidEmailFormats
     *
     * @param string $invalidEmail
     *
     * @return void
     */
    public function testIsEmailFormatValidReturnsFalseOnInvalidEmailFormat($invalidEmail)
    {
        // Assign
        $expectedResult = false;

        // Act
        $actualResult = $this->utilValidateService->isEmailFormatValid($invalidEmail);

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function invalidEmailFormats()
    {
        return [
            ['Abc.example.com'],
            ['A@b@c@example.com'],
            ['a\"b(c)d,e:f;g<h>i[j\k]l@example.com'],
            ['just\"not\"right@example.com'],
            ['this is"not\allowed@example.com'],
            ['this\ still\"not\\allowed@example.com'],
            ['john..doe@example.com'],
            ['john.doe@example..com'],
            ["te'<i>sting@twelvebeaufort.com"],
        ];
    }

    /**
     * @dataProvider validEmailFormats
     *
     * @param string $email
     *
     * @return void
     */
    public function testIsEmailFormatValidReturnsTrueOnValidEmailFormat($email)
    {
        // Assign
        $expectedResult = true;

        // Act
        $actualResult = $this->utilValidateService->isEmailFormatValid($email);

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function validEmailFormats()
    {
        return [
            ['some.one@example.com'],
        ];
    }
}
