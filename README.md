<h1 align=center>SensioLabs Minify Bundle</h1>
<picture><source media="(prefers-color-scheme: light)" srcset="./minify.svg" />
<img src="./minify.dark.svg" alt="SensioLabs Minify Bundle for Symfony" width="100%" /></picture>
<div align="center"><pre>composer require sensiolabs/minify-bundle</pre></div>
<div align="center">

[![PHP Version](https://img.shields.io/badge/%C2%A0php-%3E%3D%208.2-777BB4.svg?logo=php&logoColor=white)](https://github.com/sensiolabs/minify-bundle/blob/main/composer.json)
[![CI](https://github.com/sensiolabs/minify-bundle/actions/workflows/CI.yaml/badge.svg?color=68c22e)](https://github.com/sensiolabs/minify-bundle/actions)
[![Release](https://img.shields.io/github/v/release/sensiolabs/minify-bundle?color=31a6ce)](https://github.com/sensiolabs/minify-bundle/releases)
[![Packagist Downloads](https://img.shields.io/packagist/dt/sensiolabs/minify-bundle?color=ce8531)](https://github.com/sensiolabs/minify-bundle/)
[![License](https://img.shields.io/github/license/sensiolabs/minify-bundle?color=d3416f)](https://github.com/sensiolabs/minify-bundle/blob/main/LICENSE)

</div>

<!-- ☘️ If you find this bundle helpful, feel free to show your appreciation by starring the repository on GitHub or 
sending a message to the author. Thank you! ☘️ -->

## Minify integration

SensioLabs Minify Bundle integrates [Minify](https://github.com/tdewolff/minify) into Symfony Asset Mapper.

### Asset Minifier

✅ Minify `CSS` and `JS` files, remove whitespace, comments, and more..

🌍 Reduces the size of your assets by up to `70%` (see metrics below).  

🚀 Improves the loading time of your website, and the `user experience`.     

### Asset Mapper

🎯 Automatically `minify` assets during the build process.   

📦 Stores minified assets in the Symfony `cache`. 

🌿 Download the Minify binary `automatically` from Github.
 
## Minification

### JavaScript

| File                 |  Original |  Minified |  Ratio |   Gain |          Compression |  Time |
|----------------------|----------:|----------:|-------:|-------:|---------------------:|------:|
| [autocomplete.js][1] |  19.88 KB |   9.17 KB | 46.13% | 53.87% | 🟩🟩🟩🟩🟩⬜️⬜️⬜️⬜️⬜️ |  8 ms |
| [bootstrap.js][3]    | 145.40 KB |  62.20 KB | 42.76% | 57.24% | 🟩🟩🟩🟩🟩🟩⬜️⬜️⬜️⬜️ | 10 ms |
| [video.js][5]        |   2.35 MB | 690.10 KB | 29.33% | 70.67% | 🟩🟩🟩🟩🟩🟩🟩⬜️⬜️⬜️ | 42 ms |
| [w3c.org js][7]      |  43.39 KB |  19.23 KB | 44.34% | 55.66% | 🟩🟩🟩🟩🟩🟩⬜️⬜️⬜️⬜️ |  6 ms |

Even gzip compression is more efficient on minified assets (see metrics below).

<details>

<summary> See transfert comparison (gzip) </summary>

| File            |   Original |  Minified |  Ratio |   Gain |          Compression |
|-----------------|-----------:|----------:|-------:|-------:|---------------------:|
| autoComplete.js |    5.59 KB |   2.68 KB | 47.96% | 52.04% | 🟩🟩🟩🟩🟩⬜️⬜️⬜️⬜️⬜️ |
| bootstrap.js    |   29.92 KB |  12.58 KB | 42.06% | 57.94% | 🟩🟩🟩🟩🟩🟩⬜️⬜️⬜️⬜️ |
| video.js        |  538.83 KB | 202.62 KB | 37.61% | 62.39% | 🟩🟩🟩🟩🟩🟩⬜️⬜️⬜️⬜️ |
| w3c.org.js      |   10.44 KB |   5.89 KB | 56.45% | 43.55% | 🟩🟩🟩🟩⬜️⬜️⬜️⬜️⬜️⬜️ |

</details>

### CSS

| File                  |  Original |  Minified |  Ratio |   Gain |          Compression | Time |
|-----------------------|----------:|----------:|-------:|-------:|---------------------:|-----:|
| [autocomplete.css][2] |   3.09 KB |   2.51 KB | 81.33% | 18.67% | 🟩🟩⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️ | 2 ms |
| [bootstrap.css][4]    | 281.05 KB | 231.89 KB | 82.51% | 17.49% | 🟩🟩⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️ | 9 ms |
| [video-js.css][6]     |  53.37 KB |  47.06 KB | 88.24% | 11.76% | 🟩🟩⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️ | 4 ms |
| [w3c.org css][8]      | 111.44 KB |  70.37 KB | 63.17% | 36.83% | 🟩🟩🟩🟩⬜️⬜️⬜️⬜️⬜️⬜️ | 5 ms |

<details>

<summary> See transfert comparison (gzip) </summary>

| File             |   Original |  Minified |  Ratio |   Gain |          Compression |
|------------------|-----------:|----------:|-------:|-------:|---------------------:|
| autoComplete.css |    1.08 KB |   0.89 KB | 82.41% | 17.59% | 🟩🟩⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️ |
| bootstrap.css    |   33.56 KB |  28.94 KB | 86.08% | 13.92% | 🟩⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️ |
| video-js.css     |   13.14 KB |  12.72 KB | 96.79% |  3.21% | 🟩⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️ |
| w3c.org.css      |   21.98 KB |  13.65 KB | 62.13% | 37.87% | 🟩🟩🟩🟩⬜️⬜️⬜️⬜️⬜️⬜️ |

</details>

## Installation

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### With Symfony Flex

Open a command console, enter your project directory and execute:

```shell
composer require sensiolabs/minify-bundle
```

### Without Symfony Flex

<details>

<summary> How to install without Symfony Flex</summary>

#### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```shell
composer require sensiolabs/minify-bundle
```

#### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Sensiolabs\MinifyBundle\SensiolabsMinifyBundle::class => ['all' => true],
];
```

Depending on your deployment process, you might want to enable the 
bundle only in the desired environment(s).

</details>

## Usage

If you use [AssetMapper][9], run the following command to minify all the assets:

```shell
php bin/console asset-map:compile
```

This command is usually run when [serving assets in production][10] and the
SensioLabs Minify Bundle will hook into it to minify all assets while copying them.

### Command Line

You can also minify assets manually with the command line. First, make sure that
the binary file used to minify assets is properly installed in your computer:

```shell
php bin/console minify:install
```

Then, run the following command to minify assets:

```shell
# this outputs the result in the console
php bin/console minify:asset css/main.css

# this will write the output into the 'main.min.css' file
# (the given output file is created / overwritten if needed)
php bin/console minify:asset css/main.css css/main.min.css
```

## Configuration

### AssetMapper

```yaml
# config/packages/sensiolabs_minify.yaml
sensiolabs_minify:
    asset_mapper:
        # you can minify only CSS files, only JS files or both
        types:
            css: true # (default: true)
            js:  true # (default: true)

        # a list of assets to exclude from minfication (default: [])
        # the values of the list can be any shell wildcard patterns
        ignore_paths:
            - 'admin/*'
            - '*.min.js'

        # whethere to exclude the assets stored in vendor/ from minification;
        # these assets are usually already minified, so it's common to ignore them
        ignore_vendor: true # (default: true)
```

### Minify Binary

#### Local binary

The minification is performed by a binary file that can be installed on your
computer/server or downloaded automatically by the bundle. This is the default
configuration used by the bundle:

```yaml
# config/packages/sensiolabs_minify.yaml
sensiolabs_minify:
    # ...

    minify:
        # this disables the usage of local binaries
        local_binary: false

        # if TRUE, the bundle will download the binary from GitHub
        download_binary: '%kernel.debug%'

        # the local path where the downloaded binary is stored
        download_directory: '%kernel.project_dir%/var/minify'
```

You can customize this configuration to use a local binary:

```yaml
# config/packages/sensiolabs_minify.yaml
sensiolabs_minify:
    # ...

    minify:
        # set it to 'auto' to let the bundle try to find the location of the binary
        local_binary: 'auto'

        # you can also define the path to the binary explicitly, but this won't work
        # if you run the application in multiple servers with different binary locations
        local_binary: "/usr/local/bin/minify"
```

## Credits

### Authors

- MinifyBundle: [Simon André](https://github.com/smnandre) & [SensioLabs](https://github.com/sensiolabs)
- Minify binary: [Timo Dewolf](https://github.com/tdewolff)

### Acknowledgments

This bundle is inspired by the following projects:

- [SassBundle](https://github.com/SymfonyCasts/sass-bundle) from @SymfonyCasts
- [BiomejsBundle](https://github.com/Kocal/BiomeJsBundle) from @Kocal
- [TypeScriptBundle](https://github.com/sensiolabs/AssetMapperTypeScriptBundle) from @SensioLabs

### Contributors

Special thanks to the Symfony community for their contributions and feedback.

## License

The [SensioLabs Minify Bundle](https://github.com/sensiolabs/minify-bundle) is released under the [MIT license](LICENSE).

[1]: https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.js
[3]: https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.js
[5]: https://cdn.jsdelivr.net/npm/video.js@8.18.1/dist/video.js
[7]: https://github.com/w3c/w3c-website-templates-bundle/blob/main/public/dist/assets/js/main.js
[2]: https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.css
[4]: https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.css
[6]: https://cdn.jsdelivr.net/npm/video.js@8.18.1/dist/video-js.css
[8]: https://github.com/w3c/w3c-website-templates-bundle/blob/main/public/dist/assets/styles/core.css
[9]: https://symfony.com/doc/current/frontend/asset_mapper.html
[10]: https://symfony.com/doc/current/frontend/asset_mapper.html#serving-assets-in-dev-vs-prod
