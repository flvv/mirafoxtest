<?php
#error_reporting(E_ALL);
#error_reporting(E_NOTICE);
require_once 'data.php';


class MatchWin 
{
    protected $data;
 
    function __construct($data) {
        $this->data = $data;
    }


    public function match($c1, $c2) {
        $c1 = isset($this->data[$c1]) ? $this->data[$c1] : $this->error($c1);
        $c2 = isset($this->data[$c2]) ? $this->data[$c2] : $this->error($c2);
        $c1_o = $this->odds($c1);
        $c2_o = $this->odds($c2);
        $max_rand = $c1_o + $c2_o;
        list($c1_score, $c2_score) = 0;

        for ($i=0; $i <= $this->games($c1) + $this->games($c2); $i++) {
            rand(1, $max_rand) <= $c1_o ? $c1_score++ : $c2_score++;
        }

        return array((int)$c1_score, (int)$c2_score);
    }

    protected function error($team){
        exit('Команда с индификатором ' . $team . ' отсутствует');
    }

    protected function odds($var){
       return $this->atack($var) + $this->win($var);
    }

    protected function atack($var){
       return $var['goals']['scored'] / ($var['goals']['scored'] + $var['goals']['skiped']) / 100;
    }

    protected function win($var){
      $percent = ($var['games'] - $var['draw']) / 100;
      return ($var['win'] / $percent) - ($var['defeat'] / $percent);
    }

    protected function games($var){
       return ($var['goals']['scored'] + $var['goals']['skiped']) / $var['games'];
    }


}

function match($c1, $c2) {
    global $data;
    $result = new MatchWin($data);
    return $result->match($c1, $c2);
}

#print_r(match(31, 2));

?>