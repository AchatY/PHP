<?php
// fonction3.php for fonction3 in /home/ay/minishell/achat_y
// 
// Made by Achat Youmer
// Login   <achat_y@etna-alternance.net>
// 
// Started on  Thu Oct 13 11:01:31 2016 Achat Youmer
// Last update Thu Oct 13 17:01:17 2016 Achat Youmer
//

function	my_cat($tab)
{
  if (isset($tab[1]))
    {
      $i = 1;
      while (isset($tab[$i]) && checkfile($tab, $i) == 1)
	{
	  if ($str = file_get_contents($tab[$i]))
	    echo $str . "\n";
	  $i = $i + 1;
	}
    }
  else
    echo "$tab[0]: Invalid arguments\n";
  prompt();
}

function	checkfile($tab, $i)
{
  if (file_exists($tab[$i]))
    {
      if (is_dir($tab[$i]))
	echo "$tab[0]: $tab[$i]: Is a directory\n";
      else
	{
	  if (is_readable($tab[$i]))
	    {
	      if ($str = fopen($tab[$i], "r"))
		return (1);
	      else
		echo "$tab[0]: $tab[$i]: Cannot open file\n";
	    }
	  else
	    echo "$tab[0]: $tab[$i]: Permission denied\n";
	}
    }
  else
    echo "$tab[0]: $tab[$i]: No such file or directory\n";
}