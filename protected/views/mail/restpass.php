<?php
?>
Вы можете войти на сайт, воспользовавшись этой ссылкой:



<?php echo CHtml::link('Вход по временному паролю', $this->createAbsoluteUrl('/site/login', array('email'=>$email, 'kw'=>$password))) ?>