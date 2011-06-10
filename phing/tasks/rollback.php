<?php

require_once "phing/Task.php";

class Rollback extends Task {

    /**
     * The message passed in the buildfile.
     */
    private $dir = null;
    private $release = -1;
    private $current = null;
    private $deltas = null;

    /**
     * The setter for the attribute "message"
     */
    public function setDir($dir) {
        $this->dir = $dir;
    }
    
    public function setRelease($release) {
        $this->release = $release;
    }
    
    public function setCurrent($current) {
        $this->current = $current;
    }
    
    public function setDeltas($deltas) {
        $this->deltas = $deltas;
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
        $releases = $this->getReleases();
        $lastRelease = count($releases) - 1;
        
        if ($this->release < 0) {
            $this->release = $lastRelease - 1;
        }
        if (!is_numeric($this->release) || $release > $lastRelease) {
            $this->log('Číslo verze je naplatné');
            
            return;
        }
        
        $sql = '';
        
        for ($i = $lastRelease; $i > $this->release; $i--) {
            $this->log('Odebírám vezri ' . $releases[$i]);
            
            $undoScript = $this->dir . '/' . $releases[$i] . '/' . $this->deltas . '/undo-all-deltas.sql';
            if (file_exists($undoScript)) {
                $sql .= "\n\n" . file_get_contents($undoScript);
            }
            
            $this->removeRelease($releases[$i]);
        }
        
        $rollbackScript = $this->dir . '/' . $releases[$i] . '/' . $this->deltas . '/rollback-all-deltas.sql';
        
        fopen($rollbackScript, 'w');
        file_put_contents($rollbackScript, $sql);
        
        exec('rm -rf ' . $this->current);
        exec('ln -s ' . $this->dir . '/' . $releases[$i] . '/' . ' ' . $this->current);
    }
    
    private function removeRelease($release) {
        exec('rm -rf ' . $this->dir . '/' . $release);
    }
    
    private function getReleases()
    {
        $releases = array();
        
        if ($handle = opendir($this->dir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && is_dir($this->dir . '/' . $file)) {
                    $releases[] = $file;

                }
            }
            closedir($handle);
        }
        
        return $releases;
    }
}

?>