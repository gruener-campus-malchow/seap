<?php
/*
 * Created 12.07.2007
 * Recreated 25.02.2011
 * Created by alex  - baldauf@gruener-campus-malchow.de
 * 
 * For "SEAP" - Seminar Austausch Projekt
 */
 
 session_start(); 
 
 //Constants
	//HTML
		 $Title="It is not shure that it is a pod - maybe not";
		 $Header= '<?xml version="1.0" encoding="cp1252"?>' ."\n".
				'<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">' ."\n".
				'<head>'."\n".
				'<title>'.$Title.'</title>' ."\n".
				'<link rel="stylesheet" type="text/css" media="screen" href="stylesheets/screen.css" >
				<link rel="stylesheet" type="text/css" media="print" href="stylesheets/print.css" >' ."\n".
				'</head>' ."\n".
				'<body><form action="index.php" method="POST" enctype="multipart/form-data">'."\n\n\n";
		 $Foot='</form></body></html>';
	//Pfade
		 $pathspacer='/'; //UNIX Pathseperator
		 //$pathspacer='\\'; // WINDOWS Pathseperator
		 $datapath='data_sfdoifdngcflaeioweitr0q9firhefbhy';
		 $path=".".$pathspacer.$datapath.$pathspacer;
	//URL
		 $url= "seap_leoputer_release";
	//FTP
		 $ftp_server='leoputer.beispiel.net';
		 $ftp_user_name='nicht richtiger name';
		 $ftp_password='1234';
		 $ftp_root_file='/';
	//login
		$secret_pwd='1234';
 
 //functions
 
 function start_FTP_connection($server,$name,$password)
 {
	 // Verbindung aufbauen
		$connection = ftp_connect($server);

		// Login mit Benutzername und Passwort
		$login_result = ftp_login($connection, $name, $password);

		// Verbindung 黚erpr黤en
		if ((!$connection) || (!$login_result)) {
			echo "FTP-Verbindung ist fehlgeschlagen!";
			echo "Verbindungsaufbau zu $server mit Benutzername $name versucht.";
			exit;
		} 
		return $connection;
 }
 
// Neues Projektverzeichnis erstellen
	if($_POST[newProject]!='')
	{
		// Verbindung aufbauen
		$conn_id = start_FTP_connection($ftp_server,$ftp_user_name,$ftp_password);
		//Verzeichnis auf Server erstellen
		ftp_mkdir($conn_id,$ftp_root_file.$datapath.'/'.$_POST[newProject]);
		// Verbindung schlie遝n
		ftp_close($conn_id);
	}

 
 //Projektverzeichnis lesen
	 $entrys=scandir("$path");
	 $themata=array();
	 $i=1;
	 foreach($entrys as $entry)
	 {
		if(is_dir($path.$entry) and $entry!='.' and $entry!='..')
		{
			array_push($themata,$entry);
			$i++;
		}
	 }

//Header ausgeben
	echo"$Header";
 
//create Website
	 if ($_POST[pwd]==$secret_pwd or $_SESSION[startedYet]==1)
	 {
				$_SESSION[startedYet]=1;
				$HTML='<h2>'.$Title.'</h2>' .
			'<div>Liebe Leute,' .
			'<br />' .
			'Dieses Skript nimmt uns Arbeit ab, denn es zeigt uns die Dateien schön an.' .
			'<br />' .
			'Und wir können sogar Dateien hochladen.<br />Und sogar neue Projekte erstellen.<br />Löschen wäre verantwortunglos!</div>';
			
			
			 foreach($_FILES as $file_ul)
			 {
				if ($file_ul[size]>0) $somethinguploaded=1;
			 }
			 //ftpupload ausf黨ren
			 if ($somethinguploaded)
			 {
				 // Verbindung aufbauen
				$conn_id = start_FTP_connection($ftp_server,$ftp_user_name,$ftp_password);

				// Datei hochladen
				$Upload_names=array_keys($_FILES);
				foreach($Upload_names as $UploadName)
				{		
					if($_FILES[$UploadName][size]>0)
					{
						$target=$ftp_root_file.$datapath.'/'.$UploadName.'/'.$_FILES[$UploadName][name];
						$upload = ftp_put($conn_id, $target, $_FILES[$UploadName][tmp_name], FTP_BINARY);
						// Upload 黚erpr黤en
						if (!$upload) {
							$UploadMessage[$UploadName]="FTP-Upload ist fehlgeschlagen!";
						} else {
							$UploadMessage[$UploadName]= "Datei <b>".$_FILES[$UploadName][name]."</b> auf Server <b>$ftp_server</b> hochgeladen";
						
						}
					}
				}
				
				// Verbindung schlie遝n
				ftp_close($conn_id);
			 }
			 
		
			foreach ($themata as $thema)
			{
				$HTML.='<hr><h1>'.$thema.'</h1><p>';
				$files=scandir($path.$pathspacer.$thema.$pathspacer);			
				foreach ($files as $file)
				{
					if (is_file($path.$pathspacer.$thema.$pathspacer.$file))
					{
						$HTML.='<b>'.$file.'</b> <a href="'.$url.'/'.$datapath.'/'.$thema.'/'.$file.'">benutzen</a><br />';
					}
				}
				$HTML.='<br />
					Weitere Datei hochladen <input name="'.$thema.'" type="file">
					   <input type="submit" value="Hochladen"><br />'.$UploadMessage[$thema].'
					</p>
					
					';
			}
			$HTML.='<br />
					<br />

					
					<hr><h1>Neues Projekt anlegen</h1>
					<p>
					Niemals (!!!) Leerzeichen oder Sonderzeichen benutzen.
					<br />
					<input name="newProject" type="text" size="51" maxlength="50">
					<input type="submit" value="Neues Projekt erstellen">
					</p>';
			echo $HTML;
	 }else{
		
	
		$HTML='<h2>'.$Title.'</h2>';
		$HTML.='Enter your birthday, please (YYYY.MM.DD:) <input type="password" name="pwd">' .
				'<input type="submit" value="Ok" name="Ok">';
		echo $HTML; 	
	} //end create Website
//Footer ausgeben
 echo $Foot;
?>
