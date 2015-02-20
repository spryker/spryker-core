# Spryker SaltStack


## Port numbering
For all services, there is a constant port numbering scheme. Each of the digit has a meaning:

LEDDC

Where:
### L - Listener
1 for applications with one / default listener only, 1/2/... for applications with more than one
possible listenere (for example, Elasticsearch has both HTTP and Transport ports).

| ID     | Listener                                  |
| ------ | ----------------------------------------- |
| 1      | default (*) / HTTP (NginX, Elasticsearch) |
| 2      | Transport (Elasticsearch)                 |

### E - Environment

| ID     | Environment                               |
| ------ | ----------------------------------------- |
| 5      | Production                                |
| 3      | Staging                                   |
| 1      | Testing                                   |
| 0      | Development                               |


### DD - AppDomain for multiple country instances
Default value: 00 (appropiate for ALL single-languages components)

| AppDomain | Country name (English) | Store | Default language |
| ------ | ------------------------- | ----- | ---------------- |
| 00     | Germany (or default)      | DE    | de_DE            |
| 01     | Poland                    | PL    | pl_PL            |
| 02     | France                    | FR    | fr_FR            |
| 03     | Austria                   | AT    | de_AT            |
| 04     | Netherlands               | NL    | nl_NL            |
| 05     | Switzerland               | CH    | de_CH            |
| 06     | Brazil                    | BR    | pt_BR            |
| 07     | United Kingdom            | UK    | en_UK            |
| 08     | Italy                     | IT    | it_IT            |
| 09     | Belgium                   | BE    | nl_BE            |
| 10     | USA                       | US    | en_US            |
| 11     | Mexico                    | MX    | es_MX            |
| 12     | Argentina                 | AR    | es_AR            |
| 13     | Chile                     | CL    | es_CL            |
| 14     | Columbia                  | CO    | es_CO            |
| 15     | Canada                    | CA    |                  |
| 16     | Spain                     | ES    | es_ES            |
| 17     | Portugal                  | PT    | pt_PT            |
| 18     | Ireland                   | IE    |                  |
| 19     | Denmark                   | DK    |                  |
| 20     | Sweden                    | SE    |                  |
| 21     | Norway                    | NO    |                  |
| 22     | Finland                   | FI    |                  |
| 23     | Czech Republic            | CZ    |                  |
| 24     | Slovakia                  | SK    |                  |
| 25     | Hungary                   | HU    |                  |
| 26     | Greece                    | GR    |                  |
| 27     | Slovenia                  | SI    |                  |
| 28     | Romania                   | RO    |                  |
| 29     | Croatia                   | HR    |                  |
| 30     | Turkey                    | TR    |                  |
| ...    |                           |       |                  |
| 99     | (reserved) International  | COM   | en_UK            |
| 99     | (reserved) Europe         | EU    | en_UK            |


### C - Component, from following list:

| ID     | Component                                 |
| ------ | ----------------------------------------- |
| 0      | Yves                                      |
| 1      | Zed                                       |
| 2      | Static web content                        |
| 3      |                                           |
| 4      |                                           |
| 5      | Search (elasticsearch)                    |
| 6      | Queue (rabbitMQ)                          |
| 7      | Jenkins                                   |
| 8      | Cache (memcached)                         |
| 9      | K/V Datastore (redis)                     |

Examples:
 - 15000 - Production YVES, Germany, HTTP
 - 15101 - Production ZED, USA, HTTP
 - 13007 - Staging Jenkins, HTTP
 - 10006 - Development Elasticsearch, HTTP
