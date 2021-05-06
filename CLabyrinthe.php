<?php

// no direct access
// defined('_ACCESSIBLE') or trigger_error('Direct access of this file is not allowed!', E_USER_ERROR);

class CLabyrinthe
{
   // file codage
   const FCHAR_NONE = '.';
   const FCHAR_WALL = '*';
   const FCHAR_PLAY = 'S';
   const FCHAR_GOAL = 'E';

   // grid codage
   const GCHAR_NONE = '.';
   const GCHAR_WALL = '*';
   const GCHAR_PLAY = 'S';
   const GCHAR_GOAL = 'E';
 
   // variables
   private static $grid = array();
   private static $rowCount = 0;
   private static $colCount = 0;
   private static $rowPos = 0;
   private static $colPos = 0;
   private static $moveCount = 0;
   private static $ready = FALSE;
   private static $win = FALSE;

   public static function getMoveCount(){
      return $moveCount;
   }
   public static function getReady(){
      return $ready;
   }
   public static function getWin(){
      return $win;
   }

   // load a grid from a file
   public static function load($fileName){
      $moveCount = 0;
      $ready = FALSE;
      $win = FALSE;
      $err = FALSE;
      $lines = file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $rowCount = count($lines);
      foreach($lines as $lineNum => $line){
         $chars = str_split($line);
         $colCount = count($chars);
         foreach($chars as $charNum => $char){
            if($char == FCHAR_NONE){
               $grid[$lineNum][$charNum] = GCHAR_NONE;
            }elseif($char == FCHAR_WALL){
               $grid[$lineNum][$charNum] = GCHAR_WALL;
            }elseif($char == FCHAR_PLAY){
               $grid[$lineNum][$charNum] = GCHAR_PLAY;
               $rowPos = $lineNum;
               $colPos = $charNum;
            }elseif($char == FCHAR_GOAL){
               $grid[$lineNum][$charNum] = GCHAR_GOAL;
            }else{
               $err = TRUE;
            }
         }
      }
      if(!$err){
         $ready = TRUE;
      }
   }

   public static function display(){
      echo '<div>' . PHP_EOL;
      for($row = 0; $row < $rowCount; $row++){
         for($col = 0; $col < $colCount; $col++){
            echo $grid[$row][$col] . PHP_EOL;
         }
         echo '<br>' . PHP_EOL;
      }
      echo '</div>' . PHP_EOL;
   }

   public static function moveUp(){
      if($rowPos > 0){
         if($grid[$rowPos - 1][$colPos] == GCHAR_NONE){
            // move
            $grid[$rowPos][$colPos] = GCHAR_NONE;
            $rowPos = $rowPos - 1;
            $grid[$rowPos][$colPos] = GCHAR_PLAY;
            $moveCount++;
         }elseif($grid[$rowPos - 1][$colPos] == GCHAR_GOAL){
            // move
            $grid[$rowPos][$colPos] = GCHAR_NONE;
            $rowPos = $rowPos - 1;
            $grid[$rowPos][$colPos] = GCHAR_PLAY;
            $moveCount++;
            // win
            $win = TRUE;
         }
      }
   }
   
   public static function moveDown(){
      if($rowPos < $rowCount){
         if($grid[$rowPos + 1][$colPos] == GCHAR_NONE){
            // move
            $grid[$rowPos][$colPos] = GCHAR_NONE;
            $rowPos = $rowPos + 1;
            $grid[$rowPos][$colPos] = GCHAR_PLAY;
            $moveCount++;
         }elseif($grid[$rowPos + 1][$colPos] == GCHAR_GOAL){
            // move
            $grid[$rowPos][$colPos] = GCHAR_NONE;
            $rowPos = $rowPos + 1;
            $grid[$rowPos][$colPos] = GCHAR_PLAY;
            $moveCount++;
            // win
            $win = TRUE;
         }
      }
   }
   
   public static function moveLeft(){
      if($colPos > 0){
         if($grid[$rowPos][$colPos - 1] == GCHAR_NONE){
            // move
            $grid[$rowPos][$colPos] = GCHAR_NONE;
            $colPos = $colPos - 1;
            $grid[$rowPos][$colPos] = GCHAR_PLAY;
            $moveCount++;
         }elseif($grid[$rowPos][$colPos - 1] == GCHAR_GOAL){
            // move
            $grid[$rowPos][$colPos] = GCHAR_NONE;
            $colPos = $colPos - 1;
            $grid[$rowPos][$colPos] = GCHAR_PLAY;
            $moveCount++;
            // win
            $win = TRUE;
         }
      }
   }
   
   public static function moveRight(){
      if($colPos < $colCount){
         if($grid[$rowPos][$colPos + 1] == GCHAR_NONE){
            // move
            $grid[$rowPos][$colPos] = GCHAR_NONE;
            $colPos = $colPos + 1;
            $grid[$rowPos][$colPos] = GCHAR_PLAY;
            $moveCount++;
         }elseif($grid[$rowPos][$colPos + 1] == GCHAR_GOAL){
            // move
            $grid[$rowPos][$colPos] = GCHAR_NONE;
            $colPos = $colPos + 1;
            $grid[$rowPos][$colPos] = GCHAR_PLAY;
            $moveCount++;
            // win
            $win = TRUE;
         }
      }
   }
  
};
   
