<?php

return [
  'paths' =>
   [
    '/dynamic-entity-prefix-patch/test-resource' =>
     [
      'patch' =>
       [
        'tags' =>
         [
          0 => 'dynamic-entity-test-resource',
        ],
        'operationId' => 'update-collection-dynamic-api-test-resource',
        'summary' => 'Update collection of test-resource',
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
          'description' => 'Data to update collection of entities.  Request data can contain also child relation, for example: `{ ...fields, test-first-level-child-relation: { ...childFields } }`.',
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
                    'oneOf' =>
                     [
                      0 =>
                       [
                        'type' => 'object',
                        'properties' =>
                         [
                          'test' =>

                           [

                            'type' => 'string',

                            'example' => 'John Doe',

                            'title' => 'test',

                            'description' => 'Test',

                          ],
                          'test-first-level-child-relation' =>
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

                                  'example' => 'John Doe',

                                  'title' => 'test',

                                  'description' => 'Test',

                                ],
                              ],
                            ],
                          ],
                        ],
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
                      'oneOf' =>
                       [
                        0 =>
                         [
                          'type' => 'object',
                          'properties' =>
                           [
                            'test' =>

                             [

                              'type' => 'string',

                              'example' => 'John Doe',

                              'title' => 'test',

                              'description' => 'Test',

                            ],
                            'test-first-level-child-relation' =>
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

                                    'example' => 'John Doe',

                                    'title' => 'test',

                                    'description' => 'Test',

                                  ],
                                ],
                              ],
                            ],
                          ],
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
    '/dynamic-entity-prefix-patch/test-resource/{id}' =>
     [
      'patch' =>
       [
        'tags' =>
         [
          0 => 'dynamic-entity-test-resource',
        ],
        'operationId' => 'update-entity-dynamic-api-test-resource',
        'summary' => 'Update entity of test-resource',
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
        'requestBody' =>
         [
          'description' => 'Data to update entity.  Request data can contain also child relation, for example: `{ ...fields, test-first-level-child-relation: { ...childFields } }`.',
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
                    'oneOf' =>
                     [
                      0 =>
                       [
                        'type' => 'object',
                        'properties' =>
                         [
                          'test' =>

                           [

                            'type' => 'string',

                            'example' => 'John Doe',

                            'title' => 'test',

                            'description' => 'Test',

                          ],
                          'test-first-level-child-relation' =>
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

                                  'example' => 'John Doe',

                                  'title' => 'test',

                                  'description' => 'Test',

                                ],
                              ],
                            ],
                          ],
                        ],
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
                      'oneOf' =>
                       [
                        0 =>
                         [
                          'type' => 'object',
                          'properties' =>
                           [
                            'test' =>

                             [

                              'type' => 'string',

                              'example' => 'John Doe',

                              'title' => 'test',

                              'description' => 'Test',

                            ],
                            'test-first-level-child-relation' =>
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

                                    'example' => 'John Doe',

                                    'title' => 'test',

                                    'description' => 'Test',

                                  ],
                                ],
                              ],
                            ],
                          ],
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
