#!/usr/bin/php

<?php 

if ( count( $argv ) < 4 )
{
	
	die( "Usage: datasetCreator DATABASE_NAME DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD\n" );
	
}

try 
{

	// Connect to the database
	$db = new PDO( 'mysql:dbname=' . $argv[1] . ';host=' . $argv[2], $argv[3], 
	               $argv[4] );
	
}
catch( PDOException $e )
{

	die( 'Database Connection Failed: ' . $e->getMessage( ) );
	
}

// Get the list of tables.
$sql = 'SHOW TABLES';
$result = $db->query( $sql );

foreach( $result as $table )
{
	
	// $row[0] should contain the table name
	if ( isset( $table[0] ) )
	{
		
		$tableName = $table[0];
		
		$xmlCode = '<' . $tableName . ' />';
		
		$sql = 'SELECT * FROM ' . $tableName;
		$tableResult = $db->query( $sql, PDO::FETCH_ASSOC );
		
		foreach( $tableResult as $row )
		{
			
			$xml = new SimpleXMLElement( $xmlCode );
			
			foreach( $row as $field => $value )
			{
			
    			$xml->addAttribute( $field, $value );
    			
			}
			
			$lines = explode( "\n", $xml->asXML( ) );
			echo $lines[1] ."\n";
			
		}
		
	}
	
}

?>