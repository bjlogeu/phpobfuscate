<?php

/**
 * Main obfuscator class. This class is used to obfuscate
 * PHP source files.
 *
 * Example usage:
 * $obfuscator = new Obfuscator();
 * $obfuscator->obfuscate("./file.php");
 *
 * The output file will be file.php.obfuscated
 **/
class Obfuscator
{
  //Set to true to remove all comments
  //from source file
  private $deleteComments = true;

  //Set to true to remove all whitespace
  //from source file
  private $deleteWhiteSpace = true;

  //Set this to true to rename all variables
  //to their md5 hashes
  private $renameVariables = true;

  //Set this to true to rename all declared
  //functions to their md5 hashes
  private $renameFunctions = true;

  //Set this to true to rename all classes
  //to their md5 hashes
  private $renameClasses = true;

  //Array holding all comments extracted
  //from source file
  private $allComments = array();

  //Array holding all variables extracted
  //from source file
  private $allVariables = array();

  //Array holding all declared functions extracted
  //from source file
  private $allFunctions = array();

  //Array holding all declared classes extracted
  //from source file
  private $allClasses = array();

  //Holds contents of source file
  private $source;

  function __construct($deleteWhiteSpace = true, $deleteComments = true, $renameVariables = true, $renameFunctions = false, $renameClasses = false)
  {
    $this->deleteComments = $deleteComments;
    $this->deleteWhiteSpace = $deleteWhiteSpace;
    $this->renameVariables = $renameVariables;
    $this->renameFunctions = $renameFunctions;
    $this->renameClasses = $renameClasses;
  }

  /**
   * Main obfuscation function. Opens the source file
   * and extracts all tokens. Depending on the settings used
   * obfuscates different tokens.
   *
   * param file File name to open and obfuscate
   **/
  public function obfuscate($file)
  {
    //Get contents of source file
    $this->source = file_get_contents($file);

    //If unsuccessfull print error
    if($this->source === false)
      die("Could not get file contents");

    $this->parseSource();

    if($this->deleteComments)
      $this->obfuscateComments();

    if($this->deleteWhiteSpace)
      $this->obfuscateWhiteSpace();

    if($this->renameVariables)
      $this->obfuscateVariables();

    if($this->renameFunctions)
      $this->obfuscateFunctions();

    if($this->renameClasses)
      $this->obfuscateClasses();

    file_put_contents($file.".obfuscated", $this->source);
  }

  /**
   * Extracts all tokens of interest from the source
   * code.
   **/
  private function parseSource()
  {
    //get all tokens from source
    $tokens = token_get_all($this->source);
    foreach ($tokens as $token)
    {
      if(!is_string($token[0]))
      {
        //get token names
        $tokenName = token_name($token[0]);

        //if token is comment
        if($tokenName == "T_DOC_COMMENT" || $tokenName == "T_ML_COMMENT" || $tokenName == "T_COMMENT")
          array_push($this->allComments, $token[1]);

        //if token is variable
        if($tokenName == "T_VARIABLE" && $token[1] != "\$this")
          array_push($this->allVariables, str_replace("$", "", $token[1]));

        //extract declared functions
        preg_match_all("/function ([^\(]+)\s*\(/i", $this->source, $this->allFunctions);
        $this->allFunctions = $this->allFunctions[1];

        //Extract all classes and interfaces
        if($tokenName == "T_STRING" && (strpos($this->source, "class ".$token[1]) != false ||
           strpos($this->source, "extends ".$token[1]) != false ||
           strpos($this->source, "implements ".$token[1]) != false ||
           strpos($this->source, "interface ".$token[1]) != false ||
           strpos($this->source, "abstract class".$token[1]) != false)
          )
          array_push($this->allClasses, $token[1]);
      }
    }
  }

  /**
   * Deletes all comments from the source file.
   * That includes comments like //, /* and #
   **/
  private function obfuscateComments()
  {
    $this->source = str_replace($this->allComments, "", $this->source);
  }

  /**
   * Remove all whitespace characters from source file.
   * That includes new lines, tabulations and spaces
   **/
  private function obfuscateWhiteSpace()
  {
    $this->source = preg_replace("/([\t\n\r])/", " ", $this->source);
  }

  /**
   * Rename all variables in the source code to their
   * md5 hash representation
   **/
  private function obfuscateVariables()
  {
    foreach($this->allVariables as $variable)
    {
     $this->source = str_replace($variable, "V".md5($variable), $this->source);
    }
  }


  /**
   * Rename all functions in the source code to their
   * md5 hash representation
   **/
  private function obfuscateFunctions()
  {
    foreach($this->allFunctions as $function)
    {
      //we skip class constructors and destructors
      if($function != "__construct" && $function != "__destruct")
        $this->source = str_replace($function, "F".md5($function), $this->source);
    }
  }

  /**
   * Rename all classes in the source code to their
   * md5 hash representation
   **/
  private function obfuscateClasses()
  {
    echo($this->allClasses);
    foreach($this->allClasses as $klass)
    {
      echo($klass."\n");
      $this->source = str_replace($klass, "C".md5($klass), $this->source);
    }
  }
}

?>
