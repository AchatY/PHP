<?php
// fonction2.php for fonction2 in /home/ay/minishell/achat_y
// 
// Made by Achat Youmer
// Login   <achat_y@etna-alternance.net>
// 
// Started on  Thu Oct 13 10:59:13 2016 Achat Youmer
// Last update Thu Oct 13 16:56:29 2016 Achat Youmer
//

function	my_echo($tab)
{
  $i = 1;
  while (isset($tab[$i]))
    {
      $j = 0;
      if (preg_replace("/\"/","" , $tab[$i]))
	$tab[$i] = preg_replace("/\"/","" , $tab[$i]);
      preg_match_all("/([\w]+)/", $tab[$i], $str);
      while (isset($str[0][$j]))
	{
	  echo $str[0][$j] . " ";
	  $j = $j + 1;
	}
      $i = $i + 1;
    }
  echo "\n";
  prompt();
}

function	my_pwd()
{
  $_my_env["PWD"] = getcwd();
  echo $_my_env["PWD"] . "\n";
  prompt();
}

function	my_ls($tab)
{
  $j = 0;
  if ($d = checklist($tab))
    {
      while ( false !== ($file = $d->read()))
	{
	  $str[$j] = $file;
	  $j = $j + 1;
	}
      $j = 0;
      sort_ascii($str);
      while (isset($str[$j]))
	{
	  if ($str[$j] != "." && $str[$j] != ".." &&  $str[$j][0] != ".")
	    {
	      if (is_dir($str[$j]))
		echo $str[$j] . "/" . "\n";
	      else if (is_executable($str[$j]))
		echo $str[$j] . "*" . "\n";
	      else if (is_link($str[$j]))
		echo $str[$j] . "@" . "\n";
	      else
		echo $str[$j] . "\n";
	    }
	  $j = $j + 1;
	}
      prompt();
    }
}

function	my_exit($tab)
{
  return ;
}

function	checklist($tab)
{
  if (isset($tab[1]))
    {
      if (file_exists($tab[1]))
	{
	  if (is_dir($tab[1]))
	    return (dir($tab[1]));
	  else
	    {
	      if (is_executable($tab[1]))
		echo $tab[1] . "*" . "\n";
	      else if (is_link($tab[1]))
		echo $tab[1] . "@" . "\n";
	      else
		echo $tab[1] . "\n";
	      prompt();
	    }
	}
      else
	{
	  echo "$tab[0]: $tab[1]: No such file or directory\n";
	  prompt();
	}
    }
  else
    return (dir('.'));
}