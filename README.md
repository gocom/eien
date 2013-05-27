Eien - PHP, have a temporary file
====

[Packagist](https://packagist.org/packages/rah/eien) | [Twitter](http://twitter.com/gocom) | [Issues](https://github.com/gocom/eien/issues) | [![Build Status](https://travis-ci.org/gocom/eien.png?branch=master)](https://travis-ci.org/gocom/eien)

Eien is a small PHP helper library for handling temporary files. Get, create, make, remove and flush temporary files and directories. Currently unstable, work in progress.

Basic usage
----

### Get a path to an available temporary file

```php
echo (string) new Rah_Eien_Temporary_File();
```

### Create a temporary file and move it to its final location once done

The file is moved to location specified with the optional Rah_Eien_File::$final option, if defined. The moving is performed once there are no other references to the instance, script is closed or when the move() method is called.

```php
$tmp = new Rah_Eien_File();
$tmp->final('/path/to/final/location.txt');
$file = new Rah_Eien_Temporary_File($tmp);
```

If you want moving to happen automatically, the easiest ways are extending, which allows you to perform your actions within it, unsetting the instance once you are done with it or wrapping the instance to its own contexts, like an anonymous function.

### Make a temporary file from an existing file

In addition to creating brand new temporary files, or getting paths as strings, you can also create temporary file instances from other files. The specified files are copied to your temporary directory, and you get an instance point to the new temporary instance.

```php
$tmp = new Rah_Eien_File();
$tmp->file('/path/to/source/file.txt');
echo (string) new Rah_Eien_Temporary_File($tmp);
```

### Create a new temporary directory and return its path

```php
echo (string) new Rah_Eien_Temporary_Directory();
```