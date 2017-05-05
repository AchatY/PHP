<?php
check_function($argv);
check_espace_end($argv);
check_espace_motcle($argv);
check_header($argv);
check_parameters($argv);
check_characters($argv);

function check_espace_motcle($argv)
{
    if (isset($argv[1]))
    {
        $file = $argv[1];
        $handle = fopen($file, 'r');
        $count = 0;
        while (!feof($handle))
        {
            $lines = fgets($handle);
            $count++;
            if (preg_match_all("/#define/", $lines, $ind))
                echo "Erreur: presence d'un define dans un .c: ligne $count\n";
            if (preg_match_all("/(#include)/", $lines, $ind) &&
                !preg_match_all("/(#include) [<'].+.h[>']/", $lines, $output_array))
                echo "Erreur: Ordonnement des includes: ligne $count\n";

            if (preg_match_all("/\w+\s+\w+\s=\s.+;/", $lines, $ind))
                echo "Erreur: Declaration et affectation sur la meme ligne: ligne $count\n";
            if (preg_match_all("/(while|for|if|else if|switch|else)\(/", $lines, $ind))
                echo "\t\033[31m Erreur: \033[32m $file:\e[33m Espace manquant après mot clé: ligne $count\n";
        }
        fclose($handle);
    }
}

function check_espace_end($argv)
{
    if (isset($argv[1]))
    {
        $result = file_get_contents($argv[1]);
        $i = 0;
        $nb_ligne = 0;
        while (isset($result[$i]))
        {
            if ($result[$i] == "\n")
            {
                $nb_ligne++;
                if (isset ($result[$i - 1]) && $result[$i - 1] == " ")
                    echo "Erreur: Espace en fin de ligne $nb_ligne\n";
            }
            $i++;
        }
    }
}

function check_function ($argv)
{
    if (isset($argv[1]))
    {
        $result = file_get_contents($argv[1]);
        $i = 0;
        $nb_f = 0;
        $ind = 0;
        $ind_inc = 0;
        $ind_ligne = 0;
        $nb_ligne = 0;
        while (isset($result[$i]))
        {
            if ($result[$i] == "\n")
            {
                $nb_ligne++;
                if ($ind_inc == 1)
                {
                    saut_dec_var($result, $nb_ligne - 1, $i);
                    if (isset($result[$i + 1]) && $result[$i + 1] == "\n" && double_saut_ligne($result, $i) == 0)
                        echo "Erreur: Double retour à la ligne: ligne $nb_ligne\n";
                    $ind_ligne++;
                }
                else if ($ind_inc == 0)
                    $ind_ligne = 0;
            }
            if ($result[$i] == '{')
            {
                $ind++;
                $ind_inc = 1;
            }
            else if ($result[$i] == '}')
            {
                $ind--;
                $ind_inc = 1;
            }
            if ($ind == 0  && $ind_inc == 1)
            {
                $nb_f++;
                if ($ind_ligne > 25)
                    echo "Erreur: la fonctions $nb_f depasse les 25 ligne \n";
                if (isset($result[$i + 2]) && $result[$i + 2] != "\n")
                    echo "Erreur: Oublie de saut de ligne apres une fonction : ligne ".($nb_ligne + 1)." \n";
                $j = 3;
                while (isset($result[$i + $j]) && $result[$i + $j] == "\n")
                {
                    echo "Erreur: Double saut de ligne : ligne".($nb_ligne + $j)."\n";
                    $j++;
                }
                $ind_inc = 0;
            }
            $i++;
        }
        if ($nb_f > 5)
        {
            echo "Erreur: Y'a Plus de 5 fonctions dans le fichier: $argv[1]\n";
        }
    }
}

function saut_dec_var($result, $nb_ligne, $i)
{
    $str =  recuper_ligne_prec($result, $i);
    if (preg_match_all("/\w+\s+[^ ]+;/", $str['res'], $ind) ||
        preg_match_all("/\w+\s+\([\w]+\)\(.+\);/", $str['res'], $ind))
    {
        if (!preg_match_all("/\w+\t+[^ ]+;/", $str['res'], $ind1) &&
            !preg_match_all("/\w+\t+\([\w]+\)\(.+\);/", $str['res'], $ind1) &&
            !preg_match_all("/(return)\s+.+;/", $ind[0][0], $ind1))
            echo "Erreur: Tabulation pour la Declaration de variable: ligne ".($nb_ligne+1)." \n";

        if (isset ($result[$i + 1]) &&
            $result[$i + 1] != "\n" &&
            !preg_match_all("/(return)\s+.+;/", $ind[0][0], $ind))
        {
            $str1 = recuper_ligne_suiv($result, $i);
            if ((!preg_match_all("/\w+\s+[^ ]+;/", $str1['res'], $ind) &&
                    !preg_match_all("/\w+\s+\([\w]+\)\(.+\);/", $str1['res'], $ind)) ||
                preg_match_all("/(return)\s+.+;/", $str1['res'], $ind))
            {
                $nb_ligne++;
                echo "Erreur oublie d'un saut a la ligne apres dec_var: ligne" . $nb_ligne . "\n";
            }
        }
    }
    else
        $dec_var = 0;
}

function recuper_ligne_prec($result, $i)
{
    $a = 0;
    while (isset($result[$i - 1]) && $result[$i - 1] != "\n" && $i > 0)
    {
        $a++;
        $i--;
    }
    $str['i'] = $i - 1;
    $i = $i + $a;
    $res = "";
    while ($a > 0)
    {
        $res = $res . $result[$i - $a];
        $a--;
    }
    $str['res'] = $res;
    return $str;
}

function recuper_ligne_suiv($result, $i)
{
    $a = 0;
    while (isset($result[$i + 1]) && $result[$i + 1] != "\n")
    {
        $a++;
        $i++;
    }
    $str['i'] = $i - 1;
    $i = $i - $a;
    $res = "";
    while ($a > 0)
    {
        $res = $res . $result[$i + 1];
        $i++;
        $a--;
    }
    $str['res'] = $res;
    return $str;
}

function double_saut_ligne($result, $i)
{
    $a = 0;
    while ($result[$i - 1] != "\n" && $i > 0)
    {
        $a++;
        $i--;
    }
    $i = $i + $a;
    $res = "";
    while ($a >= 0)
    {
        $res = $res . $result[$i - $a];
        $a--;
    }
    if (preg_match_all("/\w+\s+[^ ]+;/", $res, $ind) || preg_match_all("/\w+\s+\([\w]+\)\(.+\);/", $res, $ind))
    {
        if (preg_match_all("/(return)\s+.+;/", $ind[0][0], $ind))
            $dec_var = 0;
        else
            $dec_var = 1;
    }
    else
        $dec_var = 0;
    return $dec_var;
}

function check_characters($argv)
{
    $file = $argv[1];
    $handle = fopen($file, 'r');
    $i = 0;
    $resultsArray = array();
    while (!feof($handle))
    {
        $lines = fgets($handle);
        $resultsArray[] = $lines;
        if (iconv_strlen($resultsArray[$i], "UTF-8") > 80)
            echo "\t\033[31m Erreur: \033[32m $argv[1]:\e[33m Plus de 80 caractères sur la ligne " . ($i + 1) . "\n\033[0m";
        $i++;

    }
    fclose($handle);
}


function check_parameters($argv)
{
    error_reporting(0);
    $file = $argv[1];
    $handle = fopen($file, 'r');
    $nb_ligne = 0;
    while (!feof($handle))
    {
        $lines = fgets($handle);
        $nb_ligne++;
        if (preg_match_all("/(\w+\s\w+\(.*\))\n/", $lines, $ind))
        {
            if (preg_match_all("/\(.+\)/", $ind[1][0], $ind2))
            {
                if (preg_match_all("/,/", $ind2[0][0], $ind3))
                {
                    $j = 0;
                    while (isset($ind3[0][$j]))
                    {
                        $j++;
                    }
                    if ($j >= 4)
                        echo "\t\033[31m Erreur: \033[32m $argv[1]:\e[33m Plus de 4 paramètres sur la fonction à la ligne $nb_ligne\n\033[0m";
                }
            }
        }
    }
    fclose($handle);
}

function check_header($argv)
{
    $file = $argv[1];
    $handle = fopen($file, 'r');
    $read = fread($handle, filesize($file));
    $path = dirname($read);
    $header = "\/\*\n\*\* (" . $file . "|Makefile) .*\n\*\*.*\n\*\* Made by .*\n\*\* Login   <[a-z]{2,6}\_[a-z]{1}@etna-alternance\.net>\n\*\*.*\n\*\* Started on  [A-Za-z]{3} [A-Za-z]{3}.*\n\*\*.*\n\*\/";
    preg_match_all("/" . $header . "/", $read, $matches);

    if (!isset($matches[0][0]))
    {
        echo "\n	/***************************************\
	|                \e[31mTRICHE\e[33m                 |
	|     \e[31mErreur: \033[32m" . $argv[1] . ":\e[33m Mauvais Header\033   |
	\***************************************/\n\n";
    }
    fclose($handle);
}

?>
