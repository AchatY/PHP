<?php
// fonction4.php for fonction4 in /home/ay/minishell/achat_y
// 
// Made by Achat Youmer
// Login   <achat_y@etna-alternance.net>
// 
// Started on  Thu Oct 13 14:22:43 2016 Achat Youmer
// Last update Thu Oct 13 16:58:27 2016 Achat Youmer
//

function	my_env($tab)
{
  global	$_my_env;

  $tmp = $_my_env;
  $i = 0;
  while ($i < 4)
    {
      $str = key($_my_env) . ":" . current($_my_env) . "\n";
      if ($str != ":\n")
	echo $str;
      next($_my_env);
      $i = $i + 1;
    }
  $_my_env = $tmp;
  prompt();
}

function	my_setenv($tab)
{
  global	$_my_env;

  if (array_key_exists($tab[1], $_my_env))
     $_my_env[$tab[1]] = $tab[2];
  else 
     echo "var d'env not found \n";
   prompt();
}

function	my_unsetenv($tab)
{
  global	$_my_env;
  
  if (array_key_exists($tab[1], $_my_env))
    unset($_my_env[$tab[1]]);
  else 
    echo "$tab[0]: Invalid arguments\n";
  prompt();
}