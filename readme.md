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

[![CI Tests](https://github.com/api-ecosystem-for-laravel/dingo-api/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/api-ecosystem-for-laravel/dingo-api/actions)
[![License](https://img.shields.io/packagist/l/api-ecosystem-for-laravel/dingo-api.svg?style=flat-square)](LICENSE)
[![Development Version](https://img.shields.io/packagist/vpre/api-ecosystem-for-laravel/dingo-api.svg?style=flat-square)](https://packagist.org/packages/api-ecosystem-for-laravel/dingo-api)
[![Monthly Installs](https://img.shields.io/packagist/dm/api-ecosystem-for-laravel/dingo-api.svg?style=flat-square)](https://packagist.org/packages/api-ecosystem-for-laravel/dingo-api)

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

Please refer to our extensive [Wiki documentation](https://github.com/api-ecosystem-for-laravel/dingo-api/wiki) for more information.

[![DeepWiki](https://img.shields.io/badge/DeepWiki-api--ecosystem--for--laravel%2Fdingo--api-blue.svg?logo=data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAyCAYAAAAnWDnqAAAAAXNSR0IArs4c6QAAA05JREFUaEPtmUtyEzEQhtWTQyQLHNak2AB7ZnyXZMEjXMGeK/AIi+QuHrMnbChYY7MIh8g01fJoopFb0uhhEqqcbWTp06/uv1saEDv4O3n3dV60RfP947Mm9/SQc0ICFQgzfc4CYZoTPAswgSJCCUJUnAAoRHOAUOcATwbmVLWdGoH//PB8mnKqScAhsD0kYP3j/Yt5LPQe2KvcXmGvRHcDnpxfL2zOYJ1mFwrryWTz0advv1Ut4CJgf5uhDuDj5eUcAUoahrdY/56ebRWeraTjMt/00Sh3UDtjgHtQNHwcRGOC98BJEAEymycmYcWwOprTgcB6VZ5JK5TAJ+fXGLBm3FDAmn6oPPjR4rKCAoJCal2eAiQp2x0vxTPB3ALO2CRkwmDy5WohzBDwSEFKRwPbknEggCPB/imwrycgxX2NzoMCHhPkDwqYMr9tRcP5qNrMZHkVnOjRMWwLCcr8ohBVb1OMjxLwGCvjTikrsBOiA6fNyCrm8V1rP93iVPpwaE+gO0SsWmPiXB+jikdf6SizrT5qKasx5j8ABbHpFTx+vFXp9EnYQmLx02h1QTTrl6eDqxLnGjporxl3NL3agEvXdT0WmEost648sQOYAeJS9Q7bfUVoMGnjo4AZdUMQku50McDcMWcBPvr0SzbTAFDfvJqwLzgxwATnCgnp4wDl6Aa+Ax283gghmj+vj7feE2KBBRMW3FzOpLOADl0Isb5587h/U4gGvkt5v60Z1VLG8BhYjbzRwyQZemwAd6cCR5/XFWLYZRIMpX39AR0tjaGGiGzLVyhse5C9RKC6ai42ppWPKiBagOvaYk8lO7DajerabOZP46Lby5wKjw1HCRx7p9sVMOWGzb/vA1hwiWc6jm3MvQDTogQkiqIhJV0nBQBTU+3okKCFDy9WwferkHjtxib7t3xIUQtHxnIwtx4mpg26/HfwVNVDb4oI9RHmx5WGelRVlrtiw43zboCLaxv46AZeB3IlTkwouebTr1y2NjSpHz68WNFjHvupy3q8TFn3Hos2IAk4Ju5dCo8B3wP7VPr/FGaKiG+T+v+TQqIrOqMTL1VdWV1DdmcbO8KXBz6esmYWYKPwDL5b5FA1a0hwapHiom0r/cKaoqr+27/XcrS5UwSMbQAAAABJRU5ErkJggg==)](https://deepwiki.com/api-ecosystem-for-laravel/dingo-api)


## API Boilerplate

If you are looking to start a new project from scratch, consider using the [Laravel API Boilerplate](https://github.com/specialtactics/laravel-api-boilerplate), which builds on top of the dingo-api package, and adds a lot of great features for API development.

## Support

For answers you may not find in the Wiki, avoid posting issues. Feel free to ask for support on the dedicated [Slack](https://larachat.slack.com/messages/api/) room. Make sure to mention **specialtactics** so he is notified.

Alternatively, you can start a [new discussion in the Q&A category](https://github.com/api-ecosystem-for-laravel/dingo-api/discussions/categories/q-a).

## License

This package is licensed under the [BSD 3-Clause license](http://opensource.org/licenses/BSD-3-Clause).
