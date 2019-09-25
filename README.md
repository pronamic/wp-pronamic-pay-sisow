# WordPress Pay Gateway: Sisow

**Sisow driver for the WordPress payment processing library.**

[![Build Status](https://travis-ci.org/wp-pay-gateways/sisow.svg?branch=develop)](https://travis-ci.org/wp-pay-gateways/sisow)
[![Coverage Status](https://coveralls.io/repos/wp-pay-gateways/sisow/badge.svg?branch=master&service=github)](https://coveralls.io/github/wp-pay-gateways/sisow?branch=master)
[![Latest Stable Version](https://poser.pugx.org/wp-pay-gateways/sisow/v/stable.svg)](https://packagist.org/packages/wp-pay-gateways/sisow)
[![Total Downloads](https://poser.pugx.org/wp-pay-gateways/sisow/downloads.svg)](https://packagist.org/packages/wp-pay-gateways/sisow)
[![Latest Unstable Version](https://poser.pugx.org/wp-pay-gateways/sisow/v/unstable.svg)](https://packagist.org/packages/wp-pay-gateways/sisow)
[![License](https://poser.pugx.org/wp-pay-gateways/sisow/license.svg)](https://packagist.org/packages/wp-pay-gateways/sisow)
[![Built with Grunt](http://cdn.gruntjs.com/builtwith.svg)](http://gruntjs.com/)

## Strange Behavior Sisow API

Please note that if you send a Sisow `TransactionRequest` with a empty `issuerid` Sisow will not return a `trxid` in the `transactionrequest` XML response message.
So if the Sisow transaction IDs are empty you probably left the `issuerid` empty in the `TransactionRequest` message.

## Documentation

| Title                | Version | Date       | Link                                  |
| -------------------- | ------- | ---------- | ------------------------------------- |
| Sisow REST API       | 5.3.0   | 05-09-2019 | [Download][sisow-rest-api-v5.3.0]     |
| Sisow REST API       | 5.2.0   |            | [Download][sisow-rest-api-v5.2.0]     |
| Sisow REST API       | 5.1.0   |            | [Download][sisow-rest-api-v5.1.0]     |
| Sisow REST API       | 5.0.0   |            | [Download][sisow-rest-api-v5.0.1]     |
| Sisow REST API       | 5.0.0   |            | [Download][sisow-rest-api-v5.0.0]     |
| Sisow REST API       | 3.2.1   |            | [Download][sisow-rest-api-v3.2.1]     |
| Sisow WebService API | 2.0     |            | [Download][sisow-webservice-api-v2.0] |

[sisow-rest-api-v5.3.0]: documentation/rest530.pdf
[sisow-rest-api-v5.2.0]: documentation/rest520.pdf
[sisow-rest-api-v5.1.0]: documentation/rest510.pdf
[sisow-rest-api-v5.0.1]: documentation/rest501.pdf
[sisow-rest-api-v5.0.0]: https://www.pronamic.nl/wp-content/uploads/2018/01/Sisow-REST-API-Versie-5.0.0.pdf
[sisow-rest-api-v3.2.1]: https://www.pronamic.nl/wp-content/uploads/2014/11/sisow-rest-api-v3.2.1.pdf
[sisow-webservice-api-v2.0]: documentation/WEbservice.pdf
