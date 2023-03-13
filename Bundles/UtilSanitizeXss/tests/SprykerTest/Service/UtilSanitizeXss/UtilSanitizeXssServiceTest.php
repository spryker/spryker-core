<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\UtilSanitizeXss\tests\SprykerTest\Service\UtilSanitizeXss;

use Codeception\Test\Unit;
use Spryker\Service\UtilSanitizeXss\UtilSanitizeXssService;
use Spryker\Service\UtilSanitizeXss\UtilSanitizeXssServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group Spryker
 * @group UtilSanitizeXss
 * @group tests
 * @group SprykerTest
 * @group Service
 * @group UtilSanitizeXss
 * @group UtilSanitizeXssServiceTest
 * Add your own group annotations below this line
 */
class UtilSanitizeXssServiceTest extends Unit
{
    /**
     * @var string
     */
    protected const HTML_TAG_IFRAME = 'iframe';

    /**
     * @var string
     */
    protected const ATTRIBUTE_STYLE = 'style';

    /**
     * @return void
     */
    public function testSanitizeXssWillSanitizeScriptTag(): void
    {
        //Arrange
        $harmString = '<script>alert("Hack");</script>';

        //Act
        $result = $this->getUtilSanitizeXssService()->sanitizeXss($harmString);

        //Assert
        $this->assertSame('', $result);
    }

    /**
     * @return void
     */
    public function testSanitizeXssWillSanitizeScriptTagInsideAnotherTag(): void
    {
        //Arrange
        $harmString = '<span style="font-size: 36px;"><b>&lt;script&gt;alert("Hack");&lt;/script&gt;</b></span>';

        //Act
        $result = $this->getUtilSanitizeXssService()->sanitizeXss($harmString);

        //Assert
        $this->assertSame('<span ><b>alert&#40;"Hack"&#41;;</b></span>', $result);
    }

    /**
     * @return void
     */
    public function testSanitizeXssWillSanitizeHexEncodedScriptTag(): void
    {
        //Arrange
        $harmString = '<img SRC=&#x6A&#x61&#x76&#x61&#x73&#x63&#x72&#x69&#x70&#x74&#x3A&#x61&#x6C&#x65&#x72&#x74&#x28&#x27&#x58&#x53&#x53&#x27&#x29 />';

        //Act
        $result = $this->getUtilSanitizeXssService()->sanitizeXss($harmString);

        //Assert
        $this->assertSame('<img  />', $result);
    }

    /**
     * @return void
     */
    public function testSanitizeXssWillSanitizeAttributeWithoutRemovingIt(): void
    {
        //Arrange
        $harmString = '<iframe width="560" onclick="alert(\'xss\')" height="315" src="http://some-site"></iframe>';

        //Act
        $result = $this->getUtilSanitizeXssService()->sanitizeXss($harmString, [], [static::HTML_TAG_IFRAME]);

        //Assert
        $this->assertSame('<iframe width="560"  height="315" src="http://some-site"></iframe>', $result);
    }

    /**
     * @return void
     */
    public function testSanitizeXssWillSanitizeHtmlTagWithoutRemovingIt(): void
    {
        //Arrange
        $harmString = '<div style="list-style-image: url(javascript:alert(0)); margin=0"></div>';

        //Act
        $result = $this->getUtilSanitizeXssService()->sanitizeXss($harmString, [static::ATTRIBUTE_STYLE]);

        //Assert
        $this->assertThat(
            $result,
            $this->logicalOr(
                '<div style="list-style-image: url(alert&#40;0&#41;); margin=0"></div>',
                '<div style="list-style-image: url((0)); margin=0"></div>',
            ),
        );
    }

    /**
     * @dataProvider getSanitizeXssTwigFunctionsDataProvider
     *
     * @param string $text
     * @param string $expectedResult
     *
     * @return void
     */
    public function testSanitizeXssTwigFunctions(string $text, string $expectedResult): void
    {
        // Act
        $sanitizedText = $this->getUtilSanitizeXssService()->sanitizeXss($text);

        // Assert
        $this->assertSame($expectedResult, $sanitizedText);
    }

    /**
     * @return array<string, list<string>>
     */
    protected function getSanitizeXssTwigFunctionsDataProvider(): array
    {
        return [
            'Should not remove valid twig functions.' => [
                '{{ twig_file() }} {{twig_file([1, 2, 3])}} {{ twigFile("arguments") }}',
                '{{ twig_file() }} {{twig_file([1, 2, 3])}} {{ twigFile("arguments") }}',
            ],
            'Should sanitize invalid twig functions.' => [
                '{{ <script>alert("Hack");</script> }}{{<span style="font-size: 36px;"><b>&lt;script&gt;alert("Hack");&lt;/script&gt;</b></span>}}{{ <img SRC=&#x6A&#x61&#x76&#x61&#x73&#x63&#x72&#x69&#x70&#x74&#x3A&#x61&#x6C&#x65&#x72&#x74&#x28&#x27&#x58&#x53&#x53&#x27&#x29 /> }}{{twig_file([1, 2, 3])}}',
                '{{  }}{{<span ><b>alert&#40;"Hack"&#41;;</b></span>}}{{ <img  /> }}{{twig_file([1, 2, 3])}}',
            ],
            'Should sanitize twig function arguments.' => [
                '{{twig-function(<script>alert("Hack");</script>)}}{{ twig_function(<span style="font-size: 36px;"><b>&lt;script&gt;alert("Hack");&lt;/script&gt;</b></span>) }}{{ twig_file(<script>alert("Hack");</script>) }} {{ twigFile(<img SRC=&#x6A&#x61&#x76&#x61&#x73&#x63&#x72&#x69&#x70&#x74&#x3A&#x61&#x6C&#x65&#x72&#x74&#x28&#x27&#x58&#x53&#x53&#x27&#x29 />) }}',
                '{{twig-function()}}{{ twig_function(<span ><b>alert&#40;"Hack"&#41;;</b></span>) }}{{ twig_file&#40;&#41; }} {{ twigFile(<img  />) }}',
            ],
        ];
    }

    /**
     * @return \Spryker\Service\UtilSanitizeXss\UtilSanitizeXssServiceInterface
     */
    protected function getUtilSanitizeXssService(): UtilSanitizeXssServiceInterface
    {
        return new UtilSanitizeXssService();
    }
}
