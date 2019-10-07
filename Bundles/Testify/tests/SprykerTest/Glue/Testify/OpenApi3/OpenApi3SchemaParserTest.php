<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\Testify\OpenApi3;

use Codeception\Test\Unit;
use Spryker\Glue\Testify\OpenApi3\OpenApiSchemaParser;
use Spryker\Glue\Testify\OpenApi3\Reader\YamlFileReader;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group Testify
 * @group OpenApi3
 * @group OpenApi3SchemaParserTest
 * Add your own group annotations below this line
 */
class OpenApi3SchemaParserTest extends Unit
{
    /**
     * @return void
     */
    public function testYamlReaderReadsSchemaFromFile(): void
    {
        $reader = new YamlFileReader(__DIR__ . DIRECTORY_SEPARATOR . 'schema.yml');
        $parser = new OpenApiSchemaParser();
        $document = $parser->parse($reader);

        $this->assertEquals('3.0.0', $document->openapi);
        $this->assertEquals('Swagger Petstore', $document->info->title);
        $this->assertEquals('Swagger API Team', $document->info->contact->name);
        $this->assertEquals('http://petstore.swagger.io/api', $document->servers[0]->url);

        $this->assertEquals(
            ['name'],
            $document->paths['/pets']->post->responses[200]->content['application/json']->schema->allOf[0]->required->toArray()
        );
        $this->assertEquals(
            'string',
            $document->paths['/pets']->post->responses[200]->content['application/json']->schema->allOf[0]->properties['name']->type
        );
        $this->assertEquals(
            ['id'],
            $document->paths['/pets']->post->responses[200]->content['application/json']->schema->allOf[1]->required->toArray()
        );
        $this->assertEquals(
            'integer',
            $document->paths['/pets']->post->responses[200]->content['application/json']->schema->allOf[1]->properties['id']->type
        );
        $this->assertEquals(
            'string',
            $document->paths['/pets']->get->parameters[0]->schema->items->type
        );
    }
}
