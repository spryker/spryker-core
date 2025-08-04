<?php

declare(strict_types = 1);

return [
  'paths' =>
   [
    '/collection' =>
     [
      'get' =>
       [
      ],
      'post' =>
       [
      ],
      'put' =>
       [
      ],
      'patch' =>
       [
      ],
    ],
    '/dynamic-entity/test' =>
     [
      'get' =>
       [
        'tags' =>
         [
          0 => 'dynamic-entity-test',
        ],
        'operationId' => 'get-collection-dynamic-api-test',
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
                'test.id' =>
                 [
                  'type' => 'integer',
                ],
                'test.field_string' =>
                 [
                  'type' => 'string',
                ],
                'test.field_decimal' =>
                 [
                  'type' => 'string',
                ],
                'test.field_boolean' =>
                 [
                  'type' => 'boolean',
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
            'description' => 'Get item of entities for defined resource.',
            'content' =>
             [
              'application/json' =>
               [
                'schema' =>
                 [
                  'type' => 'object',
                  'properties' =>
                   [
                    'data' =>
                     [
                      'type' => 'array',
                      'items' =>
                       [
                        'type' => 'object',
                        'properties' =>
                         [
                          'id' =>
                           [
                            'type' => 'integer',
                            'minimum' => 1,
                            'maximum' => 100,
                            'example' => '123',
                            'title' => 'id',
                            'description' => 'Id',
                          ],
                          'field_string' =>
                           [
                            'type' => 'string',
                            'minLength' => 1,
                            'maxLength' => 5,
                            'title' => 'field_string',
                            'description' => 'Field_string',
                          ],
                          'field_decimal' =>
                           [
                            'type' => 'string',
                            'minLength' => 1,
                            'maxLength' => 5,
                            'title' => 'field_decimal',
                            'description' => 'Field_decimal',
                          ],
                          'field_boolean' =>
                           [
                            'type' => 'boolean',
                            'example' => 'true',
                            'title' => 'field_boolean',
                            'description' => 'Field_boolean',
                          ],
                        ],
                        'required' => [
                           'id',
                           'field_string',
                           'field_decimal',
                           'field_boolean',
                        ],
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
      'post' =>
       [
        'tags' =>
         [
          0 => 'dynamic-entity-test',
        ],
        'operationId' => 'save-collection-dynamic-api-test',
        'summary' => 'Save collection of test',
        'parameters' =>
         [
          0 =>
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
          1 =>
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
        'requestBody' =>
         [
          'description' => 'Data to create new entities. ',
          'required' => true,
          'content' =>
           [
            'application/json' =>
             [
              'schema' =>
               [
                'type' => 'object',
                'properties' =>
                 [
                  'data' =>
                   [
                    'type' => 'array',
                    'items' =>
                     [
                      'type' => 'object',
                      'properties' =>
                       [
                        'id' =>
                         [
                          'type' => 'integer',
                          'minimum' => 1,
                          'maximum' => 100,
                          'example' => '123',
                          'title' => 'id',
                          'description' => 'Id',
                        ],
                        'field_string' =>
                        [
                           'type' => 'string',
                           'minLength' => 1,
                           'maxLength' => 5,
                           'title' => 'field_string',
                           'description' => 'Field_string',
                        ],
                        'field_decimal' =>
                        [
                           'type' => 'string',
                           'minLength' => 1,
                           'maxLength' => 5,
                           'title' => 'field_decimal',
                           'description' => 'Field_decimal',
                        ],
                        'field_boolean' =>
                        [
                           'type' => 'boolean',
                           'example' => 'true',
                           'title' => 'field_boolean',
                           'description' => 'Field_boolean',
                        ],
                      ],
                         'required' => [
                             'id',
                             'field_string',
                             'field_decimal',
                             'field_boolean',
                         ],
                    ],
                  ],
                ],
              ],
            ],
          ],
        ],
        'responses' =>
         [
          201 =>
           [
            'description' => 'Created entities.',
            'content' =>
             [
              'application/json' =>
               [
                'schema' =>
                 [
                  'type' => 'object',
                  'properties' =>
                   [
                    'data' =>
                     [
                      'type' => 'array',
                      'items' =>
                       [
                        'type' => 'object',
                        'properties' =>
                         [
                          'id' =>
                           [
                            'type' => 'integer',
                            'minimum' => 1,
                            'maximum' => 100,
                            'example' => '123',
                            'title' => 'id',
                            'description' => 'Id',
                          ],
                             'field_string' =>
                                 [
                                     'type' => 'string',
                                     'minLength' => 1,
                                     'maxLength' => 5,
                                     'title' => 'field_string',
                                     'description' => 'Field_string',
                                 ],
                             'field_decimal' =>
                                 [
                                     'type' => 'string',
                                     'minLength' => 1,
                                     'maxLength' => 5,
                                     'title' => 'field_decimal',
                                     'description' => 'Field_decimal',
                                 ],
                             'field_boolean' =>
                                 [
                                     'type' => 'boolean',
                                     'example' => 'true',
                                     'title' => 'field_boolean',
                                     'description' => 'Field_boolean',
                                 ],
                        ],
                           'required' => [
                               'id',
                               'field_string',
                               'field_decimal',
                               'field_boolean',
                           ],
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
      'put' =>
       [
        'tags' =>
         [
          0 => 'dynamic-entity-test',
        ],
        'operationId' => 'upsert-collection-dynamic-api-test',
        'summary' => 'Upsert collection of test entities',
        'parameters' =>
         [
          0 =>
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
          1 =>
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
        'requestBody' =>
         [
          'description' => 'Data to create new or update collection of entities. ',
          'required' => true,
          'content' =>
           [
            'application/json' =>
             [
              'schema' =>
               [
                'type' => 'object',
                'properties' =>
                 [
                  'data' =>
                   [
                    'type' => 'array',
                    'items' =>
                     [
                      'type' => 'object',
                      'properties' =>
                       [
                        'id' =>
                         [
                          'type' => 'integer',
                          'minimum' => 1,
                          'maximum' => 100,
                          'example' => '123',
                          'title' => 'id',
                          'description' => 'Id',
                        ],
                           'field_string' =>
                               [
                                   'type' => 'string',
                                   'minLength' => 1,
                                   'maxLength' => 5,
                                   'title' => 'field_string',
                                   'description' => 'Field_string',
                               ],
                           'field_decimal' =>
                               [
                                   'type' => 'string',
                                   'minLength' => 1,
                                   'maxLength' => 5,
                                   'title' => 'field_decimal',
                                   'description' => 'Field_decimal',
                               ],
                           'field_boolean' =>
                               [
                                   'type' => 'boolean',
                                   'example' => 'true',
                                   'title' => 'field_boolean',
                                   'description' => 'Field_boolean',
                               ],
                      ],
                         'required' => [
                             'id',
                             'field_string',
                             'field_decimal',
                             'field_boolean',
                         ],
                    ],
                  ],
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
                  'type' => 'object',
                  'properties' =>
                   [
                    'data' =>
                     [
                      'type' => 'array',
                      'items' =>
                       [
                        'type' => 'object',
                        'properties' =>
                         [
                          'id' =>
                           [
                            'type' => 'integer',
                            'minimum' => 1,
                            'maximum' => 100,
                            'example' => '123',
                            'title' => 'id',
                            'description' => 'Id',
                          ],
                             'field_string' =>
                                 [
                                     'type' => 'string',
                                     'minLength' => 1,
                                     'maxLength' => 5,
                                     'title' => 'field_string',
                                     'description' => 'Field_string',
                                 ],
                             'field_decimal' =>
                                 [
                                     'type' => 'string',
                                     'minLength' => 1,
                                     'maxLength' => 5,
                                     'title' => 'field_decimal',
                                     'description' => 'Field_decimal',
                                 ],
                             'field_boolean' =>
                                 [
                                     'type' => 'boolean',
                                     'example' => 'true',
                                     'title' => 'field_boolean',
                                     'description' => 'Field_boolean',
                                 ],
                         ],
                           'required' => [
                               'id',
                               'field_string',
                               'field_decimal',
                               'field_boolean',
                           ],
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
      'patch' =>
       [
        'tags' =>
         [
          0 => 'dynamic-entity-test',
        ],
        'operationId' => 'update-collection-dynamic-api-test',
        'summary' => 'Update collection of test',
        'parameters' =>
         [
          0 =>
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
          1 =>
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
        'requestBody' =>
         [
          'description' => 'Data to update collection of entities. ',
          'required' => true,
          'content' =>
           [
            'application/json' =>
             [
              'schema' =>
               [
                'type' => 'object',
                'properties' =>
                 [
                  'data' =>
                   [
                    'type' => 'array',
                    'items' =>
                     [
                      'type' => 'object',
                      'properties' =>
                       [
                        'id' =>
                         [
                          'type' => 'integer',
                          'minimum' => 1,
                          'maximum' => 100,
                          'example' => '123',
                          'title' => 'id',
                          'description' => 'Id',
                        ],
                           'field_string' =>
                               [
                                   'type' => 'string',
                                   'minLength' => 1,
                                   'maxLength' => 5,
                                   'title' => 'field_string',
                                   'description' => 'Field_string',
                               ],
                           'field_decimal' =>
                               [
                                   'type' => 'string',
                                   'minLength' => 1,
                                   'maxLength' => 5,
                                   'title' => 'field_decimal',
                                   'description' => 'Field_decimal',
                               ],
                           'field_boolean' =>
                               [
                                   'type' => 'boolean',
                                   'example' => 'true',
                                   'title' => 'field_boolean',
                                   'description' => 'Field_boolean',
                               ],
                      ],
                         'required' => [
                             'id',
                             'field_string',
                             'field_decimal',
                             'field_boolean',
                         ],
                    ],
                  ],
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
                  'type' => 'object',
                  'properties' =>
                   [
                    'data' =>
                     [
                      'type' => 'array',
                      'items' =>
                       [
                        'type' => 'object',
                        'properties' =>
                         [
                          'id' =>
                           [
                            'type' => 'integer',
                            'minimum' => 1,
                            'maximum' => 100,
                            'example' => '123',
                            'title' => 'id',
                            'description' => 'Id',
                          ],
                             'field_string' =>
                                 [
                                     'type' => 'string',
                                     'minLength' => 1,
                                     'maxLength' => 5,
                                     'title' => 'field_string',
                                     'description' => 'Field_string',
                                 ],
                             'field_decimal' =>
                                 [
                                     'type' => 'string',
                                     'minLength' => 1,
                                     'maxLength' => 5,
                                     'title' => 'field_decimal',
                                     'description' => 'Field_decimal',
                                 ],
                             'field_boolean' =>
                                 [
                                     'type' => 'boolean',
                                     'example' => 'true',
                                     'title' => 'field_boolean',
                                     'description' => 'Field_boolean',
                                 ],
                        ],
                           'required' => [
                               'id',
                               'field_string',
                               'field_decimal',
                               'field_boolean',
                           ],
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
    '/dynamic-entity/test/{id}' =>
     [
      'get' =>
       [
        'tags' =>
         [
          0 => 'dynamic-entity-test',
        ],
        'operationId' => 'get-entity-dynamic-api-test',
        'summary' => 'Get item of entities for defined resource.',
        'parameters' =>
         [
          0 =>
           [
            'name' => 'id',
            'in' => 'path',
            'required' => true,
            'description' => 'ID of entity test',
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
            'description' => 'Get item of entities for defined resource.',
            'content' =>
             [
              'application/json' =>
               [
                'schema' =>
                 [
                  'type' => 'object',
                  'properties' =>
                   [
                    'data' =>
                     [
                      'type' => 'object',
                      'properties' =>
                       [
                        'id' =>
                         [
                          'type' => 'integer',
                          'minimum' => 1,
                          'maximum' => 100,
                          'example' => '123',
                          'title' => 'id',
                          'description' => 'Id',
                        ],
                           'field_string' =>
                               [
                                   'type' => 'string',
                                   'minLength' => 1,
                                   'maxLength' => 5,
                                   'title' => 'field_string',
                                   'description' => 'Field_string',
                               ],
                           'field_decimal' =>
                               [
                                   'type' => 'string',
                                   'minLength' => 1,
                                   'maxLength' => 5,
                                   'title' => 'field_decimal',
                                   'description' => 'Field_decimal',
                               ],
                           'field_boolean' =>
                               [
                                   'type' => 'boolean',
                                   'example' => 'true',
                                   'title' => 'field_boolean',
                                   'description' => 'Field_boolean',
                               ],
                      ],
                      'required' => [
                         'id',
                         'field_string',
                         'field_decimal',
                         'field_boolean',
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
      'put' =>
       [
        'tags' =>
         [
          0 => 'dynamic-entity-test',
        ],
        'operationId' => 'upsert-entity-dynamic-api-test',
        'summary' => 'Upsert entity of test',
        'parameters' =>
         [
          0 =>
           [
            'name' => 'id',
            'in' => 'path',
            'required' => true,
            'description' => 'ID of entity test',
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
        'requestBody' =>
         [
          'description' => 'Data to create new or update entity.',
          'required' => true,
          'content' =>
           [
            'application/json' =>
             [
              'schema' =>
               [
                'type' => 'object',
                'properties' =>
                 [
                  'data' =>
                   [
                    'type' => 'object',
                    'properties' =>
                     [
                      'id' =>
                       [
                        'type' => 'integer',
                        'minimum' => 1,
                        'maximum' => 100,
                        'example' => '123',
                        'title' => 'id',
                        'description' => 'Id',
                      ],
                         'field_string' =>
                             [
                                 'type' => 'string',
                                 'minLength' => 1,
                                 'maxLength' => 5,
                                 'title' => 'field_string',
                                 'description' => 'Field_string',
                             ],
                         'field_decimal' =>
                             [
                                 'type' => 'string',
                                 'minLength' => 1,
                                 'maxLength' => 5,
                                 'title' => 'field_decimal',
                                 'description' => 'Field_decimal',
                             ],
                         'field_boolean' =>
                             [
                                 'type' => 'boolean',
                                 'example' => 'true',
                                 'title' => 'field_boolean',
                                 'description' => 'Field_boolean',
                             ],
                    ],
                       'required' => [
                           'id',
                           'field_string',
                           'field_decimal',
                           'field_boolean',
                       ],
                  ],
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
                  'type' => 'object',
                  'properties' =>
                   [
                    'data' =>
                     [
                      'type' => 'object',
                      'properties' =>
                       [
                        'id' =>
                         [
                          'type' => 'integer',
                          'minimum' => 1,
                          'maximum' => 100,
                          'example' => '123',
                          'title' => 'id',
                          'description' => 'Id',
                        ],
                           'field_string' =>
                               [
                                   'type' => 'string',
                                   'minLength' => 1,
                                   'maxLength' => 5,
                                   'title' => 'field_string',
                                   'description' => 'Field_string',
                               ],
                           'field_decimal' =>
                               [
                                   'type' => 'string',
                                   'minLength' => 1,
                                   'maxLength' => 5,
                                   'title' => 'field_decimal',
                                   'description' => 'Field_decimal',
                               ],
                           'field_boolean' =>
                               [
                                   'type' => 'boolean',
                                   'example' => 'true',
                                   'title' => 'field_boolean',
                                   'description' => 'Field_boolean',
                               ],
                      ],
                         'required' => [
                             'id',
                             'field_string',
                             'field_decimal',
                             'field_boolean',
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
      'patch' =>
       [
        'tags' =>
         [
          0 => 'dynamic-entity-test',
        ],
        'operationId' => 'update-entity-dynamic-api-test',
        'summary' => 'Update entity of test',
        'parameters' =>
         [
          0 =>
           [
            'name' => 'id',
            'in' => 'path',
            'required' => true,
            'description' => 'ID of entity test',
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
        'requestBody' =>
         [
          'description' => 'Data to update entity. ',
          'required' => true,
          'content' =>
           [
            'application/json' =>
             [
              'schema' =>
               [
                'type' => 'object',
                'properties' =>
                 [
                  'data' =>
                   [
                    'type' => 'object',
                    'properties' =>
                     [
                      'id' =>
                       [
                        'type' => 'integer',
                        'minimum' => 1,
                        'maximum' => 100,
                        'example' => '123',
                        'title' => 'id',
                        'description' => 'Id',
                      ],
                         'field_string' =>
                             [
                                 'type' => 'string',
                                 'minLength' => 1,
                                 'maxLength' => 5,
                                 'title' => 'field_string',
                                 'description' => 'Field_string',
                             ],
                         'field_decimal' =>
                             [
                                 'type' => 'string',
                                 'minLength' => 1,
                                 'maxLength' => 5,
                                 'title' => 'field_decimal',
                                 'description' => 'Field_decimal',
                             ],
                         'field_boolean' =>
                             [
                                 'type' => 'boolean',
                                 'example' => 'true',
                                 'title' => 'field_boolean',
                                 'description' => 'Field_boolean',
                             ],
                    ],
                       'required' => [
                           'id',
                           'field_string',
                           'field_decimal',
                           'field_boolean',
                       ],
                  ],
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
                  'type' => 'object',
                  'properties' =>
                   [
                    'data' =>
                     [
                      'type' => 'object',
                      'properties' =>
                       [
                        'id' =>
                         [
                          'type' => 'integer',
                          'minimum' => 1,
                          'maximum' => 100,
                          'example' => '123',
                          'title' => 'id',
                          'description' => 'Id',
                        ],
                           'field_string' =>
                               [
                                   'type' => 'string',
                                   'minLength' => 1,
                                   'maxLength' => 5,
                                   'title' => 'field_string',
                                   'description' => 'Field_string',
                               ],
                           'field_decimal' =>
                               [
                                   'type' => 'string',
                                   'minLength' => 1,
                                   'maxLength' => 5,
                                   'title' => 'field_decimal',
                                   'description' => 'Field_decimal',
                               ],
                           'field_boolean' =>
                               [
                                   'type' => 'boolean',
                                   'example' => 'true',
                                   'title' => 'field_boolean',
                                   'description' => 'Field_boolean',
                               ],
                      ],
                         'required' => [
                             'id',
                             'field_string',
                             'field_decimal',
                             'field_boolean',
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
