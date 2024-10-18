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

 🌍🌍 Reduces the size of your assets by up to `70%` (see metrics below).  

🚀🚀🚀 Improves the loading time of your website, and the `user experience`.     

### Asset Mapper

🎯 Automatically `minify` assets during the build process.   

📦📦 Stores minified assets in the Symfony `cache`. 

🌿🌿🌿 Download the Minify binary `automatically` from Github.
 
## Minification

### JavaScript

| Asset                  | Before |  After | Diff | Compression                              |  Time |
|------------------------|-------:|-------:|-----:|------------------------------------------|------:|
| [Autocomplete.js][1]   |  20 kB | 9.2 kB | -54% | ⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩 |  8 ms |
| [Bootstrap.js][3]      | 145 kB |  62 kB | -57% | ⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩 | 10 ms |
| [Video.js][5]          | 2.3 MB | 0.7 MB | -71% | ⬜️⬜️⬜️⬜️⬜️⬜️🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩 | 42 ms |
| [w3c.org js][7]        |  44 kB |  19 kB | -57% | ⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩 |  6 ms |

### CSS

| Asset                 | Before |  After | Diff | Compression                               | Time |
|-----------------------|-------:|-------:|-----:|-------------------------------------------|-----:|
| [Autocomplete.css][2] | 3.1 kB | 2.5 kB | -19% | ⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️🟩🟩🟩🟩🟩 | 2 ms |
| [Bootstrap.css][4]    | 281 kB | 232 kB | -18% | ⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️🟩🟩🟩🟩 | 9 ms |
| [Video-js.css][6]     |  53 kB |  47 kB | -12% | ⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜⬜️⬜️⬜️⬜️⬜️⬜️⬜️️🟩🟩 | 4 ms |
| [w3c.org css][8]      | 111 kB |  70 kB | -37% | ⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️🟩🟩🟩🟩🟩🟩🟩🟩 | 5 ms |

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


## Configuration

### AssetMapper Settings

#### Asset types

```yaml
# config/packages/sensiolabs_minify.yaml
sensiolabs_minify:
  asset_mapper:

    # Minify CSS and JS files  
    types: 
      css: true
      js:  true
```

#### Exclude files

```yaml
# config/packages/sensiolabs_minify.yaml
sensiolabs_minify:
  asset_mapper:

    # Exclude files
    ignore_paths: 
      - 'admin/*'
      - '*.min.js'

    # Exclude vendor assets
    ignore_vendor: true
```


### Minify Binary


#### Local binary

```yaml
# config/packages/sensiolabs_minify.yaml
sensiolabs_minify:
  minify:

    # Auto-detect the local binary 
    local_binary: 'auto'

    # Specify the local binary path
    # local_binary: "/usr/local/sbin/minify"

    # Or set false to disable
    # local_binary: false
```

#### Automatic download 

```yaml
# config/packages/sensiolabs_minify.yaml
sensiolabs_minify:
  minify:

    # Enable automatic download from GitHub
    download_binary: true

    # Directory to store the downloaded binary
    download_directory: '%kernel.project_dir%/var/minify'

```

## Console

### Command Line

#### Install Minify locally

```shell
php bin/console minify:install
```

#### Minify assets

```shell
php bin/console minify:assets css/main.css css/main.min.css
```

## Credits

- Minify binary: [Timo Dewolf](https://github.com/tdewolff) 
- Symfony Bundle: [Simon André](https://github.com/smnandre) & [SensioLabs](https://github.com/sensiolabs)

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
