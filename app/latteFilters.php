<?php

  function hideMail($s) {
    /*
     *  Encrypts e-mail address using rot13 cipher.
     *
     *  Args:
     *    $s (string): string to encode
     *
     *  Returns:
     *    string: encoded e-mail address
     */

    return str_rot13($s);
  }

?>
