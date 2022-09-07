<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Http\Request;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplication\Http\Request\RequestBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Http
 * @group Request
 * @group RequestBuilderTest
 *
 * Add your own group annotations below this line
 */
class RequestBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const HTTP_HOST = 'glue.de.spryker.local';

    /**
     * @var string
     */
    protected const HTTP_CONTENT_TYPE = 'application/xml';

    /**
     * @var string
     */
    protected const HTTP_ACCEPT = 'text/html, application/xhtml+xml, application/xml;q=0.9, */*;q=0.8';

    /**
     * @var string
     */
    protected const HTTP_ACCEPT_CHARSET = 'ISO-8859-1,utf-8;q=0.7,*;q=0.3';

    /**
     * @var string
     */
    protected const HTTP_ACCEPT_LANGUAGE = 'de-DE';

    /**
     * @var string
     */
    protected const FIRST_FIELD_NAME = 'field1';

    /**
     * @var string
     */
    protected const FIRST_FIELD_VALUE = 'value1';

    /**
     * @var string
     */
    protected const SECOND_FIELD_NAME = 'field2';

    /**
     * @var string
     */
    protected const SECOND_FIELD_VALUE = 'value2';

    /**
     * @var string
     */
    protected const THIRD_FIELD_VALUE = 'value3';

    /**
     * @return void
     */
    public function testExtractHeadersFromRequest(): void
    {
        // Arrange
        $expectedHeaders = ['host', 'content-type', 'accept', 'accept-charset', 'accept-language'];
        $_SERVER['HTTP_HOST'] = static::HTTP_HOST;
        $_SERVER['HTTP_CONTENT_TYPE'] = static::HTTP_CONTENT_TYPE;
        $_SERVER['HTTP_ACCEPT'] = static::HTTP_ACCEPT;
        $_SERVER['HTTP_ACCEPT_CHARSET'] = static::HTTP_ACCEPT_CHARSET;
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = static::HTTP_ACCEPT_LANGUAGE;

        // Act
        $glueRequestTransfer = $this->createRequestBuilderAndExtractData();

        // Assert
        $meta = $glueRequestTransfer->getMeta();
        foreach ($expectedHeaders as $expectedHeader) {
            $this->assertArrayHasKey($expectedHeader, $meta);
        }
        $this->assertSame(static::HTTP_HOST, $meta[$expectedHeaders[0]][0]);
        $this->assertSame(static::HTTP_CONTENT_TYPE, $meta[$expectedHeaders[1]][0]);
        $this->assertSame(static::HTTP_ACCEPT, $meta[$expectedHeaders[2]][0]);
        $this->assertSame(static::HTTP_ACCEPT_CHARSET, $meta[$expectedHeaders[3]][0]);
        $this->assertSame(static::HTTP_ACCEPT_LANGUAGE, $meta[$expectedHeaders[4]][0]);
    }

    /**
     * @return void
     */
    public function testNoQueryFields(): void
    {
        // Act
        $glueRequestTransfer = $this->createRequestBuilderAndExtractData();

        // Assert
        $this->assertCount(0, $glueRequestTransfer->getQueryFields());
    }

    /**
     * @return void
     */
    public function testEmptyQueryFields(): void
    {
        // Arrange
        $_GET[static::FIRST_FIELD_NAME] = '';

        // Act
        $glueRequestTransfer = $this->createRequestBuilderAndExtractData();

        // Assert
        $queryFields = $glueRequestTransfer->getQueryFields();
        $this->assertCount(1, $queryFields);
        $this->assertArrayHasKey(static::FIRST_FIELD_NAME, $queryFields);
        $this->assertEmpty($queryFields[static::FIRST_FIELD_NAME]);
    }

    /**
     * @return void
     */
    public function testSingleQueryField(): void
    {
        // Arrange
        $_GET[static::FIRST_FIELD_NAME] = static::FIRST_FIELD_VALUE;

        // Act
        $glueRequestTransfer = $this->createRequestBuilderAndExtractData();

        // Assert
        $queryFields = $glueRequestTransfer->getQueryFields();
        $this->assertCount(1, $queryFields);
        $this->assertArrayHasKey(static::FIRST_FIELD_NAME, $queryFields);
        $this->assertSame(static::FIRST_FIELD_VALUE, $queryFields[static::FIRST_FIELD_NAME]);
    }

    /**
     * @return void
     */
    public function testMultipleQueryFields(): void
    {
        // Arrange
        $_GET[static::FIRST_FIELD_NAME] = static::FIRST_FIELD_VALUE;
        $_GET[static::SECOND_FIELD_NAME] = static::SECOND_FIELD_VALUE;

        // Act
        $glueRequestTransfer = $this->createRequestBuilderAndExtractData();

        // Assert
        $queryFields = $glueRequestTransfer->getQueryFields();
        $this->assertCount(2, $queryFields);
        $this->assertArrayHasKey(static::FIRST_FIELD_NAME, $queryFields);
        $this->assertSame(static::FIRST_FIELD_VALUE, $queryFields[static::FIRST_FIELD_NAME]);
        $this->assertArrayHasKey(static::SECOND_FIELD_NAME, $queryFields);
        $this->assertSame(static::SECOND_FIELD_VALUE, $queryFields[static::SECOND_FIELD_NAME]);
    }

    /**
     * @return void
     */
    public function testMultiValueFields(): void
    {
        // Arrange
        $_GET[static::FIRST_FIELD_NAME] = static::FIRST_FIELD_VALUE;
        $_GET[static::SECOND_FIELD_NAME] = [static::SECOND_FIELD_VALUE, static::THIRD_FIELD_VALUE];

        // Act
        $glueRequestTransfer = $this->createRequestBuilderAndExtractData();

        // Assert
        $queryFields = $glueRequestTransfer->getQueryFields();
        $this->assertCount(2, $queryFields);
        $this->assertArrayHasKey(static::FIRST_FIELD_NAME, $queryFields);
        $this->assertSame(static::FIRST_FIELD_VALUE, $queryFields[static::FIRST_FIELD_NAME]);
        $this->assertArrayHasKey(static::SECOND_FIELD_NAME, $queryFields);
        $this->assertIsArray($queryFields[static::SECOND_FIELD_NAME]);
        $this->assertCount(2, $queryFields[static::SECOND_FIELD_NAME]);
        $this->assertSame(static::SECOND_FIELD_VALUE, $queryFields[static::SECOND_FIELD_NAME][0]);
        $this->assertSame(static::THIRD_FIELD_VALUE, $queryFields[static::SECOND_FIELD_NAME][1]);
    }

    /**
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function createRequestBuilderAndExtractData(): GlueRequestTransfer
    {
        $requestBuilder = new RequestBuilder(
            Request::createFromGlobals(),
        );

        return $requestBuilder->extract(new GlueRequestTransfer());
    }
}
