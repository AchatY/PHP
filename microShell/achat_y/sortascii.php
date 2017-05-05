<?php
// sortascii.php for sortascii in /home/ay/minishell/achat_y
// 
// Made by Achat Youmer
// Login   <achat_y@etna-alternance.net>
// 
// Started on  Thu Oct 13 16:18:35 2016 Achat Youmer
// Last update Thu Oct 13 16:54:23 2016 Achat Youmer
//

function	my_strcmp($str1, $str2)
{
  $i = 0;
  while (isset($str1[$i]) && isset($str2[$i]))
    {
      if (ord($str1[$i]) > ord($str2[$i]))
	{
	  return (1);
	}
      else if (ord($str1[$i]) < ord($str2[$i]))
	{
	  return (-1);
	}
      $i = $i + 1;
    }
  if (isset($str2[$i]) == FALSE)
    {
      return (1);
    }
  return (-1);
}

function	sort_ascii(&$str)
{
  $i = 0;
  while (isset($str[$i]))
    {
      $j = $i;
      while (isset($str[$j]))
	{
	  if (my_strcmp($str[$i], $str[$j]) == 1)
	    {
	      $swap = $str[$i];
	      $str[$i] = $str[$j];
	      $str[$j] = $swap;
	    }
	  $j = $j + 1;
	}
      $i = $i + 1;
    }
  return ($str);
}