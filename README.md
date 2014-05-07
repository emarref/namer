# Namer

This is a simple library for determining whether or not a given name is available for use, and generating a new name based on a strategy if it is not.

For example, when saving a file to a filesystem, use the Namer to determine an available filename to use that will not clash with other files on the filesystem.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/emarref/namer/badges/quality-score.png?s=7f4132867598fcfe59d6f7671347fdcd44d625a3)](https://scrutinizer-ci.com/g/emarref/namer/) [![Code Coverage](https://scrutinizer-ci.com/g/emarref/namer/badges/coverage.png?s=2ac1119564679c48662b1c18af78e078c354b8ab)](https://scrutinizer-ci.com/g/emarref/namer/)

## Install

### Composer

Add the following configuration to your composer.json file.

```json
[...]
    "require": {
       "emarref/phpunit": "dev-master"
   }
[...]
```

then run `php ./composer.phar update emarref/namer`.

## Usage

```php
$strategy = new Emarref\Namer\Strategy\SuffixStrategy();
$detector = new Emarref\Namer\Detector\ArrayDetector(['Taken', 'Taken copy']);
$namer = new Emarref\Namer($strategy, $detector);
echo $namer->getName('Taken'); // Will return "Taken copy 2"
```

### Strategies

The strategy is the class that generates a new name, based on an iteration index. It must implement the interface `Emarref\Namer\Strategy\StrategyInterface`. There are currently two strategies available for use. `HashStrategy` and `SuffixStrategy`.

#### HashStrategy

Returns a hashed representation of the name concatenated with the index. `sha1` and `md5` are supported.

#### SuffixStrategy

A simple strategy that returns the name untouched on iteration 0, returns the name with suffix appended on iteration
1, and returns the name with suffix and iteration appended thereafter.

e.g. Name, Name copy, Name copy 2, Name copy 3 etc

### Detectors

The detector is the class that determines whether or not a name is available to use. A detector must implement the interface `Emarref\Namer\Detector\DetectorInterface`. There are currently two simple detectors available for use. `ArrayDetector` and `FilesystemDetector`.

#### ArrayDetector

Detects the existence of a value in an array.

#### FilesystemDetector

Detects the existence of a file on a filesystem.
