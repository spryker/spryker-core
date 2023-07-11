<?php

return [
    'paths' =>
         [
            '/dynamic-entity-prefix/test-resource' =>
                 [
                    'get' =>
                         [
                            'tags' =>
                                 [
                                    0 => 'dynamic-entity-test-resource',
                                ],
                            'operationId' => 'get-collection-dynamic-api-test-resource',
                            'summary' => 'Get collection of entities for defined resource.',
                            'parameters' =>
                                 [
                                    0 =>
                                         [
                                            'name' => 'filter',
                                            'in' => 'query',
                                            'description' => 'Parameter is used to filter items by specified values.',
                                            'required' => false,
                                            'style' => 'deepObject',
                                            'explode' => true,
                                            'schema' =>
                                                 [
                                                    'type' => 'object',
                                                    'properties' =>
                                                         [
                                                            'test-resource.test' =>
                                                                 [
                                                                    'type' => 'string',
                                                                ],
                                                        ],
                                                ],
                                        ],
                                    1 =>
                                         [
                                            'name' => 'Content-Type',
                                            'in' => 'header',
                                            'description' => 'Content type of request body.',
                                            'required' => true,
                                            'schema' =>
                                                 [
                                                    'type' => 'string',
                                                    'example' => 'application/json',
                                                ],
                                        ],
                                    2 =>
                                         [
                                            'name' => 'Accept',
                                            'in' => 'header',
                                            'description' => 'The Accept request HTTP header indicates which content types, expressed as MIME types, the client is able to understand.',
                                            'required' => true,
                                            'schema' =>
                                                 [
                                                    'type' => 'string',
                                                    'example' => 'application/json',
                                                ],
                                        ],
                                    3 =>
                                         [
                                            'name' => 'page',
                                            'in' => 'query',
                                            'description' => 'Parameter is used to paginate items.',
                                            'required' => false,
                                            'schema' =>
                                                 [
                                                    'type' => 'object',
                                                    'properties' =>
                                                         [
                                                            'offset' =>
                                                                 [
                                                                    'type' => 'integer',
                                                                ],
                                                            'limit' =>
                                                                 [
                                                                    'type' => 'integer',
                                                                ],
                                                        ],
                                                ],
                                        ],
                                ],
                            'responses' =>
                                 [
                                    200 =>
                                         [
                                            'description' => 'Expected response to a valid request returned successfully.',
                                            'content' =>
                                                 [
                                                    'application/json' =>
                                                         [
                                                            'schema' =>
                                                                 [
                                                                    'type' => 'array',
                                                                    'items' =>
                                                                         [
                                                                            'type' => 'object',
                                                                            'properties' =>
                                                                                 [
                                                                                    'test' =>
                                                                                         [
                                                                                            'type' => 'string',
                                                                                        ],
                                                                                ],
                                                                        ],
                                                                ],
                                                        ],
                                                ],
                                        ],
                                     403 =>
                                         [
                                             'description' => 'Unauthorized request.',
                                             'content' =>
                                                 [
                                                     'application/json' =>
                                                         [
                                                             'schema' =>
                                                                 [
                                                                     '$ref' => '#/components/schemas/RestErrorMessage',
                                                                 ],
                                                         ],
                                                 ],
                                         ],
                                    'default' =>
                                         [
                                            'description' => 'An error occurred.',
                                            'content' =>
                                                 [
                                                    'application/json' =>
                                                         [
                                                            'schema' =>
                                                                 [
                                                                    '$ref' => '#/components/schemas/RestErrorMessage',
                                                                ],
                                                        ],
                                                ],
                                        ],
                                ],
                        ],
                ],
            '/dynamic-entity-prefix/test-resource/{id}' =>
                 [
                    'get' =>
                         [
                            'tags' =>
                                 [
                                    0 => 'dynamic-entity-test-resource',
                                ],
                            'operationId' => 'get-entity-dynamic-api-test-resource',
                            'summary' => 'Get item of entities for defined resource.',
                            'parameters' =>
                                 [
                                    0 =>
                                         [
                                            'name' => 'id',
                                            'in' => 'path',
                                            'required' => true,
                                            'description' => 'ID of entity test-resource',
                                            'schema' =>
                                                 [
                                                    'type' => 'integer',
                                                ],
                                        ],
                                    1 =>
                                         [
                                            'name' => 'Content-Type',
                                            'in' => 'header',
                                            'description' => 'Content type of request body.',
                                            'required' => true,
                                            'schema' =>
                                                 [
                                                    'type' => 'string',
                                                    'example' => 'application/json',
                                                ],
                                        ],
                                    2 =>
                                         [
                                            'name' => 'Accept',
                                            'in' => 'header',
                                            'description' => 'The Accept request HTTP header indicates which content types, expressed as MIME types, the client is able to understand.',
                                            'required' => true,
                                            'schema' =>
                                                 [
                                                    'type' => 'string',
                                                    'example' => 'application/json',
                                                ],
                                        ],
                                ],
                            'responses' =>
                                 [
                                    200 =>
                                         [
                                            'description' => 'Expected response to a valid request returned successfully.',
                                            'content' =>
                                                 [
                                                    'application/json' =>
                                                         [
                                                            'schema' =>
                                                                 [
                                                                    'type' => 'array',
                                                                    'items' =>
                                                                         [
                                                                            'type' => 'object',
                                                                            'properties' =>
                                                                                 [
                                                                                    'test' =>
                                                                                         [
                                                                                            'type' => 'string',
                                                                                        ],
                                                                                ],
                                                                        ],
                                                                ],
                                                        ],
                                                ],
                                        ],
                                     403 =>
                                         [
                                             'description' => 'Unauthorized request.',
                                             'content' =>
                                                 [
                                                     'application/json' =>
                                                         [
                                                             'schema' =>
                                                                 [
                                                                     '$ref' => '#/components/schemas/RestErrorMessage',
                                                                 ],
                                                         ],
                                                 ],
                                         ],
                                    404 =>
                                         [
                                            'description' => 'Not Found.',
                                            'content' =>
                                                 [
                                                    'application/json' =>
                                                         [
                                                            'schema' =>
                                                                 [
                                                                    '$ref' => '#/components/schemas/RestErrorMessage',
                                                                ],
                                                        ],
                                                ],
                                        ],
                                    'default' =>
                                         [
                                            'description' => 'An error occurred.',
                                            'content' =>
                                                 [
                                                    'application/json' =>
                                                         [
                                                            'schema' =>
                                                                 [
                                                                    '$ref' => '#/components/schemas/RestErrorMessage',
                                                                ],
                                                        ],
                                                ],
                                        ],
                                ],
                        ],
                ],
        ],
];
