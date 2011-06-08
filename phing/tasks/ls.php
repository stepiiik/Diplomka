<?php

require_once "phing/Task.php";

class Ls extends Task {

    /**
     * The message passed in the buildfile.
     */
    private $dir = null;

    /**
     * The setter for the attribute "message"
     */
    public function setDir($dir) {
        $this->dir = $dir;
    }

    /**
     * The init method: Do init steps.
     */
    public function init() {
      // nothing to do here
    }

    /**
     * The main entry point method.
     */
    public function main() {
        $releases = array();
        
        if ($handle = opendir($this->dir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && is_dir($this->dir . '/' . $file)) {
                    $releases[] = $file;

                }
            }
            closedir($handle);
        }
        
        foreach ($releases as $i => $release) {
            echo '[' . $i . '] ' . $release . "\n";
        }
    }
}

?>