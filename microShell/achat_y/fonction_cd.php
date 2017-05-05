<?php
// fonction_cd.php for fonction_cd in /home/ay/minishell/achat_y
// 
// Made by Achat Youmer
// Login   <achat_y@etna-alternance.net>
// 
// Started on  Thu Oct 13 15:21:05 2016 Achat Youmer
// Last update Thu Oct 13 17:00:34 2016 Achat Youmer
//

function	my_cd($tab)
{
  global	$_my_env;

  if (isset($tab[1]))
    {
      if (is_dir($tab[1]))
	{
	  chdir($tab[1]);
	  changepwd();
	}
      else
	cd_checkoption($tab);
    }
  else
    cd_checkhome();
  prompt();
}

function	cd_checkhome()
{
  global	$_my_env;

  if (isset($_my_env["HOME"]))
    {
      if (is_dir($_my_env["HOME"]))
	{
	  chdir($_my_env["HOME"]);
	  changepwd();
	}
      else if (file_exists($_my_env["HOME"]))
	echo "ERROR: HOME:IS NOT A DIRECTORY\n";
      else
	echo "ERROR: HOME: NOT FOUND\n";
    }
}

function	cd_ereur($tab)
{
  if (file_exists($tab[1]))
    echo "cd: $tab[1]:Not a directory\n";
  else
    echo "cd: $tab[1]: No such file or directory\n";
}

function	cd_checkoption($tab)
{
  global	$_my_env;

  if ($tab[1] == "-")
    {
      if (isset($_my_env["OLDPWD"]))
	chdir($_my_env["OLDPWD"]);
      else
	echo "ERROR: NO OLDPWD FOUND\n";
      changepwd();
    }
  else if ($tab[1] == "~")
    cd_checkhome();
  else if ($tab[1] != "..")
    cd_ereur($tab);
}

function	changepwd()
{
  global	$_my_env;

  if (isset($_my_env["OLDPWD"]) && isset($_my_env['PWD']))
    $_my_env["OLDPWD"] = $_my_env["PWD"];
  if (isset($_my_env['PWD']))
    $_my_env["PWD"] = getcwd();
}