# CHANGELOG

## 1.3.0

- Add HTML support (`MinifierInterface::TYPE_HTML`) and `HtmlOptions::keepDocumentTags` to preserve the `html`, `head` and `body` tags (#41).
- Add options support to `MinifierInterface::minify()` via a third, optional positional `OptionsInterface` argument, while preserving compatibility with existing two-argument implementations (#41).
- Allow pinning the Minify binary version with `sensiolabs_minify.minify.version` (#40).
- Remove the `minify:install` command when `download_binary` is disabled, fixing container compilation with this configuration (#39).
- Upgrade development tooling to PHPStan 2 and add PHP 8.5 to the CI test matrix (#44, #36).

## 1.2.0

- Add Symfony 8 support

## 1.1.0

- Support for PHP 8.1.0 or higher

## 1.0.0

- First stable release
- Fix download binary for Windows

## 0.9.3

- Support for Windows

## 0.9.0

- First version of the bundle
