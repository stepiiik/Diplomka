<?php

require_once "phing/Task.php";

class PreprocessConfig extends Task {

    private $privateConfig = null;
    
    private $prefix = '###';
    
    private $filesets = array();

    public function setConfig($file) {
        $this->config = $file;
    }
    
    public function setPrefix($prefix) {
        $this->prefix = $prefix;
    }

    public function createFileSet()
    {
        $num = array_push($this->filesets, new FileSet());
        return $this->filesets[$num - 1];
    }

    public function init() {
        
    }
    
    public function main() {
        if (file_exists($this->config)) {
            $this->log('Načítám citlivá data ze souboru');
            $data = $this->parseConfig(file_get_contents($this->config));
            
            if (count($data)) {
                $this->log('Nahrazuji data v konfiguračních souborech');
                
                foreach ($this->filesets as $fs) {
                    $files    = $fs->getDirectoryScanner($this->project)->getIncludedFiles();
                    $fullPath = realpath($fs->getDir($this->project));
                    
                    foreach ($files as $file) {
                        $this->replace($fullPath . '/' . $file, $data);
                    }
                }
            }
        } else {
            $this->log('Soubor ' . $this->config . ' neexistuje');
        }
    }
    
    protected function replace($file, $data) 
    {
        $content = file_get_contents($file);
        foreach ($data as $key => $value) {
            $content = str_replace($this->prefix . $key . $this->prefix, $value, $content);
        }
        file_put_contents($file, $content);
    }
    
    protected function parseConfig($text)
    {
        $data = array();
        
        $rows = explode("\n", $text);
        foreach ($rows as $row) {
            $pair = explode('=', $row);
            if (count($pair) == 2) {
                $data[ trim($pair[0]) ] = trim($pair[1]);
            }
        }
        
        return $data;
    }
}

?>