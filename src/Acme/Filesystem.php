<?php

namespace Acme;

class FileDoesNotExist extends \Exception {}

class Filesystem {

    /**
     * Root directory
     *
     * @var string
     */
    protected $root;

    /**
     * @param null $root
     */
    public function __construct($root)
    {
        $this->setRoot($root);
    }

    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * Fetch a local file
     *
     * @param $file
     * @return string
     * @throws FileDoesNotExist
     */
    public function get($file)
    {
        $path = $this->getPath($file);

        if ( ! file_exists($path)) throw new FileDoesNotExist;

        return file_get_contents($path);
    }

    /**
     * Build the path to the file
     *
     * @param $file
     * @return string
     */
    protected function getPath($file)
    {
        return $this->root . '/' . $file;
    }

    /**
     * Write to a file
     *
     * @param $file
     * @param $body
     * @param null $flag
     * @return int
     */
    public function put($file, $body, $flag = null)
    {
        return file_put_contents($this->getPath($file), $body, $flag);
    }

    /**
     * Append to a file
     *
     * @param $file
     * @param $body
     * @return int
     */
    public function append($file, $body)
    {
        return $this->put($file, $body, FILE_APPEND);
    }

    /**
     * Delete a file
     *
     * @param $file
     */
    public function delete($file)
    {
        unlink($this->getPath($file));
    }

    /**
     * Does the given file exist?
     *
     * @param $file
     * @return bool
     */
    public function exists($file)
    {
        return file_exists($this->getPath($file));
    }

}
