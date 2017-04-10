# Change Log

All notable changes to this project will be documented in this file.

This projects adheres to [Semantic Versioning](http://semver.org/) and [Keep a CHANGELOG](http://keepachangelog.com/).

## [Unreleased][unreleased]
-

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
- Simplified the gateay payment start function.

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
- Changed WordPress pay core library requirment from ~1.0.0 to >=1.0.0.

## [1.0.2] - 2015-01-20
- Require WordPress pay core library version 1.0.0.

## [1.0.1] - 2014-12-12
- Improved error handling.

## 1.0.0 - 2014-12-12
- First release.

[unreleased]: https://github.com/wp-pay-gateways/sisow/compare/1.2.2...HEAD
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
