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



# Effectra FS - Directory Class 

Utility class for working with directories.

## Methods

- `isDirectory(string $directory): bool`: Checks if a given path is a directory.
- `make(string $path, int $mode = 0755, bool $recursive = false, bool $force = false): bool`: Creates a new directory.
- `delete(string $directory, bool $preserve = false): bool`: Deletes a directory.
- `deleteDirectories(string $directory): bool`: Deletes all directories within a directory.
- `instance(string $directory)`: Gets a directory instance.
- `copy(string $source, $destination)`: Copies a directory recursively to a destination.
- `name(string $directory)`: Retrieves the name of a directory from its path.
- `rename(string $from, string $to, $context = null): bool`: Renames a directory.
- `fullName($directory)`: Retrieves the full path of a directory's parent.
- `read($directory)`: Retrieves the files and directories within a directory.
- `parent($directory, $levels = 1)`: Retrieves the parent directory of a given directory path.
- `isPrivate($directory)`: Checks if a directory is considered private.
- `files($directory, $full_path = false, $only_extension = false)`: Retrieves the files within a directory.
- `hasFiles($directory)`: Checks if a directory has any files.
- `deleteFiles($directory, $only_extension = false)`: Deletes files within a directory.
- `directories($directory, $full_path = false)`: Retrieves the directories within a directory.
- `empty($directory)`: Empties a directory by deleting its files and subdirectories.

## Example Usage

```php
use Effectra\Fs\Directory;

// Check if a path is a directory
$isDirectory = Directory::isDirectory('/path/to/directory');

// Create a new directory
$created = Directory::make('/path/to/new_directory');

// Delete a directory
$deleted = Directory::delete('/path/to/directory');

// Delete all directories within a directory
$deletedAll = Directory::deleteDirectories('/path/to/parent_directory');

// Get a directory instance
$dirInstance = Directory::instance('/path/to/directory');

// Copy a directory recursively to a destination
$copied = Directory::copy('/path/to/source_directory', '/path/to/destination_directory');

// Retrieve the name of a directory
$directoryName = Directory::name('/path/to/directory');

// Rename a directory
$renamed = Directory::rename('/path/to/old_directory', '/path/to/new_directory');

// Retrieve the full path of a directory's parent
$parentDirectory = Directory::fullName('/path/to/directory');

// Retrieve the files and directories within a directory
$entries = Directory::read('/path/to/directory');

// Retrieve the parent directory of a given directory path
$parentDirectory = Directory::parent('/path/to/directory');

// Check if a directory is considered private
$isPrivate = Directory::isPrivate('/path/to/directory');

// Retrieve the files within a directory
$files = Directory::files('/path/to/directory', true, ['txt', 'csv']);

// Check if a directory has any files
$hasFiles = Directory::hasFiles('/path/to/directory');

// Delete files within a directory
$deletedFiles = Directory::deleteFiles('/path/to/directory', ['txt', 'csv']);

// Retrieve the directories within a directory
$directories = Directory::directories('/path/to/directory', true);

// Empty a directory by deleting its files and subdirectories
Directory::empty('/path/to/directory');
````

Note: Please make sure to replace /path/to with the actual path in your code.


# Effectra FS - Path Class 


Effectra\Fs\Path is a utility class for manipulating file paths in PHP.

## Usage

The `Path` class provides several useful methods for manipulating file paths:

### ds(): string

Retrieves the directory separator for the current platform.

Example:
```php
$separator = \Effectra\Fs\Path::ds();
echo $separator; // Outputs '\' on Windows, '/' on Unix-like systems
```

### format(string $path): string

Formats a given path by replacing forward slashes, backslashes, and multiple separators with the directory separator.

Example:
```php
$path = \Effectra\Fs\Path::format('path/to//file');
echo $path; // Outputs 'path/to/file' on all platforms
```

### removeExtension(string $path): string

Removes the extension from a given path.

Example:
```php
$path = \Effectra\Fs\Path::removeExtension('file.txt');
echo $path; // Outputs 'file'
```

### setExtension(string $path, string $ext): string

Sets the extension of a given path.

Example:
```php
$path = \Effectra\Fs\Path::setExtension('file', 'txt');
echo $path; // Outputs 'file.txt'
```

# Effectra FS - FileData Class 

Effectra\Fs\FileData is a utility class for converting files to data URLs and vice versa in PHP.

##Usage

The `FileData` class provides two methods for converting files to data URLs and vice versa:

### fileToDataUrl(string $file): string|false

Converts a file to a data URL.

- Parameters:
  - `$file` (string): The path to the file.

- Returns:
  - A string representing the data URL for the file, or `false` if the conversion fails.

Example:
```php
$fileData = new \Effectra\Fs\FileData();
$dataUrl = $fileData->fileToDataUrl('/path/to/file.jpg');
if ($dataUrl !== false) {
    echo $dataUrl;
} else {
    echo "Failed to convert the file to a data URL.";
}
```

### dataUrlToFile(string $dataUrl, string $file): bool

Converts a data URL to a file.

- Parameters:
  - `$dataUrl` (string): The data URL to convert.
  - `$file` (string): The path to save the file.

- Returns:
  - `true` if the file is successfully saved, `false` otherwise.

Example:
```php
$fileData = new \Effectra\Fs\FileData();
$result = $fileData->dataUrlToFile('data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAAAAAAAD...', '/path/to/output.jpg');
if ($result) {
    echo "File saved successfully.";
} else {
    echo "Failed to save the file.";
}
```

# FileEncryption Class (Version >= 1.1)

The `FileEncryption` class provides utility methods for encrypting and decrypting files.

### Usage

```php
use Effectra\Fs\FileEncryption;

// Create an instance of FileEncryption with the encryption key
$fileEncryption = new FileEncryption('your-encryption-key');

// Encrypt a file
$fileEncryption->encryptFile('path/to/source/file.txt', 'path/to/destination/encrypted-file.txt');

// Decrypt a file
$fileEncryption->decryptFile('path/to/source/encrypted-file.txt', 'path/to/destination/decrypted-file.txt');

```

Make sure to replace `'your-encryption-key'` with your own encryption key.

### Example

```php
use Effectra\Fs\FileEncryption;

$fileEncryption = new FileEncryption('my-secret-key');

// Encrypt a file
$fileEncryption->encryptFile('data.txt', 'encrypted-data.txt');

// Decrypt a file
$fileEncryption->decryptFile('encrypted-data.txt', 'decrypted-data.txt');
```

In the above example, the `FileEncryption` class is used to encrypt the content of the `data.txt` file and store the encrypted data in the `encrypted-data.txt` file. It also provides a method to decrypt the encrypted data and save it in a separate file.

### Encryption Algorithm

The `FileEncryption` class uses the AES-256-CBC encryption algorithm with a randomly generated initialization vector (IV) for each encryption operation.

**Note:** Make sure to keep the encryption key secure and do not share it with unauthorized individuals.


# Contributing

Contributions are welcome! If you find any issues or have suggestions for improvements, please open an issue or submit a pull request on the GitHub repository.

# License

This package is open-source software licensed under the MIT license.