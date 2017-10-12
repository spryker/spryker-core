<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Shared\Url\Url;

use Codeception\Test\Unit;
use Spryker\Service\UtilText\Model\Url\Url;

/**
 * Auto-generated group annotations
 * @group Functional
 * @group Spryker
 * @group Shared
 * @group Url
 * @group Url
 * @group UrlTest
 * Add your own group annotations below this line
 */
class UrlTest extends Unit
{
    /**
     * @return void
     */
    public function testUrlConstruct()
    {
        $url = new Url(['path' => '/foo/bar']);

        $this->assertSame('/foo/bar', $url->build());
    }

    /**
     * @return void
     */
    public function testToString()
    {
        $url = new Url(['path' => '/foo/bar']);

        $this->assertSame('/foo/bar', (string)$url);
    }

    /**
     * @return void
     */
    public function testToArray()
    {
        $url = new Url(['path' => '/foo/bar', 'query' => ['x' => 'y'], 'fragment' => 'z']);

        $expected = [
            'scheme' => null,
            'user' => null,
            'pass' => null,
            'host' => null,
            'port' => null,
            'path' => '/foo/bar',
            'query' => ['x' => 'y'],
            'fragment' => 'z',
        ];
        $result = $url->toArray();
        $this->assertSame($expected, $result);
    }

    /**
     * @return void
     */
    public function testBuild()
    {
        $url = new Url(['path' => '/foo/bar', 'query' => ['x' => 'y'], 'fragment' => 'z']);

        $this->assertSame('/foo/bar?x=y#z', $url->build());
    }

    /**
     * @return void
     */
    public function testBuildWithQueryAsString()
    {
        $url = new Url(['path' => '/foo/bar', 'query' => 'ö=ä', 'fragment' => 'z']);

        $this->assertSame('/foo/bar?%C3%B6=%C3%A4#z', $url->build());
    }

    /**
     * @return void
     */
    public function testBuildEscaped()
    {
        $url = new Url(['path' => '/foo/bar', 'query' => ['x' => 'y', 'ö' => 'ä'], 'fragment' => 'z']);

        $this->assertSame('/foo/bar?x=y&amp;%C3%B6=%C3%A4#z', $url->buildEscaped());
    }

    /**
     * @return void
     */
    public function testParse()
    {
        $url = Url::parse('/foo/bar?q=a#z');

        $this->assertSame('/foo/bar?q=a#z', (string)$url);
    }

    /**
     * @return void
     */
    public function testGetPathSegments()
    {
        $url = new Url(['path' => '/foo/bar/baz', 'query' => 'q=a', 'fragment' => 'x']);
        $segments = $url->getPathSegments();
        $this->assertSame(['foo', 'bar', 'baz'], $segments);
    }

    /**
     * @return void
     */
    public function testNormalizePath()
    {
        $url = new Url(['path' => '/foo/bar/baz//abc/', 'query' => ['x' => 'y'], 'fragment' => 'z']);
        $path = $url->normalizePath()->build();
        $this->assertSame('/foo/bar/baz/abc?x=y#z', $path);
    }

    /**
     * @return void
     */
    public function testSetPathAsString()
    {
        $url = new Url(['path' => '/foo/bar/baz', 'query' => 'x=y', 'fragment' => 'z']);
        $url->setPath('/e/f');
        $this->assertSame('/e/f?x=y#z', $url->build());
    }

    /**
     * @return void
     */
    public function testSetPathAsArray()
    {
        $url = new Url(['path' => '/foo/bar/baz', 'query' => 'x=y', 'fragment' => 'z']);
        $url->setPath(['e', 'f']);
        $this->assertSame('/e/f?x=y#z', $url->build());
    }

    /**
     * @return void
     */
    public function testAddPathAsString()
    {
        $url = new Url(['path' => '/foo/bar/baz', 'query' => 'x=y', 'fragment' => 'z']);
        $url->addPath('/e/f/');

        $this->assertSame('/foo/bar/baz/e/f?x=y#z', $url->build());
    }

    /**
     * @return void
     */
    public function testAddPathAsArray()
    {
        $url = new Url(['path' => '/foo/bar/baz', 'query' => 'x=y', 'fragment' => 'z']);
        $url->addPath(['e', 'f']);

        $this->assertSame('/foo/bar/baz/e/f?x=y#z', $url->build());
    }

    /**
     * @return void
     */
    public function testSetQuery()
    {
        $url = new Url(['path' => '/foo/bar/baz', 'query' => 'x=y', 'fragment' => 'z']);
        $url->addQuery('c', 'd');
        $url->addQuery('e', 'f');
        $this->assertSame('/foo/bar/baz?x=y&c=d&e=f#z', $url->build());
    }

    /**
     * @return void
     */
    public function testEmpty()
    {
        $url = new Url();
        $this->assertSame('/', $url->build(), 'Empty URL object must return homepage');
    }

    /**
     * @return void
     */
    public function testFull()
    {
        $url = new Url();
        $url->addQuery('x', 'y');

        $url->setScheme('https');
        $url->setHost('www.foobar.dev');
        $url->setPort(81);

        $this->assertSame('https://www.foobar.dev:81/?x=y', $url->build());
    }
}
