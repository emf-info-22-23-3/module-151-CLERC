<?php
class Ctrl
{
 public function getEquipes(){
  require('wrk.php');
  $wrk = new Wrk();
  return $wrk->getEquipesFromDB();
 } 
}
?>