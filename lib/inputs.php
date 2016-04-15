<?php

function backup_inputs($mysqli,$emoncms_server,$emoncms_apikey,$userid)
{
  $inputs = file_get_contents($emoncms_server."/input/list.json?apikey=$emoncms_apikey");
  $inputs = json_decode($inputs);
  
  foreach ($inputs as $input)
  {
    echo json_encode($input)."\n";
    $mysqli->query("DELETE FROM input WHERE `id` = '".$input->id."'");
    $mysqli->query("INSERT INTO input (`id`,`userid`,`name`,`processList`,`time`,`value`,`nodeid`,`description`) VALUES ('".$input->id."','".$userid."','".$input->name."','".$input->processList."','".$input->time."','".$input->value."','".$input->nodeid."','".$input->description."')");

  }
}
