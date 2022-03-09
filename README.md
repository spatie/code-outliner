
[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# Create a visual representation of your code

[![Latest Version](https://img.shields.io/github/release/spatie/code-outliner.svg?style=flat-square)](https://github.com/spatie/code-outliner/releases)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/code-outliner.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/code-outliner)
[![StyleCI](https://github.styleci.io/repos/141413296/shield?branch=master)](https://github.styleci.io/repos/141413296)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/code-outliner.svg?style=flat-square)](https://packagist.org/packages/spatie/code-outliner)

You're reading code on a daily basis, code should be optimised for that. 
This tool helps you visualise how the code in your project looks, 
and might be a trigger to better structure your files.

More information about the visual perception of code here: [https://www.stitcher.io/blog/visual-perception-of-code](https://www.stitcher.io/blog/visual-perception-of-code).

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/code-outliner.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/code-outliner)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

[spatie/browsershot](https://github.com/spatie/browsershot) is required to generate images. 
Please refer to the Browsershot documentation for all details, most important is to install puppeteer.

```
npm install puppeteer --global
```

You can install the package via composer:

```
composer global require spatie/code-outliner
```

## Usage

```bash
code-outliner <file> [--output=] [--extensions=]
```

### Outlining a single file

Pass a single file to the command, and you'll get output like this.

![Outline of a single file](https://spatie.github.io/code-outliner/outline-single.png)

### Overlaying multiple files

Pass a directory path to the command, and it'll overlay all files on top of each other.
The darker areas represent areas where there's more code across all files.

![Outline of multiple files](https://spatie.github.io/code-outliner/outline-multiple.png)

### Filtering extensions

By default, PHP files will be scanned. 
You're able to specify other and multiple extensions with the `--extensions` option.

```php
code-outliner --extensions="html,twig"
```

### Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Kruikstraat 22, 2018 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Brent Roose](https://github.com/brendt)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
