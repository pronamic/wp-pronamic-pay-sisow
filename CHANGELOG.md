# Change Log

All notable changes to this project will be documented in this file.

This projects adheres to [Semantic Versioning](http://semver.org/) and [Keep a CHANGELOG](http://keepachangelog.com/).

## [Unreleased][unreleased]
-

## [4.1.0] - 2022-04-11
- No longer depend on core gateway mode.

## [4.0.0] - 2022-01-11
### Changed
- Updated to https://github.com/pronamic/wp-pay-core/releases/tag/4.0.0.

## [3.0.0] - 2021-08-05
- Updated to `pronamic/wp-pay-core` version `3.0.0`.
- Updated to `pronamic/wp-money` version `2.0.0`.
- Switched to `pronamic/wp-coding-standards`.

## [2.1.1] - 2021-04-26
- Happy 2021.

## [2.1.0] - 2020-03-19
- Catch exception in gateway instead of client.
- Extend from AbstractGatewayIntegration class.

## [2.0.4] - 2019-12-22
- Added support for new `pronamic_pay_return_should_redirect` filter for notify and callback processing.
- Added URL to manual in gateway settings.
- Improved status updates for payments without transaction ID (i.e. iDEAL QR and iDEAL without issuer).
- Improved getting active payment methods for account.
- Improved error handling with exceptions.
- Updated usage of deprecated `get_cents()` method.
- Updated payment status class name.

## [2.0.3] - 2019-08-30
- Fix fatal error "Uncaught Error: Call to a member function get_cents() on null".

## [2.0.2] - 2019-08-28
- Updated packages.

## [2.0.1] - 2018-12-10
- Added support for payment lines, shipping, billing and customer data.
- Added support for Billink.
- Added support for Capayable.

## [2.0.0] - 2018-05-11
- WordPress Coding Standards improvements.

## [1.2.4] - 2017-12-12
- WordPress Coding Standards improvements.

## [1.2.3] - 2017-09-13
- Added support for bunq payment method.

## [1.2.2] - 2017-04-10
- Added support for PayPal, Sofort and 'leap of faith' payment methods.

## [1.2.1] - 2016-10-20
- Only send status check if transaction ID is not empty.
- Added feature support `payment_status_request`.
- Added support for new Bancontact constant.

## [1.2.0] - 2016-06-14
- Added support for bank transfer payment method.

## [1.1.9] - 2016-06-10
- Fixed "Fatal error: Uncaught Error: Call to a member function get_order_id() on null".

## [1.1.8] - 2016-06-08
- Simplified the gateway payment start function.

## [1.1.7] - 2016-03-22
- Updated gateway settings.
- Added product and dashboard URLs.

## [1.1.6] - 2016-03-02
- Added an get_settings function.
- Moved get_gateway_class() function to the configuration class.
- Removed get_config_class(), no longer required.

## [1.1.5] - 2016-02-11
- Use iDEAL payment method also if none set in issuer field.

## [1.1.4] - 2016-02-10
- Set default payment method to iDEAL if none set.

## [1.1.3] - 2016-02-05
- Fixed 'Fatal error: Call to a member function set_payment_method() on null'.

## [1.1.2] - 2016-02-01
- Added an gateway settings class.
- Added support for creditcard payment method.

## [1.1.1] - 2015-08-04
- Use wp-pay/core XML security filter function.

## [1.1.0] - 2015-05-26
### Added
- Added support for Shop ID.

## [1.0.3] - 2015-03-03
- Changed WordPress pay core library requirement from `~1.0.0` to `>=1.0.0`.

## [1.0.2] - 2015-01-20
- Require WordPress pay core library version 1.0.0.

## [1.0.1] - 2014-12-12
- Improved error handling.

## 1.0.0 - 2014-12-12
- First release.

[unreleased]: https://github.com/wp-pay-gateways/sisow/compare/4.1.0...HEAD
[4.1.0]: https://github.com/wp-pay-gateways/sisow/compare/4.0.0...4.1.0
[4.0.0]: https://github.com/wp-pay-gateways/sisow/compare/3.0.0...4.0.0
[3.0.0]: https://github.com/wp-pay-gateways/sisow/compare/2.1.1...3.0.0
[2.1.1]: https://github.com/wp-pay-gateways/sisow/compare/2.1.0...2.1.1
[2.1.0]: https://github.com/wp-pay-gateways/sisow/compare/2.0.4...2.1.0
[2.0.4]: https://github.com/wp-pay-gateways/sisow/compare/2.0.3...2.0.4
[2.0.3]: https://github.com/wp-pay-gateways/sisow/compare/2.0.2...2.0.3
[2.0.2]: https://github.com/wp-pay-gateways/sisow/compare/2.0.1...2.0.2
[2.0.1]: https://github.com/wp-pay-gateways/sisow/compare/2.0.0...2.0.1
[2.0.0]: https://github.com/wp-pay-gateways/sisow/compare/1.2.4...2.0.0
[1.2.4]: https://github.com/wp-pay-gateways/sisow/compare/1.2.3...1.2.4
[1.2.3]: https://github.com/wp-pay-gateways/sisow/compare/1.2.2...1.2.3
[1.2.2]: https://github.com/wp-pay-gateways/sisow/compare/1.2.1...1.2.2
[1.2.1]: https://github.com/wp-pay-gateways/sisow/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/wp-pay-gateways/sisow/compare/1.1.9...1.2.0
[1.1.9]: https://github.com/wp-pay-gateways/sisow/compare/1.1.8...1.1.9
[1.1.8]: https://github.com/wp-pay-gateways/sisow/compare/1.1.7...1.1.8
[1.1.7]: https://github.com/wp-pay-gateways/sisow/compare/1.1.6...1.1.7
[1.1.6]: https://github.com/wp-pay-gateways/sisow/compare/1.1.5...1.1.6
[1.1.5]: https://github.com/wp-pay-gateways/sisow/compare/1.1.4...1.1.5
[1.1.4]: https://github.com/wp-pay-gateways/sisow/compare/1.1.3...1.1.4
[1.1.3]: https://github.com/wp-pay-gateways/sisow/compare/1.1.2...1.1.3
[1.1.2]: https://github.com/wp-pay-gateways/sisow/compare/1.1.1...1.1.2
[1.1.1]: https://github.com/wp-pay-gateways/sisow/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/wp-pay-gateways/sisow/compare/1.0.3...1.1.0
[1.0.3]: https://github.com/wp-pay-gateways/sisow/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/wp-pay-gateways/sisow/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/wp-pay-gateways/sisow/compare/1.0.0...1.0.1
