# WordPress Pay Gateway: Sisow

**Sisow driver for the WordPress payment processing library.**

[![Build Status](https://travis-ci.org/wp-pay-gateways/sisow.svg?branch=develop)](https://travis-ci.org/wp-pay-gateways/sisow)
[![Coverage Status](https://coveralls.io/repos/wp-pay-gateways/sisow/badge.svg?branch=master&service=github)](https://coveralls.io/github/wp-pay-gateways/sisow?branch=master)
[![Latest Stable Version](https://poser.pugx.org/wp-pay-gateways/sisow/v/stable.svg)](https://packagist.org/packages/wp-pay-gateways/sisow)
[![Total Downloads](https://poser.pugx.org/wp-pay-gateways/sisow/downloads.svg)](https://packagist.org/packages/wp-pay-gateways/sisow)
[![Latest Unstable Version](https://poser.pugx.org/wp-pay-gateways/sisow/v/unstable.svg)](https://packagist.org/packages/wp-pay-gateways/sisow)
[![License](https://poser.pugx.org/wp-pay-gateways/sisow/license.svg)](https://packagist.org/packages/wp-pay-gateways/sisow)
[![Built with Grunt](https://cdn.gruntjs.com/builtwith.svg)](http://gruntjs.com/)

## Strange Behavior Sisow API

Please note that if you send a Sisow `TransactionRequest` with a empty `issuerid` Sisow will not return a `trxid` in the `transactionrequest` XML response message.
So if the Sisow transaction IDs are empty you probably left the `issuerid` empty in the `TransactionRequest` message.

## Documentation

*	[Sisow REST API v3.2.1](http://pronamic.nl/wp-content/uploads/2014/11/sisow-rest-api-v3.2.1.pdf)
