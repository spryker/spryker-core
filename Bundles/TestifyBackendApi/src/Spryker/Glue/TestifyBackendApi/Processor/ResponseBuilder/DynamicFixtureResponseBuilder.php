<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Processor\ResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\DynamicFixturesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\TestifyBackendApi\TestifyBackendApiConfig;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class DynamicFixtureResponseBuilder implements DynamicFixtureResponseBuilderInterface
{
    /**
     * @param array<string, \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\ArrayObject<array-key, \Spryker\Shared\Kernel\Transfer\AbstractTransfer>|null> $dynamicFixtures
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createDynamicFixtureResponse(array $dynamicFixtures): GlueResponseTransfer
    {
        $glueResponseTransfer = new GlueResponseTransfer();
        foreach ($dynamicFixtures as $key => $dynamicFixture) {
            if (!$dynamicFixture) {
                continue;
            }

            $glueResponseTransfer->addResource($this->createGlueResourceTransfer($key, $dynamicFixture));
        }

        return $glueResponseTransfer;
    }

    /**
     * @param string $key
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\ArrayObject<array-key, \Spryker\Shared\Kernel\Transfer\AbstractTransfer> $dynamicFixture
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createGlueResourceTransfer(
        string $key,
        AbstractTransfer|ArrayObject $dynamicFixture
    ): GlueResourceTransfer {
        $dynamicFixturesBackendApiAttributesTransfer = (new DynamicFixturesBackendApiAttributesTransfer())
            ->setKey($key)
            ->setData($this->mapDynamicFixtureToData($dynamicFixture));

        return (new GlueResourceTransfer())
            ->setType(TestifyBackendApiConfig::RESOURCE_DYNAMIC_FIXTURES)
            ->setAttributes($dynamicFixturesBackendApiAttributesTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\ArrayObject<array-key, \Spryker\Shared\Kernel\Transfer\AbstractTransfer> $dynamicFixture
     *
     * @return array<string|int, mixed>
     */
    protected function mapDynamicFixtureToData(AbstractTransfer|ArrayObject $dynamicFixture): array
    {
        if ($dynamicFixture instanceof AbstractTransfer) {
            return $dynamicFixture->toArray();
        }

        $dynamicFixturePlainData = [];
        foreach ($dynamicFixture as $key => $item) {
            $dynamicFixturePlainData[$key] = $item->toArray();
        }

        return $dynamicFixturePlainData;
    }
}
