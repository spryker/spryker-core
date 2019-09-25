<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\Testify\Helper;

use Codeception\Exception\ModuleException;
use Codeception\Module;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;
use PHPUnit\Framework\AssertionFailedError;
use Spryker\Glue\Testify\OpenApi3\Exception\ParseException;
use Spryker\Glue\Testify\OpenApi3\Object\OpenApi;
use Spryker\Glue\Testify\OpenApi3\Object\Operation;
use Spryker\Glue\Testify\OpenApi3\Object\PathItem;
use Spryker\Glue\Testify\OpenApi3\Object\Response;
use Spryker\Glue\Testify\OpenApi3\Object\Schema;
use Spryker\Glue\Testify\OpenApi3\OpenApiSchemaParser;
use Spryker\Glue\Testify\OpenApi3\Reader\YamlFileReader;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Testify\TestifyConstants;

class OpenApi3 extends Module
{
    use LastConnectionConsumerTrait;

    /**
     * @var array
     */
    protected $config = [
        'schema' => '',
    ];

    /**
     * @var string
     */
    protected $dependencyMessage = <<<EOF
Example configuring OpenApi3.
--
modules:
    enabled:
        - \SprykerTest\Glue\Testify\Helper\OpenApi3
    config:
        \SprykerTest\Glue\Testify\Helper\OpenApi3:
            schema: http://localhost/api/schema.yml
--
EOF;

    /**
     * @var \Spryker\Glue\Testify\OpenApi3\Object\OpenApi|null
     */
    protected $schema;

    /**
     * @inheritDoc
     */
    public function _initialize(): void
    {
        parent::_initialize();

        $this->schema = $this->readOpenApiSchemaIfDefined();
    }

    /**
     * @return array
     */
    public function _parts(): array
    {
        return ['json'];
    }

    /**
     * @part json
     *
     * @throws \Codeception\Exception\ModuleException
     *
     * @return void
     */
    public function seeResponseMatchesOpenApiSchema(): void
    {
        if ($this->schema === null) {
            throw new ModuleException('OpenApi3', 'Schema is not configured');
        }

        $connection = $this->getJsonLastConnection();
        $pathDefinition = $this->findPathDefinition($this->schema, $connection->getRequestUrl());
        $methodDefinition = $this->findMethodDefinition($pathDefinition, $connection->getRequestMethod());
        $responseDefinition = $this->findResponseDefinition($methodDefinition, $connection->getResponseCode());
        $responseSchema = $this->findResponseSchema($responseDefinition, $connection->getResponseContentType());

        $this->seeResponseDataMatchesOpenApiSchema($connection->getResponseJson(), $responseSchema);
    }

    /**
     * @part json
     *
     * @param array $responseData
     * @param \Spryker\Glue\Testify\OpenApi3\Object\Schema $responseSchema
     *
     * @return void
     */
    public function seeResponseDataMatchesOpenApiSchema(array $responseData, Schema $responseSchema): void
    {
        $validator = new Validator();
        $validator->validate($responseData, $responseSchema, Constraint::CHECK_MODE_EXCEPTIONS);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     *
     * @return \Spryker\Glue\Testify\OpenApi3\Object\OpenApi|null
     */
    protected function readOpenApiSchemaIfDefined(): ?OpenApi
    {
        $schemaPath = $this->config['schema'] ?: Config::get(TestifyConstants::GLUE_OPEN_API_SCHEMA);

        $reader = new YamlFileReader($schemaPath);
        $parser = new OpenApiSchemaParser();

        try {
            return $parser->parse($reader);
        } catch (ParseException $exception) {
            throw new ModuleException('OpenApi3', sprintf(
                'OpenApi Schema validation has not passed: %s',
                $exception->getMessage()
            ));
        }
    }

    /**
     * @param \Spryker\Glue\Testify\OpenApi3\Object\OpenApi $schema
     * @param string $url
     *
     * @throws \PHPUnit\Framework\AssertionFailedError
     *
     * @return \Spryker\Glue\Testify\OpenApi3\Object\PathItem
     */
    protected function findPathDefinition(OpenApi $schema, string $url): PathItem
    {
        $urlWithoutQuery = rtrim(strtok($url, '?'), '/');

        foreach ($schema->paths as $path => $pathDefinition) {
            // TODO preg_quote, but it brakes the expression
            $pathTemplate = '#^.*' . preg_replace('/\{[^\}]+\}/m', '[^/]*', $path) . '((\?.*)|)$#';

            if (preg_match($pathTemplate, $urlWithoutQuery)) {
                return $pathDefinition;
            }
        }

        throw new AssertionFailedError(sprintf('No valid path is found in the schema for "%s"', $url));
    }

    /**
     * @param \Spryker\Glue\Testify\OpenApi3\Object\PathItem $pathItem
     * @param string $method
     *
     * @throws \PHPUnit\Framework\AssertionFailedError
     *
     * @return \Spryker\Glue\Testify\OpenApi3\Object\Operation
     */
    protected function findMethodDefinition(PathItem $pathItem, string $method): Operation
    {
        if (isset($pathItem->{$method})) {
            return $pathItem->{$method};
        }

        throw new AssertionFailedError(sprintf('No valid method is found in the path definition for "%s"', $method));
    }

    /**
     * @param \Spryker\Glue\Testify\OpenApi3\Object\Operation $operation
     * @param int $responseCode
     *
     * @throws \PHPUnit\Framework\AssertionFailedError
     *
     * @return \Spryker\Glue\Testify\OpenApi3\Object\Response
     */
    protected function findResponseDefinition(Operation $operation, int $responseCode): Response
    {
        /** @var \Spryker\Glue\Testify\OpenApi3\Collection\Responses $responses */
        $responses = $operation->responses;
        if ($responses->offsetExists($responseCode)) {
            return $responses[$responseCode];
        }

        $responseCodeMask = substr((string)$responseCode, 0, 1) . 'XX';
        if ($responses->offsetExists($responseCodeMask)) {
            return $responses[$responseCodeMask];
        }

        throw new AssertionFailedError(sprintf(
            'No valid response code is found in the method definition for "%d"',
            $responseCode
        ));
    }

    /**
     * @param \Spryker\Glue\Testify\OpenApi3\Object\Response $responseDefinition
     * @param string $contentType
     *
     * @throws \PHPUnit\Framework\AssertionFailedError
     *
     * @return \Spryker\Glue\Testify\OpenApi3\Object\Schema
     */
    protected function findResponseSchema(Response $responseDefinition, string $contentType): Schema
    {
        if (isset($responseDefinition->content[$contentType]->schema)) {
            return $responseDefinition->content[$contentType]->schema;
        }

        throw new AssertionFailedError(sprintf(
            'No valid response schema is found in the response definition for "%s"',
            $contentType
        ));
    }
}
