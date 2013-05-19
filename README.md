Eien - PHP, have a temporary file
====

Eien is a small PHP helper library for handling temporary files. Get, create, make, remove and flush temporary files and directories. Currently unstable, work in progress.

Basic usage
----

### Get a path to a available temporary file

```php
echo (string) new Rah_Eien_Temporary_File();
```

### Create a temporary file and move it to its final location once done

```php
$tmp = new Rah_Eien_File();
$tmp
    ->file('/path/to/final/destination')
    ->tmp('/tmp');

$file = new Rah_Eien_Temporary_File($tmp);
$file->move();
```

### Make a temporary file from an existing file

```php
$tmp = new Rah_Eien_File();
$tmp->file('/path/to/source/file.txt');
echo (string) new Rah_Eien_Temporary_File($tmp);
```

### Create a new temporary directory and return its path

```php
echo (string) new Rah_Eien_Temporary_Directory;
```