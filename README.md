# Effectra FS - File Class

Effectra FS is a PHP library that offers a comprehensive set of classes for managing various aspects of the file system, including files, folders, JSON, CSV, and XML. The `File` class provided by Effectra FS provides static methods for handling file-related operations.

## Installation

```shell
composer require effectra/fs
```
This command will download and install the Effectra FS library along with its dependencies into your project.

After the installation is complete, you can start using the library by including the necessary files and utilizing the `File` class as demonstrated in the previous readme file.

## Class Reference

### `File` Class

The `File` class provides static methods for managing files and performing file system operations.

#### Methods

- `append($path, $data)`: Appends data to a file.
- `exists($path)`: Checks if a file exists.
- `delete($paths)`: Deletes one or multiple files.
- `put($path, $contents, $lock = false)`: Writes contents to a file.
- `replaceInFile($search, $replace, $path)`: Replaces occurrences of a string in a file.
- `clear($path)`: Clears the contents of a file.
- `getContent($path, ?int $length = null)`: Retrieves the contents of a file.
- `search($search, $path)`: Searches for a string in a file.
- `lastModified($path)`: Gets the last modified time of a file.
- `isExecutable($path)`: Checks if a file is executable.
- `isUploadedFile($path)`: Checks if a file is an uploaded file.
- `isReadable($path)`: Checks if a file is readable.
- `isWritable($path)`: Checks if a file is writable.
- `isFile($file)`: Checks if a path is a regular file.
- `glob($pattern, $flags = 0)`: Finds pathnames matching a pattern.
- `extension($path)`: Retrieves the file extension of a path.
- `type($path)`: Gets the file type of a path.
- `mimeType($path)`: Gets the MIME type of a file.
- `basename($path)`: Retrieves the basename of a path.
- `name($path)`: Retrieves the filename without extension of a path.
- `move($path, $target)`: Moves a file to a new location.
- `copy($path, $target)`: Copies a file to a new location.
- `size($path)`: Retrieves the file size.
- `require($path)`: Requires a PHP file.
- `requireOnce($path)`: Requires a PHP file once.
- `lines($path)`: Reads a file and returns an array of lines.
- `hash($path, $algorithm = 'md5')`: Calculates the hash value of a file.
- `replace($path, $content, $mode = null)`: Replaces the contents of a file.
- `chmod($path, $mode = null)`: Changes the mode of a file.
- `perms($path)`: Retrieves the permissions of a file.
- `fileATime($path)`: Gets the last accessed time of a file.
- `fileCTime($path)`: Gets the creation time of a file.
- `fileGroup($path)`: Gets the group ID of a file.
- `fileOwner($path)`: Gets the user ID of the owner of a file.

## Usage

Once you have installed the Effectra FS library using Composer, you can start using the `File` class in your PHP code. Here's an example of how you can utilize some of the methods provided by the `File` class:

```php
<?php

require_once 'vendor/autoload.php';

use Effectra\Fs\File;

// Example usage of the `exists()` method
$fileExists = File::exists('path/to/file.txt');
if ($fileExists) {
    echo "The file exists.";
} else {
    echo "The file does not exist.";
}

// Example usage of the `put()` method
$fileContent = 'Hello, world!';
$writeSuccess = File::put('path/to/newfile.txt', $fileContent);
if ($writeSuccess !== false) {
    echo "The file was successfully written.";
} else {
    echo "Failed to write the file.";
}

// Example usage of the `getContent()` method
$fileContent = File::getContent('path/to/file.txt');
echo "The file content is: " . $fileContent;

// Example usage of the `delete()` method
$deleteSuccess = File::delete('path/to/file.txt');
if ($deleteSuccess) {
    echo "The file was successfully deleted.";
} else {
    echo "Failed to delete the file.";
}

// ... Continue using other methods provided by the `File` class

?>
```

In this example, we demonstrate the usage of methods such as `exists()`, `put()`, `getContent()`, and `delete()` from the `File` class. You can explore the various methods available in the class and use them according to your file system requirements.

Make sure to adjust the file paths according to your specific use case.

Remember to include the Composer-generated autoloader (`require_once 'vendor/autoload.php';`) at the beginning of your PHP script to ensure that the Effectra FS library is properly loaded.

Feel free to incorporate these examples into your code and modify them as needed for your project. If you have any further questions or need additional assistance, please let me know!
