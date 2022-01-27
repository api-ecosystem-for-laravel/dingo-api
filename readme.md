Note: This is an official and reasonably maintained fork of the popular https://github.com/dingo/api repository by one of the maintainers of that project. The reason it was forked is due to broken integrations with CI tools such as travis (which can only be fixed by owner), and in general to be able to better support the project and ensure non-breaking updates.

In order to move to this repo, you merely need to update your composer file. All the namespaces and other aspects of the project are the same. Example instructions are below, to use the latest version:

```bash
composer remove dingo/api
composer require api-ecosystem-for-laravel/dingo-api
```

Please note, we do not actively maintain the Lumen support of this project. If you are still using Lumen, we recommend you migrate to Laravel.

---

![](https://cloud.githubusercontent.com/assets/829059/9216039/82be51cc-40f6-11e5-88f5-f0cbd07bcc39.png)

The Dingo API package is meant to provide you, the developer, with a set of tools to help you easily and quickly build your own API. While the goal of this package is to remain as flexible as possible it still won't cover all situations and solve all problems.

[![Build Status](https://img.shields.io/travis/api-ecosystem-for-laravel/dingo-api/master.svg?style=flat-square)](https://travis-ci.org/api-ecosystem-for-laravel/dingo-api)
[![License](https://img.shields.io/packagist/l/api-ecosystem-for-laravel/dingo-api.svg?style=flat-square)](LICENSE)
[![Development Version](https://img.shields.io/packagist/vpre/api-ecosystem-for-laravel/dingo-api.svg?style=flat-square)](https://packagist.org/packages/api-ecosystem-for-laravel/dingo-api)
[![Monthly Installs](https://img.shields.io/packagist/dm/api-ecosystem-for-laravel/dingo-api.svg?style=flat-square)](https://packagist.org/packages/api-ecosystem-for-laravel/dingo-api)
[![StyleCI](https://styleci.io/repos/18673522/shield)](https://styleci.io/repos/18673522)

## Features

This package provides tools for the following, and more:

- Content Negotiation
- Multiple Authentication Adapters
- API Versioning
- Rate Limiting
- Response Transformers and Formatters
- Error and Exception Handling
- Internal Requests
- API Blueprint Documentation

## Documentation

Please refer to our extensive [Wiki documentation](https://github.com/dingo/api/wiki) for more information.

## API Boilerplate

If you are looking to start a new project from scratch, consider using the [Laravel API Boilerplate](https://github.com/specialtactics/laravel-api-boilerplate), which builds on top of the dingo-api package, and adds a lot of great features.

## Support

For answers you may not find in the Wiki, avoid posting issues. Feel free to ask for support on the dedicated [Slack](https://larachat.slack.com/messages/api/) room. Make sure to mention **specialtactics** so he is notified.

## License

This package is licensed under the [BSD 3-Clause license](http://opensource.org/licenses/BSD-3-Clause).
