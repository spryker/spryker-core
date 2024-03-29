<?php

return [
  'paths' =>
   [
    '/dynamic-entity-prefix/test-resource' =>
     [
      'post' =>
       [
        'tags' =>
         [
          0 => 'dynamic-entity-test-resource',
        ],
        'operationId' => 'save-collection-dynamic-api-test-resource',
        'summary' => 'Save collection of test-resource',
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
          'description' => 'Data to create new entities.  Request data can contain also child relation, for example: `{ ...fields, test-first-level-child-relation: { ...childFields } }`.',
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
  ],
];
