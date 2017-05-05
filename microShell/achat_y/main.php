#!/usr/bin/env php
<?php
// principal.php for principal in /home/ay/minishell
// 
// Made by Achat Youmer
// Login   <achat_y@etna-alternance.net>
// 
// Started on  Wed Oct 12 22:24:07 2016 Achat Youmer
// Last update Thu Oct 13 18:10:44 2016 Achat Youmer
//
include 'fonction_main.php';
include 'fonction_cmd.php';
include 'fonction_cat.php';
include 'fonction_env.php';
include 'fonction_cd.php';
include 'sortascii.php';

global	$tab_tri ;
$tab_tri = array(
		 "exit" => "my_exit",
		 "pwd" => "my_pwd",
		 "cd" => "my_cd",
		 "echo" => "my_echo",
		 "ls" => "my_ls",
		 "cat" => "my_cat",
		 "env" => "my_env",
		 "setenv" => "my_setenv",
		 "unsetenv" => "my_unsetenv",
		 "help" => "my_help",
		 );
global	$_my_env;
$_my_env["HOME"] = getenv("HOME");
$_my_env["PWD"] = getcwd();
$_my_env["OLDPWD"] = $_my_env["PWD"];
preg_match("/[\w]*$/" ,$_my_env["HOME"], $str);	 	
$_my_env["USER"] = $str[0];
prompt();