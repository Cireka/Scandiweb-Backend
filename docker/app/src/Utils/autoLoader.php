<?php
/**
 * Autoloader function
 *
 * @param string $className The fully-qualified class name to load
 * @return void
 */
function autoLoader($className)
{
    // Replace namespace separators with directory separators
    $className = str_replace('\\', '/', $className);

    // Get the project root directory
    $rootDir = __DIR__ . '/../..';

    // Construct the file path
    $filePath = $rootDir . '/' . $className . '.php';

    // Check if the file exists and require it
    if (file_exists($filePath)) {
        require $filePath;
    } else {
        // Throw an exception if the class file cannot be found
        throw new Exception("Unable to load class: $className");
    }
}

// Register the autoloader
spl_autoload_register('autoLoader');