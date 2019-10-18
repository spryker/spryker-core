<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\Testify\Helper;

use Codeception\Exception\ModuleException;
use Codeception\Module\REST;
use Codeception\Util\HttpCode;
use JsonPath\JsonObject;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Testify\TestifyConstants;
use SprykerTest\Shared\Testify\Helper\ModuleLocatorTrait;

class GlueRest extends REST implements LastConnectionProviderInterface
{
    use ModuleLocatorTrait;

    public const DEFAULT_PASSWORD = 'Pass$.123456';

    /**
     * @var \SprykerTest\Glue\Testify\Helper\JsonPath|null
     */
    protected $jsonPathModule;

    /**
     * @var \SprykerTest\Glue\Testify\Helper\JsonConnection|null
     */
    protected $lastConnection;

    /**
     * @inheritDoc
     */
    public function _initialize(): void
    {
        parent::_initialize();
    }

    /**
     * @inheritDoc
     */
    public function getLastConnection(): ?Connection
    {
        return $this->lastConnection;
    }

    /**
     * @part json
     *
     * @throws \Codeception\Exception\ModuleException
     *
     * @return array
     */
    public function grabResponseJson(): array
    {
        $rawResponse = $this->grabResponse();

        $jsonResponse = json_decode($rawResponse, true); // TODO PHP 7.3 use JSON_THROW_ON_ERROR

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ModuleException(
                'GlueRest',
                sprintf(
                    'Failed to parse json string "%s", error: "%s"',
                    $jsonResponse,
                    json_last_error_msg()
                )
            );
        }

        return $jsonResponse;
    }

    /**
     * @part json
     * @part xml
     *
     * @return int
     */
    public function grabResponseCode(): int
    {
        return (int)$this->connectionModule->_getResponseStatusCode();
    }

    /**
     * Extending with response in the message
     *
     * @part json
     *
     * @param int $code
     *
     * @return void
     */
    public function seeResponseCodeIs($code): void
    {
        $rawResponse = $this->grabResponse();
        $responseLimit = 300;
        $failureMessage = sprintf(
            'Expected HTTP Status Code: %s. Actual Status Code: %s with the response "%s"',
            HttpCode::getDescription($code),
            HttpCode::getDescription($this->grabResponseCode()),
            strlen($rawResponse) > $responseLimit ? substr($rawResponse, 0, $responseLimit) . '...' : $rawResponse
        );
        $this->assertEquals($code, $this->grabResponseCode(), $failureMessage);
    }

    /**
     * @part json
     *
     * @param string $jsonPath
     *
     * @return array|mixed
     */
    public function grabDataFromResponseByJsonPath($jsonPath)
    {
        return (new JsonObject($this->connectionModule->_getResponseContent()))->get($jsonPath);
    }

    /**
     * @part json
     *
     * @param string $link
     *
     * @return void
     */
    public function seeResponseLinksContainsSelfLink(string $link): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'self' => $link,
        ], '$.links');
    }

    /**
     * @part json
     *
     * @param string $type
     *
     * @return void
     */
    public function seeResponseDataContainsSingleResourceOfType(string $type): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'type' => $type,
        ], '$.data');
    }

    /**
     * @part json
     *
     * @param string $id
     *
     * @return void
     */
    public function seeSingleResourceIdEqualTo(string $id): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'id' => $id,
        ], '$.data');
    }

    /**
     * @part json
     *
     * @param array $attributes
     *
     * @return void
     */
    public function seeSingleResourceContainsAttributes(array $attributes): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains($attributes, '$.data.attributes');
    }

    /**
     * @part json
     *
     * @param string $type
     * @param string $id
     *
     * @return void
     */
    public function seeSingleResourceHasRelationshipByTypeAndId(string $type, string $id): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'type' => $type,
            'id' => $id,
        ], sprintf(
            '$.data.relationships.%1$s.data[?(@.id == %2$s and @.type == %3$s)]',
            $type,
            json_encode($id),
            json_encode($type)
        ));
    }

    /**
     * @part json
     *
     * @param string $link
     *
     * @return void
     */
    public function seeSingleResourceHasSelfLink(string $link): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'self' => $link,
        ], '$.data.links');
    }

    /**
     * @part json
     *
     * @param string $type
     * @param int $size
     *
     * @return void
     */
    public function seeResponseDataContainsResourceCollectionOfTypeWithSizeOf(string $type, int $size): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'type' => $type,
        ], '$.data[*]');
        $this->assertCount($size, $this->grabDataFromResponseByJsonPath('$.data')[0]);
    }

    /**
     * @part json
     *
     * @return void
     */
    public function seeResponseDataContainsEmptyCollection(): void
    {
        $this->getJsonPathModule()->dontSeeResponseMatchesJsonPath('$.data[*]');
    }

    /**
     * @part json
     *
     * @return void
     */
    public function seeResponseDataContainsNonEmptyCollection(): void
    {
        $this->getJsonPathModule()->seeResponseMatchesJsonPath('$.data[*]');
    }

    /**
     * @part json
     *
     * @param string $id
     *
     * @return void
     */
    public function seeResourceCollectionHasResourceWithId(string $id): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            [
                'id' => $id,
            ],
            sprintf('$.data[?(@.id == %s)]', json_encode($id))
        );
    }

    /**
     * @part json
     *
     * @param string $id
     * @param array $attributes
     *
     * @return void
     */
    public function seeResourceByIdContainsAttributes(string $id, array $attributes): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            $attributes,
            sprintf('$.data[?(@.id == %s)].attributes', json_encode($id))
        );
    }

    /**
     * @part json
     *
     * @param string $id
     * @param string $relationType
     * @param string $relationId
     *
     * @return void
     */
    public function seeResourceByIdHasRelationshipByTypeAndId(string $id, string $relationType, string $relationId): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'type' => $relationType,
            'id' => $relationId,
        ], sprintf(
            '$.data[?(@.id == %1$s)].relationships.%2$s.data[?(@.id == %3$s and @.type == %4$s)]',
            json_encode($id),
            $relationType,
            json_encode($relationId),
            json_encode($relationType)
        ));
    }

    /**
     * @part json
     *
     * @param string $id
     * @param string $link
     *
     * @return void
     */
    public function seeResourceByIdHasSelfLink(string $id, string $link): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            [
                'self' => $link,
            ],
            sprintf('$.data[?(@.id == %s)].links', json_encode($id))
        );
    }

    /**
     * @part json
     *
     * @param string $type
     * @param string $id
     *
     * @return void
     */
    public function seeIncludesContainsResourceByTypeAndId(string $type, string $id): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            [
                'type' => $type,
                'id' => $id,
            ],
            sprintf(
                '$.included[?(@.id == %1$s and @.type == %2$s)]',
                json_encode($id),
                json_encode($type)
            )
        );
    }

    /**
     * @part json
     *
     * @param string $type
     * @param string $id
     * @param array $attributes
     *
     * @return void
     */
    public function seeIncludedResourceByTypeAndIdContainsAttributes(string $type, string $id, array $attributes): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            $attributes,
            sprintf(
                '$.included[?(@.id == %1$s and @.type == %2$s)].attributes',
                json_encode($id),
                json_encode($type)
            )
        );
    }

    /**
     * @part json
     *
     * @param string $type
     * @param string $id
     * @param string $relationType
     * @param string $relationId
     *
     * @return void
     */
    public function seeIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
        string $type,
        string $id,
        string $relationType,
        string $relationId
    ): void {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'type' => $relationType,
            'id' => $relationId,
        ], sprintf(
            '$.included[?(@.id == %1$s and @.type == %2$s)].relationships.%3$s.data[?(@.id == %4$s and @.type == %5$s)]',
            json_encode($id),
            json_encode($type),
            $relationType,
            json_encode($relationId),
            json_encode($relationType)
        ));
    }

    /**
     * @part json
     *
     * @param string $type
     * @param string $id
     * @param string $link
     *
     * @return void
     */
    public function seeIncludedResourceByTypeAndIdHasSelfLink(string $type, string $id, string $link): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            [
                'self' => $link,
            ],
            sprintf(
                '$.included[?(@.id == %1$s and @.type == %2$s)].links',
                json_encode($id),
                json_encode($type)
            )
        );
    }

    /**
     * @part json
     *
     * @param string $attribute
     *
     * @return void
     */
    public function seeSingleResourceHasAttribute(string $attribute): void
    {
        $this->getJsonPathModule()->seeResponseMatchesJsonPath(
            sprintf(
                '$.data.attributes.%s',
                $attribute
            )
        );
    }

    /**
     * @part json
     *
     * @param string $attribute
     *
     * @return void
     */
    public function seeResourceCollectionHasAttribute(string $attribute): void
    {
        $this->getJsonPathModule()->seeResponseMatchesJsonPath(
            sprintf(
                '$.data[*].attributes.%s',
                $attribute
            )
        );
    }

    /**
     * @inheritDoc
     */
    protected function resetVariables(): void
    {
        $this->lastConnection = null;
    }

    /**
     * @inheritDoc
     */
    protected function execute($method, $url, $parameters = [], $files = [])
    {
        $this->prepareHeaders();

        if (strpos($url, '://') === false) {
            $url = Config::get(TestifyConstants::GLUE_APPLICATION_DOMAIN) . '/' . rtrim($url, '/');
        }

        parent::execute($method, $url, $parameters, $files);

        $this->persistLastConnection($method, $url, $parameters, $files);
    }

    /**
     * @return static
     */
    protected function prepareHeaders(): self
    {
        $this->startFollowingRedirects();
        $this->haveHttpHeader('X-Requested-With', 'Codeception');
        $this->haveHttpHeader('Content-Type', 'application/vnd.api+json');
        $this->haveHttpHeader('Accept', '*/*');

        return $this;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $parameters
     * @param array $files
     *
     * @return static
     */
    protected function persistLastConnection(string $method, string $url, array $parameters, array $files): self
    {
        $responseBody = $this->grabResponse();
        $jsonResponse = json_decode($responseBody, true);
        $this->lastConnection = (new JsonConnection())
            ->setResponseBody($responseBody)
            ->setResponseJson(is_array($jsonResponse) ? $jsonResponse : null)
            ->setRequestFiles($files)
            ->setRequestMethod($method)
            ->setRequestParameters($parameters)
            ->setRequestUrl(strpos($url, '://') !== false ? $url : $this->config['url'] . $url)
            ->setResponseCode($this->grabResponseCode());

        return $this;
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     *
     * @return \SprykerTest\Glue\Testify\Helper\JsonPath
     */
    protected function getJsonPathModule(): JsonPath
    {
        $this->jsonPathModule = $this->jsonPathModule ?: $this->findModule(JsonPath::class);

        if ($this->jsonPathModule === null) {
            throw new ModuleException('GlueRest', 'The module requires JsonPath');
        }

        return $this->jsonPathModule;
    }
}
