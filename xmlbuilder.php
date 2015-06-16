<?php 

$text = "Anterior: Head
Anterior: Chest
Anterior: Abdomen
Tail
Anterior: Right Arm
Anterior: Right Forearm/Hand
Anterior: Left Arm
Anterior: Left Forearm/Hand
Anterior: Right Thigh
Anterior: Right Shin/Foot
Anterior: Left Thigh
Anterior: Left Shin/Foot
Posterior: Head
Posterior: Superior Back
Posterior: Inferior Back
Tail
Posterior: Left Arm
Posterior: Left Forearm/Hand
Posterior: Right Arm
Posterior: Right Forearm/Hand
Posterior: Left Thigh
Posterior: Left Calf/Foot
Posterior: Right Thigh
Posterior: Right Calf/Foot";

$values = preg_split("/\n/", $text);

foreach ($values as $key=>$value)
{
    
    echo htmlentities('<bodypart id="'.($key+1).'" value="'.$value.'"/>').'<br>';
}