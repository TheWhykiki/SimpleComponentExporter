<?php
/**
* Copy a file, or recursively copy a folder and its contents
* @author      Aidan Lister <aidan@php.net>
* @version     1.0.1
* @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
* @param       string   $source    Source path
* @param       string   $dest      Destination path
* @param       int      $permissions New folder creation permissions
* @return      bool     Returns true on success, false on failure
*/
error_reporting(E_ALL);
$componentName = 'com_simpleshop';

function xcopy($source, $dest, $permissions = 0755)
{
	// Check for symlinks
	if (is_link($source)) {
	return symlink(readlink($source), $dest);
	}

	// Simple copy for a file
	if (is_file($source)) {
	return copy($source, $dest);
	}

	// Make destination directory
	if (!is_dir($dest)) {
	mkdir($dest, $permissions, true);
	}

	// Loop through the folder
	$dir = dir($source);
	while (false !== $entry = $dir->read()) {
	// Skip pointers
	if ($entry == '.' || $entry == '..') {
	continue;
	}

	// Deep copy directories
	xcopy("$source/$entry", "$dest/$entry", $permissions);
	}

	// Clean up
	$dir->close();
	echo 'file';
	return true;
}

// Get admin sources

rmdir('copiedComponent');

xcopy('administrator/components/'.$componentName,'copiedComponent/'.$componentName.'/admin');

xcopy('components/'.$componentName,'copiedComponent/'.$componentName.'/site');

xcopy('media/'.$componentName,'copiedComponent/'.$componentName.'/media');

rename('copiedComponent/'.$componentName.'/admin/'.str_replace ( 'com_' , '',$componentName).'.xml', 'copiedComponent/'.$componentName.'/'.str_replace ( 'com_' , '',$componentName).'.xml');

?>