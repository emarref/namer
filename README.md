# Namer

This is a simple library for determining whether or not a given name is available for use, and generating a new name based on a strategy if it is not.

For example, when saving a file to a filesystem, use the Namer to determine an available filename to use that will not clash with other files on the filesystem.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/emarref/namer/badges/quality-score.png?s=7f4132867598fcfe59d6f7671347fdcd44d625a3)](https://scrutinizer-ci.com/g/emarref/namer/)
[![Code Coverage](https://scrutinizer-ci.com/g/emarref/namer/badges/coverage.png?s=2ac1119564679c48662b1c18af78e078c354b8ab)](https://scrutinizer-ci.com/g/emarref/namer/)
[![Build Status](https://travis-ci.org/emarref/namer.svg)](https://travis-ci.org/emarref/namer)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/2ba81d47-51c2-4930-aeba-8a9eab6ff685/mini.png)](https://insight.sensiolabs.com/projects/2ba81d47-51c2-4930-aeba-8a9eab6ff685)

## Install

### Composer

Add the following configuration to your composer.json file.

```json
[...]
    "require": {
       "emarref/namer": "dev-master"
   }
[...]
```

then run `php ./composer.phar update emarref/namer`.

## Usage

```php
$strategy = new Emarref\Namer\Strategy\SuffixStrategy();
$detector = new Emarref\Namer\Detector\ArrayDetector(['Taken', 'Taken copy']);
$namer    = new Emarref\Namer\Namer($strategy, $detector);
echo $namer->getName('Taken'); // Will return "Taken copy 2"
```

The detector is optional, but must be passed as the second argument to the `Namer#getName()` method if it is omitted when instantiating the namer. This is useful when your namer is configured in your DI container, but you need to use a different detector. For example:

```php
$namer            = $this->get('default_namer');
$unavailableNames = $repository->getUnavailableNames();
$detector         = new Emarref\Namer\Detector\ArrayDetector($unavailableNames);
echo $namer->getName('Taken', $detector);
```

### Using the Symfony2 Dependency Injection Container

```yaml
parameters:
    namer.default.limit:                    100

    namer.strategy.suffix.suffix:           copy
    namer.strategy.suffix.incremental:      true
    namer.strategy.suffix.ignore_extension: true

    namer.detector.filesystem.path:         /var/www/website/web/uploads

services:
    namer.strategy.suffix:
        class:  Emarref\Namer\Strategy\SuffixStrategy
        public: false
        arguments:
            - %namer.strategy.suffix.suffix%
            - %namer.strategy.suffix.incremental%
            - %namer.strategy.suffix.ignore_extension%

    namer.detector.filesystem:
        class:  Emarref\Namer\Detector\FilesystemDetector
        public: false
        arguments:
            - %namer.detector.filesystem.path%

    namer.default:
        class:  Emarref\Namer\Namer
        public: false
        arguments:
            - @namer.strategy.suffix
            - @namer.detector.filesystem
            - %namer.default.limit%

    namer:
        alias: namer.default
```

You can then use the namer by accessing the `namer` service in the container.

### Strategies

The strategy is the class that generates a new name, based on an iteration index. It must implement the interface `Emarref\Namer\Strategy\StrategyInterface`. There are currently two strategies available for use. `HashStrategy` and `SuffixStrategy`.

#### HashStrategy

Returns a hashed representation of the name concatenated with the index. `sha1` and `md5` are supported.

#### SuffixStrategy

A simple strategy that returns the name untouched on iteration 0, returns the name with suffix appended on iteration
1, and returns the name with suffix and iteration appended thereafter.

e.g. Name, Name copy, Name copy 2, Name copy 3 etc

The Suffix can also be inserted before the file extension using `SuffixStrategy::setIgnoreExtension(false)`.

### Detectors

The detector is the class that determines whether or not a name is available to use. A detector must implement the interface `Emarref\Namer\Detector\DetectorInterface`. There are currently two simple detectors available for use. `ArrayDetector` and `FilesystemDetector`.

#### ArrayDetector

Detects the existence of a value in an array.

#### FilesystemDetector

Detects the existence of a file on a filesystem.

#### GaufretteDetector

Detects the existence of a file using a [Gaufrette](https://github.com/KnpLabs/Gaufrette) Adapter, e.g. `Gaufrette\Adapter\Local`
