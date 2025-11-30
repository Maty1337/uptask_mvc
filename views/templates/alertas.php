<?php

if (!empty($alertas) && (is_array($alertas) || is_object($alertas))):
    foreach($alertas as $key => $mensajes):
        foreach($mensajes as $mensaje):
?>
    <div class="alerta <?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>">
        <?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php 
        endforeach; 
    endforeach;
endif;
?>   