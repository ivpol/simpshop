<?php

namespace Core;

/**
 *
 */
class Autoloader
{
    private $rootDir;
    private $defaultExtension;

    public function __construct($rootDir, $extension = 'php')
    {
        $rootDir = $this->getFormatedPath($rootDir);
        if ($rootDir) {
            $this->rootDir = $rootDir;
        }

        if (!$extension) {
            $this->defaultExtension = 'php';
        } else {
            $this->defaultExtension = $extension;
        }

        spl_autoload_register([$this, 'loadClass']);
    }

    public function loadFromDir($dir)
    {
        if ($this->rootDir){
            $dir = $this->getFormatedPath($dir);
            if ($dir) {
                $files = glob($this->rootDir .  $dir . '*.' . $this->defaultExtension);
                foreach ($files as $fileName) {
                    $this->includeFile($fileName);
                }
            }
        }
    }

    public function loadClass($className)
    {

        if ($this->rootDir) {
            $className = ltrim($className, '\\');
            $fileName  = '';
            $namespace = '';
            if ($lastNsPos = strrpos($className, '\\')) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            }
            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.' . $this->defaultExtension;
            return $this->includeFile($this->rootDir . $fileName);
        } else {
            return false;
        }
    }

    private function includeFile($fileName)
    {
        if ($fileName && file_exists($fileName) && is_file($fileName)) {
            include_once $fileName;
            return true;
        } else {
            return false;
        }
    }

    private function getFormatedPath($path)
    {
        if ($path) {
            ltrim($path, '/');
            if (strlen($path) > 1 && $path[strlen($path)-1]  != '/') {
                $path = $path . '/';
            }
        }
        return $path;
    }
}
