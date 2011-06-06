<?php

require_once "phing/Task.php";

class InitDir extends Task {

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
        if (!file_exists($this->dir)) {
            mkdir($this->dir);
        }
    }
}

?>