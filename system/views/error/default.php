<h1>Error!</h1>

<?php if(!empty($this->error)){ echo $this->error; } ?>

<?php if(!empty($this->errorActual)){ echo '<!-- '.Dencrypt::encrypt($this->errorActual).' -->'; } ?>