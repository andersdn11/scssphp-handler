# scssphp-handler
Make scssphp crazy easy to use. 

### GETTING STARTED

make sure /scss_php-master/ 
is in same directory as /scss_handler.php

Add this in the head:
<code>
<?php
require_once "./scss_handler.php";

$scss_path = '/styles/';
$css_path = '/styles/';

run_compiler($scss_path, $css_path);
?>
</code>

### WHAT IT DOES

It finds all .scss files that are NOT partials (e.g _part.scss)
And compiles into .css files. 

Oh and..

- it automatically checks if changes have been made to any .scss files and only compiles if true.
- It outputs errors for you.


>100 lines of code.
