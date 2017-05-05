<?php
// fonction.php for function php in /home/ay/minishell
// 
// Made by Achat Youmer
// Login   <achat_y@etna-alternance.net>
// 
// Started on  Thu Oct 13 04:18:39 2016 Achat Youmer
// Last update Thu Oct 13 18:11:14 2016 Achat Youmer
//

function	check($tmp)
{
  global	$tab_tri;

  $tab = my_getcsv($tmp, " ");
  if (isset($tab[0]))
    if (array_key_exists($tab[0], $tab_tri))
      $tab_tri[$tab[0]]($tab);
    else
      {
	echo "$tab[0]: Command not found\n";
	prompt();
      }
  else
    {
      echo "NO COMMANDE PUT help FOR MORE INDORMATION\n";
      prompt();
    }
}

function	prompt()
{
  $prompt = "$>";
  if (($tmp = readline($prompt)) === FALSE)
    {
      echo "\nERROR\n";
      my_exit($tmp);
    }
  else 
    check($tmp);
}

function	my_getcsv($str, $deter)
{
  $i = 0;
  $line = 0;
  $tmp = "";
  $tab = array();
  while(isset($str[$i]))
    {
      while (isset($str[$i]) && $str[$i] != $deter)
	{
	  $tmp .= $str[$i];
	  $i = $i + 1;
	}
      $tab[$line] = $tmp;
      $tmp = "";
      $i = $i + 1;
      $line = $line + 1;
    }
  return $tab;
}

function	my_help($tab)
{
  echo "les commandes qui existes :\n";
  echo "   exit : pour quiter\n";
  echo "   pwd : pour afficher le chemin\n";
  echo "   cd : pour changer de dossier\n";
  echo "   echo : pour pour afficher un text\n";
  echo "   ls : pour lister le contenue d'un dossier\n";
  echo "   cat : pour pour afficher le contenue d'un fichier\n";
  echo "   env : pour afficher les variables d'environement\n";
  echo "   setenv : pour pour modifier une variable d'environement\n";
  echo "   unsetenv : pour suprimer une variable d'environement\n";
  prompt();
}