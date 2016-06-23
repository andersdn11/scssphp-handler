<?php
require_once "./scssphp-master/scss.inc.php"; 
    
use Leafo\ScssPhp\Compiler;
use Leafo\ScssPhp\Server;


function run_compiler($scss_path, $css_path){
    $scss_dir = $_SERVER['DOCUMENT_ROOT']. $scss_path;
    $css_dir = $_SERVER['DOCUMENT_ROOT']. $css_path;

    global $scss;
    $scss = new Compiler();
    $scss->setImportPaths($scss_dir);
    $scss->setFormatter('Leafo\ScssPhp\Formatter\Compressed');


    if( needs_compiling($scss_dir, $css_dir) ){
            compile_files($scss_dir, $css_dir, $scss);
    }
}

// NEEDS COMPILING
function needs_compiling($scss_dir, $css_dir) {

      $latest_scss = 0;
      $latest_css = 0;

      foreach ( new RecursiveIteratorIterator(new RecursiveDirectoryIterator($scss_dir)) as $sfile ) {
        if (pathinfo($sfile->getFilename(), PATHINFO_EXTENSION) == 'scss') {
          $file_time = $sfile->getMTime();

          if ( (int) $file_time > $latest_scss) {
            $latest_scss = $file_time;
          }
        }
      }

      foreach ( new RecursiveIteratorIterator(new RecursiveDirectoryIterator($css_dir)) as $cfile ) {
        if (pathinfo($cfile->getFilename(), PATHINFO_EXTENSION) == 'css') {
          $file_time = $cfile->getMTime();

          if ( (int) $file_time > $latest_css) {
            $latest_css = $file_time;
          }
        }
      }

      if ($latest_scss > $latest_css) {
        return true;
      } else {
        return false;
      }
    }

// COMPILE FILES
function compile_files($scss_dir, $css_dir, $scss){
            $input_files = array();
          // Loop through directory and get .scss file that do not start with '_'
          foreach(new DirectoryIterator($scss_dir) as $file) {
            if (substr($file, 0, 1) != "_" && pathinfo($file->getFilename(), PATHINFO_EXTENSION) == 'scss') {
              array_push($input_files, $file->getFilename());
            }
          }

          // For each input file, find matching css file and compile
          foreach ($input_files as $scss_file) {
            $input = $scss_dir.$scss_file;
            $outputName = preg_replace("/\.[^$]*/",".css", $scss_file);
            $output = $css_dir.$outputName;

            try {
                $scssIn = file_get_contents($input);
                $cssOut = $scss->compile($scssIn);
                file_put_contents($output, $cssOut);

            } catch (Exception $e) {
                  $errors = array (
                    'message' => $e->getMessage(),
                    );
                  echo '<pre>';
                    echo $errors['message'];
                  echo '</pre>';
              }
          }
}
    

?>