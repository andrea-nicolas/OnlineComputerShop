<?php

// Cleans one input value before it is used or shown.
// trim      -> removes extra spaces
// stripslashes -> removes backslashes
// htmlspecialchars -> stops HTML/JavaScript from being run (XSS)

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
