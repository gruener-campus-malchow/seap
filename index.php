<?php
/*
 * Created 12.07.2007
 * Recreated 25.02.2011
 * Recreated 08.09.2020
 * Created by alex  - baldauf@gruener-campus-malchow.de
 * 
 * For "SEAP" - Seminar Austausch Projekt
 */
 
 session_start(); 
 
 require_once('config.php');
 
 //Constants
	//HTML

		 $Header= '<!doctype html>' ."\n".
				'<html lang="de">' ."\n".
				'<head>'."\n".
				'<title>'.$Title.'</title>' ."\n".
				'<link rel="stylesheet" type="text/css" media="screen" href="stylesheets/screen.css" >
				<link rel="stylesheet" type="text/css" media="print" href="stylesheets/print.css" >' ."\n".
				'</head>' ."\n".
				'<body><form action="index.php" method="POST" enctype="multipart/form-data">'."\n\n\n";
		 $Foot='</form></body></html>';
	
 
//functions
 
 
// Neues Projektverzeichnis erstellen
	if(isset($_POST[newProject]))
	{
		// Verbindung aufbauen
		//$conn_id = start_FTP_connection($ftp_server,$ftp_user_name,$ftp_password);
		//Verzeichnis auf Server erstellen
		if($_POST[newProject_PW] === $config['newProject_PW'])
		{
    		mkdir('./'.$datapath.'/'.$_POST[newProject]);
    	}
		// Verbindung schlie遝n
		//ftp_close($conn_id);
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
				//$conn_id = start_FTP_connection($ftp_server,$ftp_user_name,$ftp_password);

				// Datei hochladen
				$Upload_names=array_keys($_FILES);
				foreach($Upload_names as $UploadName)
				{		
					if($_FILES[$UploadName][size]>0)
					{
					
					    $uploaddir = './'.$datapath.'/'.$UploadName.'/';
                        $uploadfile = $uploaddir . basename(urlencode($_FILES[$UploadName]['name']));

                        echo '<pre>';
                        if (move_uploaded_file($_FILES[$UploadName]['tmp_name'], $uploadfile)) {
                            echo "Datei ist valide und wurde erfolgreich hochgeladen.\n<br>";
                            //echo "look for: ".$uploadfile;
                            //print_r($_FILES);
                        } else {
                            echo "Möglicherweise eine Dateiupload-Attacke!\n";
                        }

					
					
					
					}
				}
				
				// Verbindung schlie遝n
		
			 }
			 
		
			foreach ($themata as $thema)
			{
				$HTML.='<hr><h1>'.$thema.'</h1><p>';
				$files=scandir($path.$pathspacer.$thema.$pathspacer);			
				foreach ($files as $file)
				{
					if (is_file($path.$pathspacer.$thema.$pathspacer.$file))
					{
						$HTML.='<b>'.$file.'</b> <a href="./'.$datapath.'/'.$thema.'/'.urlencode($file).'">benutzen</a><br />';
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
					<input name="newProject_PW" type="password" size="4" maxlength="4">
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
