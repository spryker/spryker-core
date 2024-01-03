<?php

return [
  'paths' =>
   [
    '/dynamic-entity-prefix-put/test-resource' =>
     [
      'put' =>
       [
        'tags' =>
         [
          0 => 'dynamic-entity-test-resource',
        ],
        'operationId' => 'upsert-collection-dynamic-api-test-resource',
        'summary' => 'Upsert collection of test-resource entities',
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
          'description' => 'Data to create new or update collection of entities.  Request data can contain also child relation, for example: `{ ...fields, test-child-relation-0: { ...childFields } }`.',
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
                          ],
                          'test-child-relation-0' =>
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
                          'test-child-relation-1' =>
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
                          'test-child-relation-2' =>
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
                      1 =>
                       [
                        'type' => 'object',
                        'properties' =>
                         [
                          'test' =>
                           [
                            'type' => 'string',
                          ],
                          'test-child-relation-0' =>
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
                          'test-child-relation-1' =>
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
                          'test-child-relation-2' =>
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
                      2 =>
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
                            ],
                            'test-child-relation-0' =>
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
                            'test-child-relation-1' =>
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
                            'test-child-relation-2' =>
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
                        1 =>
                         [
                          'type' => 'object',
                          'properties' =>
                           [
                            'test' =>
                             [
                              'type' => 'string',
                            ],
                            'test-child-relation-0' =>
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
                            'test-child-relation-1' =>
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
                            'test-child-relation-2' =>
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
                        2 =>
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
    '/dynamic-entity-prefix-put/test-resource/{id}' =>
     [
      'put' =>
       [
        'tags' =>
         [
          0 => 'dynamic-entity-test-resource',
        ],
        'operationId' => 'upsert-entity-dynamic-api-test-resource',
        'summary' => 'Upsert entity of test-resource',
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
                          ],
                          'test-child-relation-0' =>
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
                          'test-child-relation-1' =>
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
                          'test-child-relation-2' =>
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
                      1 =>
                       [
                        'type' => 'object',
                        'properties' =>
                         [
                          'test' =>
                           [
                            'type' => 'string',
                          ],
                          'test-child-relation-0' =>
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
                          'test-child-relation-1' =>
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
                          'test-child-relation-2' =>
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
                      2 =>
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
                            ],
                            'test-child-relation-0' =>
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
                            'test-child-relation-1' =>
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
                            'test-child-relation-2' =>
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
                        1 =>
                         [
                          'type' => 'object',
                          'properties' =>
                           [
                            'test' =>
                             [
                              'type' => 'string',
                            ],
                            'test-child-relation-0' =>
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
                            'test-child-relation-1' =>
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
                            'test-child-relation-2' =>
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
                        2 =>
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
