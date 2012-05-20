<?php

/**
 *      DATOID: An Experimental CMS for people who crave flexibility.
 * 
 *      A "Datoid" is simply a collection of data. In the case of a
 *      CMS, these collections usually have names like "posts" or
 *      "pages" or "categories." But Datoids aren't just meant to be
 *      types of content - they can hold data about anything.
 * 
 * 
 *      Developed by @wtfribley. 
 */

// Define the Base Path

define('PATH', pathinfo(__FILE__, PATHINFO_DIRNAME) . '/');

// Block Direct Access

define("PRIVATE", true);

// Datoid Version

define("DATOID_VERSION", 0.01);

// Bootstrap

require PATH . 'app/bootstrap.php';