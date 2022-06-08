<?php
// phpcs:ignoreFile
// Mock plugin  for test

namespace SprykerTest\Glue\GlueJsonApiConvention\Stub;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;

class TestEmptyJsonApiResourcePlugin implements JsonApiResourceInterface
{
    /**
     * @param GlueRequestTransfer $glueRequestTransfer
     *
     * @return callable
     */
    public function getResource(GlueRequestTransfer $glueRequestTransfer): callable
    {
        return [
            new \stdClass(),
            'method'
        ];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'type';
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return 'Controller';
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer
     */
    public function getDeclaredMethods(): GlueResourceMethodCollectionTransfer
    {
        return new GlueResourceMethodCollectionTransfer();
    }
}
