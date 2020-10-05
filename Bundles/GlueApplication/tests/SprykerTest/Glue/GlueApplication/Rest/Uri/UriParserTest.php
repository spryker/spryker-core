<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Uri;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group Spryker
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group Uri
 * @group UriParserTest
 * Add your own group annotations below this line
 */
class UriParserTest extends Unit
{
    /**
     * @return void
     */
    public function testParseSingleResourceShouldExtractResource(): void
    {
        $uriParser = $this->creteUriParser();

        $request = Request::create('/carts/1');

        $resources = $uriParser->parse($request);

        $this->assertSame('carts', $resources[0][RequestConstantsInterface::ATTRIBUTE_TYPE]);
        $this->assertSame('1', $resources[0][RequestConstantsInterface::ATTRIBUTE_ID]);
    }

    /**
     * @return void
     */
    public function testParseMultipleResourceShouldExtractResources(): void
    {
        $uriParser = $this->creteUriParser();

        $request = Request::create('/carts/1/items/sku123');

        $resources = $uriParser->parse($request);

        $this->assertSame('carts', $resources[0][RequestConstantsInterface::ATTRIBUTE_TYPE]);
        $this->assertSame('1', $resources[0][RequestConstantsInterface::ATTRIBUTE_ID]);

        $this->assertSame('items', $resources[1][RequestConstantsInterface::ATTRIBUTE_TYPE]);
        $this->assertSame('sku123', $resources[1][RequestConstantsInterface::ATTRIBUTE_ID]);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Uri\UriParserInterface
     */
    public function creteUriParser(): UriParserInterface
    {
        return new UriParser();
    }
}
