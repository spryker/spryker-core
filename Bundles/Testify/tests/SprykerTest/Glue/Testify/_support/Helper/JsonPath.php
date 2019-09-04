<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\Testify\Helper;

use Codeception\Module;
use Codeception\PHPUnit\Constraint\JsonType as JsonTypeConstraint;
use Codeception\Util\JsonType;
use JsonPath\JsonObject;
use PHPUnit\Framework\Assert;

/**
 * For JSONPath extended syntax: @see https://github.com/Galbar/JsonPath-PHP
 */
class JsonPath extends Module
{
    use LastConnectionConsumerTrait;

    /**
     * @inheritdoc
     */
    public function _initialize(): void
    {
        parent::_initialize();

        $this->initializeAdditionalJsonConstrains();
    }

    /**
     * @return static
     */
    protected function initializeAdditionalJsonConstrains(): self
    {
        JsonType::addCustomFilter('/equal\(("[^"\\\\]*(?:\\\\.[^"\\\\]*)*")\)/s', function ($value, $expected) {
            return (string)$value === (string)json_decode($expected);
        });

        JsonType::addCustomFilter('/equal\((\d+)\)/s', function ($value, $expected) {
            return (int)$value === (int)$expected;
        });

        JsonType::addCustomFilter('/equal\((\d+\.\d+)\)/s', function ($value, $expected) {
            return (float)$value === (float)$expected;
        });

        return $this;
    }

    /**
     * @param string $jsonPath
     *
     * @return void
     */
    public function seeResponseMatchesJsonPath(string $jsonPath): void
    {
        $this->assertNotEmpty(
            (new JsonObject($this->getJsonLastConnection()->getResponseJson()))->get($jsonPath),
            "Received JSON did not match the JsonPath `$jsonPath`.\nJson Response: \n" . $this->getJsonLastConnection()->getResponseBody()
        );
    }

    /**
     * @param string $jsonPath
     *
     * @return void
     */
    public function dontSeeResponseMatchesJsonPath(string $jsonPath): void
    {
        $this->assertEmpty(
            (new JsonObject($this->getJsonLastConnection()->getResponseJson()))->get($jsonPath),
            "Received JSON did not match the JsonPath `$jsonPath`.\nJson Response: \n" . $this->getJsonLastConnection()->getResponseBody()
        );
    }

    /**
     * @param array $jsonType
     * @param string $jsonPath
     *
     * @return void
     */
    public function seeResponseJsonPathMatchesJsonType(array $jsonType, $jsonPath = '$'): void
    {
        Assert::assertThat(
            (new JsonObject($this->getJsonLastConnection()->getResponseJson()))->get($jsonPath),
            new JsonTypeConstraint($jsonType)
        );
    }

    /**
     * @param array $subArray
     * @param string $jsonPath
     *
     * @return void
     */
    public function seeResponseJsonPathContains(array $subArray, $jsonPath = '$'): void
    {
        $foundSegments = (new JsonObject($this->getJsonLastConnection()->getResponseJson()))->get($jsonPath);

        Assert::assertIsArray($foundSegments, 'Requested response part should be an array to assert `contains`');

        foreach ($foundSegments as $segment) {
            Assert::assertArraySubset(
                $subArray,
                $segment
            );
        }
    }
}
