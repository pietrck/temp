<?php
include_once __DIR__ .'/inc/class/Query.php';
$query = new Query();
$emailConfig = $query->emailInfo();

$host = $emailConfig['hostEmail'];
$usuario = $emailConfig['email'];
$senha = $emailConfig['passEmail'];

function flatMimeDecode($string) {
	$str = '';
    $array = imap_mime_header_decode ($string);
    foreach ($array as $key => $part) {
        $str .= $part->text;
        }

    return $str;
}


function gravaCobian($x,$y,$pass){
	//recebe a conexao
	$inbox = imap_open("{".$x.":993/imap/ssl}INBOX/Cobian", $y, $pass)or die("can't connect: " . imap_last_error());

	$emails = imap_search($inbox,'UNSEEN');

	if($emails){

		/* if any emails found, iterate through each email */
		if($emails){

		    /* put the newest emails on top */
		    rsort($emails);

		    /* for every email... */
		    foreach($emails as $email_number) {
		        /* get information specific to this email */
		        $overview = imap_fetch_overview($inbox,$email_number,0);

		        $message = imap_fetchbody($inbox,$email_number,2);

		        /* get mail structure */
		        $structure = imap_fetchstructure($inbox, $email_number);

		        $attachments = array();

		        /* if any attachments found... */
		        if(isset($structure->parts) && count($structure->parts)){
		            for($i = 0; $i < count($structure->parts); $i++){
		                $attachments[$i] = array(
		                    'is_attachment' => false,
		                    'filename' => '',
		                    'name' => '',
		                    'attachment' => ''
		                );

		                if($structure->parts[$i]->ifdparameters) 
		                {
		                    foreach($structure->parts[$i]->dparameters as $object) 
		                    {
		                        if(strtolower($object->attribute) == 'filename') 
		                        {
		                            $attachments[$i]['is_attachment'] = true;
		                            $attachments[$i]['filename'] = $object->value;
		                        }
		                    }
		                }

		                if($structure->parts[$i]->ifparameters) 
		                {
		                    foreach($structure->parts[$i]->parameters as $object) 
		                    {
		                        if(strtolower($object->attribute) == 'name') 
		                        {
		                            $attachments[$i]['is_attachment'] = true;
		                            $attachments[$i]['name'] = $object->value;
		                        }
		                    }
		                }

		                if($attachments[$i]['is_attachment']) 
		                {
		                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);

		                    /* 3 = BASE64 encoding */
		                    if($structure->parts[$i]->encoding == 3) 
		                    { 
		                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
		                    }
		                    /* 4 = QUOTED-PRINTABLE encoding */
		                    elseif($structure->parts[$i]->encoding == 4) 
		                    { 
		                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
		                    }
		                }
		            }
		        }

		        $folder = __DIR__."/";

		        foreach($attachments as $attachment){
		            $overview = imap_fetch_overview($inbox,$email_number,0);
		            $overview = $overview[0];
		            $titulo = htmlentities($overview->subject);
		            $elements = imap_mime_header_decode($titulo);
		            $filename = htmlspecialchars_decode($elements[0]->text);

		            if($attachment['is_attachment'] == 1){
		                $fp = fopen($folder ."/". $z ." - ".$filename.'.zip', "w+");
		                fwrite($fp, $attachment['attachment']);
		                fclose($fp);

						$sql= '';
						$file = $folder ."/". $z ." - ".$filename.'.zip';
						$za = new ZipArchive(); //informa que a variavel é de ZIP
						$za->open($file); //abre o arquivo
						$array = array();//array novo

						for( $i = 0; $i < $za->numFiles/*conta quandos arquivos tem no zip*/; $i++ ){ 
						    $stat = $za->statIndex( $i ); //informacoes do arquivo
						    $array[$i] = $stat['name'];//coloca o nome do arquivo no array
						}
						$last = $array[count($array)-1];//mostra o ultimo arquivo

						$result = file_get_contents('zip://'.$file.'#'.$last); //pega o arquivo na posicao do ultimo
						$za->close($file);

						//tratamento do body
						$convert = mb_convert_encoding($result, 'UTF-8', 'UTF-16LE');//conversao de charset
						$convert = ltrim($convert);//espaço esquerda
						$convert = rtrim($convert);//espaço direita

						//tratamento da data sem horario
						$data = substr($convert, 4, 13);//so a data
						$data = ltrim($data);//espaço esquerda
						$data = rtrim($data);//espaço direita
						
						$convert = str_replace($data ,'<br>', $convert);//troca a data para criar linha (?)
						$convert = str_replace("*" ,'', $convert);//troca 0 astesrisco por nada
						$convert2 = explode('<br>', $convert);//explode array
						$pos = strpos($convert2[count($convert2)-2], 'total');//conta a posição

						//tratamento do nome
					 	$zname = explode(" - ", $file);
						$zname[1] = ltrim($zname[1]);//espaço esquerda
						$zname[1] = rtrim($zname[1]);//espaço direita
						$zname[1] = substr($zname[1],0,-4);//espaço direita

						$id_empresa = SwitchGeral($zname[1]);
						
					 	//data com horario
					 	$ydata = "'".$data ." ". substr($convert2[count($convert2)-2], 1, 5)."'";
					 	$yname = "'".$zname[1]."'";
					 	$yid_empresa = "'".$id_empresa."'";

						$cbackup = substr($convert2[count($convert2)-2],$pos+7,1);

						$tags =  $convert;
						$termohdd = "pode encontrar o caminho especificado";

						$pattern = '/' . $termohdd . '/';//Padrão a ser encontrado na string $tags
						if (preg_match($pattern, $tags)) {
						  $backup = 5;
						}else{
							if ($cbackup == 0) {
								$backup = "'2'";
							}else{
								$backup = "'1'";
							}
						}

						if ($id_empresa <> '' and $id_empresa <> '0') {
							$conn = OpenCon();//abertura da conexao

							$sql = " INSERT INTO relatorio_tb (id_empresa, data, status) values (".$yid_empresa.", ".$ydata.", ".$backup.");";

							if ($conn->query($sql) === TRUE) {}//aqui que ocorre a inserção de dados
							imap_setflag_full($mbox,$overview->uid, "\\Seen", ST_UID);
						}else{
							echo $zname[1]."\n";
						}
		            }
		        }
		    }
		} 
		/* close the connection */
		imap_close($inbox);
	}
}
function gravaNuvemAnexo($x,$y,$pass){
	//recebe a conexao
	$inbox = imap_open("{".$x.":993/imap/ssl}INBOX/Nuvem-Windows", $y, $pass)or die("can't connect: " . imap_last_error());

	$emails = imap_search($inbox,'UNSEEN');

	if($emails){
		
		/* if any emails found, iterate through each email */
		if($emails) {
		    /* put the newest emails on top */
		    rsort($emails);

		    /* for every email... */
		    foreach($emails as $email_number) {
		        /* get information specific to this email */
		        $overview = imap_fetch_overview($inbox,$email_number,0);

		        $message = imap_fetchbody($inbox,$email_number,2);

		        /* get mail structure */
		        $structure = imap_fetchstructure($inbox, $email_number);

		        $attachments = array();

		        /* if any attachments found... */
		        if(isset($structure->parts) && count($structure->parts)) 
		        {
		            for($i = 0; $i < count($structure->parts); $i++) 
		            {
		                $attachments[$i] = array(
		                    'is_attachment' => false,
		                    'filename' => '',
		                    'name' => '',
		                    'attachment' => ''
		                );

		                if($structure->parts[$i]->ifdparameters) 
		                {
		                    foreach($structure->parts[$i]->dparameters as $object) 
		                    {
		                        if(strtolower($object->attribute) == 'filename') 
		                        {
		                            $attachments[$i]['is_attachment'] = true;
		                            $attachments[$i]['filename'] = $object->value;
		                        }
		                    }
		                }

		                if($structure->parts[$i]->ifparameters) 
		                {
		                    foreach($structure->parts[$i]->parameters as $object) 
		                    {
		                        if(strtolower($object->attribute) == 'name') 
		                        {
		                            $attachments[$i]['is_attachment'] = true;
		                            $attachments[$i]['name'] = $object->value;
		                        }
		                    }
		                }

		                if($attachments[$i]['is_attachment']) 
		                {
		                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);

		                    /* 3 = BASE64 encoding */
		                    if($structure->parts[$i]->encoding == 3) 
		                    { 
		                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
		                    }
		                    /* 4 = QUOTED-PRINTABLE encoding */
		                    elseif($structure->parts[$i]->encoding == 4) 
		                    { 
		                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
		                    }
		                }
		            }
		        }
		        foreach($attachments as $attachment)
		        {
		            $overview = imap_fetch_overview($inbox,$email_number,0);
		            $overview = $overview[0];
		            $titulo = htmlentities($overview->subject);
		            $elements = imap_mime_header_decode($titulo);
		            $filename = htmlspecialchars_decode($elements[0]->text);
		            if($attachment['is_attachment'] == 1){
						$body=$sql=$name= '';

						$linhas = $attachment['attachment'];
						$linhas = explode("\n", $linhas);
						$countLinhas=count($linhas);

						$size = substr($linhas[$countLinhas-7],13);
						$pos = strpos($size, '(');
						
						if ($pos) {
							$x = explode("(", $size);
							$size = $x[0];
						}else{
							$x = explode("/", $size);
							$size = $x[0];
						}

						$errors = substr($linhas[$countLinhas-6],8);
						$checks = substr($linhas[$countLinhas-5],8);
						$pos = strpos($checks, '(');

						if ($pos) {
							$x = explode("(", $checks);
							$checks = $x[0];
						}else{
							$x = explode("/", $checks);
							$checks = $x[0];
						}

						$transferred = substr($linhas[$countLinhas-4],13);
						$pos = strpos($transferred, '(');

						if ($pos) {
							$x = explode("(", $transferred);
							$transferred = $x[0];
						}else{
							$x = explode("/", $transferred);
							$transferred = $x[0];
						}
						$data = str_replace("/","-",substr($linhas[$countLinhas-8],0,19));

						$z = SwitchGeral($titulo);
						$z = explode("-", $z);
						$id_empresa = $z[0];
						$tipo = $z[1];

						$xid = "'".$id_empresa."'";

						$xtr = preg_replace('!\s+!', '',$transferred);
						$xtr = "'".$xtr."'";

						$xch = preg_replace('!\s+!', '',$checks);
						$xch = "'".$xch."'";

						$xer = preg_replace('!\s+!', '',$errors);
						$xer = "'".$xer."'";

						$xsi = preg_replace('!\s+!', '',$size);
						$xsi = "'".$xsi."'";

						$xti = "'".$tipo."'";;

						$xda = "'".$data."'";

						$sql = "($xid, $xtr, $xer, $xsi, $xch, $xti, $xda)";

						$teste = preg_match("/[a-zA-Z]/", $transferred);

						if ($id_empresa <> '' and $id_empresa <> '0' and !$teste) {
							$conn = OpenCon();//abertura da conexao
							//echo $sql;
							$sql = "INSERT INTO nuvem (id_empresa, transferred, erros, size, checks, tipo, data) values ".$sql.";";

							if ($conn->query($sql) === TRUE) {}//aqui que ocorre a inserção de dados
							imap_setflag_full($inbox,$overview->uid, "\\Seen", ST_UID);
						}else{
							echo $titulo."\n";
						}
		            }
		        }
		    }
		}
		imap_close($inbox);
	}
}
function gravaPersonal($x,$y,$z){
	global $query;
	//recebe a conexao
	$mbox = imap_open("{".$x.":993/imap/ssl}INBOX/Personal", $y, $z)or die("can't connect: " . imap_last_error());

	$emails = imap_search($mbox,'UNSEEN');

	if($emails){

		if(!empty($emails)){
		    //Loop through the emails.
		    foreach($emails as $email){
		        $overview = imap_fetch_overview($mbox, $email);
				$overview = $overview[0];
		        $titulo = htmlentities($overview->subject);
		        $titulo = flatMimeDecode($titulo);
		        $message = imap_fetchbody($mbox, $email, 1, FT_PEEK);
				$body = nl2br($message);

				$linhas = explode("\n",str_replace("<br />", "",$body)); //transforma arquivo num array
				$countLinhas = count($linhas);
				for ($z=0; $z < $countLinhas; $z++) {
					if (strlen($linhas[$z]) < 5 or strlen($linhas[$z]) == 0 ) {
						unset($linhas[$z]);
					}
				}

				$ultimo = end($linhas); //pega a ultima linha do log
				$data = substr ($linhas[1], 15, 20); //pega a data do inicio do backup
				preg_match('/^([^-]+)[^\(]+(\([^\)]+\))/', $ultimo, $match); //separa a string pelos caracteres dentro do parenteses

				$data = substr ($linhas[1], 15, 20); //pega a data do inicio do backup
				$data = rtrim ($data);
				$data = ltrim ($data);
				
				$data = str_replace(["/"," ",":"],"-",$data);
				$date = explode("-",$data);
				$datanova= $date[2]."-".$date[1]."-".$date[0]." ".$date[3].":".$date[4].":".$date[5];

				//tratamento de verificação se o log apresentou cheio 
				$tags =  $linhas[count ($linhas)-2];
				$termo = "volume destino estar cheio";

				$pattern = '/' . $termo . '/';//Padrão a ser encontrado na string $tags
				if (preg_match($pattern, $tags)) {
				  $backup = 3;
				} else {
					$tags =  $linhas[count ($linhas)-2];
					$termohdd = 'não está disponível para backup';

					$pattern = '/' . $termohdd . '/';//Padrão a ser encontrado na string $tags
					if (preg_match($pattern, $tags)) {
						$backup = 4;
					}else{
						//tratamento da quantidade de backup
						$backup = 0;
						$tratamentotamanho = substr($match[2],1,1);
						if ($tratamentotamanho == 0){
							$backup = 2;
						}else{
							$backup = 1;
						}
					}
				}

				$params = ['title' => $titulo];
				$result = $query->execute('select id, title from backup_title where title = :title;',$params);
		
				if ($result) {
					foreach ($result as $row) {
						$id = $row['id'];
					}
				}else{
					$query->execute('insert into backup_title (title, date) values (:title, CURRENT_TIME)',$params);
					$id = $query->conn->lastInsertId();
				}

				$sql = $query->conn->prepare('insert into backup_log (id_title, log, id_status, date) values (:id_title, :log, :id_status, CURRENT_TIME)');
				$sql->bindParam('id_title',$id);
				$sql->bindParam('id_status',$backup);
				$sql->bindParam('log',$message);
				$sql->execute();
				
				imap_setflag_full($mbox,$overview->uid, "\\Seen", ST_UID);
		    }			
		}
	}
}
function gravaWindows($x,$y,$z){
	$mbox = imap_open("{".$x.":993/imap/ssl}INBOX/Windows", $y, $z)or die("can't connect: " . imap_last_error());

	$emails = imap_search($mbox,'UNSEEN');

	if($emails){
		if(!empty($emails)){
		    foreach($emails as $email){
		        $overview = imap_fetch_overview($mbox, $email);
				$overview = $overview[0];
		        $data = htmlentities($overview->date)."\n";
		        $message = imap_fetchbody($mbox, $email, 1, FT_PEEK);

				$data = substr($data, 5, -7);
				$data = explode(";", str_replace([" ",":"], ";", $data));
				$datanova = $data[2]."-".date('m', strtotime($data[1]))."-".$data[0]." ".$data[3].":".$data[4].":".$data[5];

		        $elements = imap_mime_header_decode(htmlentities($overview->subject));

				$id_empresa = SwitchGeral(utf8_decode(rtrim(ltrim(htmlspecialchars_decode($elements[0]->text)))));
				$teste = preg_match('/sucesso/', $message);

				if ($id_empresa <> 0 and $id_empresa <>'' and $teste) {
					$sql ="('".$id_empresa."', '".$datanova."', '1')";

					$conn = OpenCon();//abertura da conexao

					$sql = " INSERT INTO relatorio_tb (id_empresa, data, status) values ".$sql.";";

					if ($conn->query($sql) === TRUE) {}//aqui que ocorre a inserção de dados
					imap_setflag_full($mbox,$overview->uid, "\\Seen", ST_UID);
				}else{
					echo $titulo2."\n";
				}
		    }
		}
	}
}
function gravaNuvem($x,$y,$z){
	$mbox = imap_open("{".$x.":993/imap/ssl}INBOX/Nuvem", $y, $z)or die("can't connect: " . imap_last_error());

	$emails = imap_search($mbox,'UNSEEN');

	if($emails){
		if(!empty($emails)){
		    foreach($emails as $email){
		        $overview = imap_fetch_overview($mbox, $email);
				$overview = $overview[0];
		        $titulo = htmlentities($overview->subject);
		        $message = imap_fetchbody($mbox, $email, 1, FT_PEEK);
				$linhas = nl2br($message);

		        $elements = imap_mime_header_decode($titulo);
		        $titulo = htmlspecialchars_decode($elements[0]->text);

				$linhas = explode("<br />", $linhas);
				$countLinhas=count($linhas);

				$size = substr($linhas[$countLinhas-6],14);
				$pos = strpos($size, '(');
				
				if ($pos) {
					$x = explode("(", $size);
					$size = $x[0];
				}else{
					$x = explode("/", $size);
					$size = $x[0];
				}

				$errors = substr($linhas[$countLinhas-5],9);
				$checks = substr($linhas[$countLinhas-4],9);
				$pos = strpos($checks, '(');

				if ($pos) {
					$x = explode("(", $checks);
					$checks = $x[0];
				}else{
					$x = explode("/", $checks);
					$checks = $x[0];
				}

				$transferred = substr($linhas[$countLinhas-3],14);
				$pos = strpos($transferred, '(');

				if ($pos) {
					$x = explode("(", $transferred);
					$transferred = $x[0];
				}else{
					$x = explode("/", $transferred);
					$transferred = $x[0];
				}
				$data = "'".str_replace("/","-",substr($linhas[$countLinhas-7],2,19))."'";

				$z = SwitchGeral($titulo);
				$z = explode("-", $z);

				$xid = "'".$z[0]."'";
				$xtr = "'".preg_replace('!\s+!', '',$transferred)."'";
				$xch = "'".preg_replace('!\s+!', '',$checks)."'";
				$xer = "'".preg_replace('!\s+!', '',$errors)."'";
				$xsi = "'".preg_replace('!\s+!', '',$size)."'";
				$xti = "'".$z[1]."'";

				$sql = "($xid, $xtr, $xer, $xsi, $xch, $xti, $data)";

				if ($xid <> '' and $xid <> '0') {
					$conn = OpenCon();//abertura da conexao

					$sql = "INSERT INTO nuvem (id_empresa, transferred, erros, size, checks, tipo, data) values ".$sql.";";

					if ($conn->query($sql) === TRUE) {}//aqui que ocorre a inserção de dados
					imap_setflag_full($mbox,$overview->uid, "\\Seen", ST_UID);
				}else{
					echo $titulo."\n";
				}
		    }
		}
	}
}
function gravaScript($x,$y,$z){
	$mbox = imap_open("{".$x.":993/imap/ssl}INBOX/Script", $y, $z)or die("can't connect: " . imap_last_error());

	$emails = imap_search($mbox,'UNSEEN');

	if($emails){
		if(!empty($emails)){
		    foreach($emails as $email){
		        $overview = imap_fetch_overview($mbox, $email);
				$overview = $overview[0];
		        $message = imap_fetchbody($mbox, $email, 1, FT_PEEK);
		        $data = htmlentities($overview->date)."\n";
		        $elements = imap_mime_header_decode(htmlentities($overview->subject));
		        $titulo = htmlspecialchars_decode($elements[0]->text);

				if(preg_match('/VERIFICACAO/', $titulo)){
					$countLinhas = count(explode("\n", $message));
					$data = substr($data, 5, -7);
					$data = explode(";", str_replace([" ",":"], ";", $data));
					$datanova = $data[2]."-".date('m', strtotime($data[1]))."-".$data[0]." ".$data[3].":".$data[4].":".$data[5];
					if ($countLinhas < 3) {
						$backup = 2;
					}else{
						$backup = 1;
					}
					$id_empresa = SwitchGeral($titulo);
					if ($id_empresa <> 0 and $id_empresa <> '') {
						$sql = "INSERT INTO relatorio_tb (id_empresa, data, status) VALUES ('".$id_empresa."', '".$datanova."', '".$backup."')";
						$conn=OpenCon();
						$result=$conn->query($sql);
						imap_setflag_full($mbox,$overview->uid, "\\Seen", ST_UID);
					}else{
						echo $titulo."\n";
					}
				}
		    }
		}
	}
}
function gravaReplic($x,$y,$z){
	//recebe a conexao
	$mbox = imap_open("{".$x.":993/imap/ssl}INBOX/Replica", $y, $z)or die("can't connect: " . imap_last_error());

	$emails = imap_search($mbox,'UNSEEN');

	if($emails){
		if(!empty($emails)){
		    //Loop through the emails.
		    foreach($emails as $email){
		        $overview = imap_fetch_overview($mbox, $email);
				$overview = $overview[0];
		        $message = imap_fetchbody($mbox, $email, 1, FT_PEEK);
		        $data = htmlentities($overview->date)."\n";
		        $elements = imap_mime_header_decode(htmlentities($overview->subject));
		        $titulo = htmlspecialchars_decode($elements[0]->text);

				if(preg_match('/VERIFICACAO/', $titulo)){
					$countLinhas = count(explode("\n", $message));
					$data = substr($data, 5, -7);
					$data = explode(";", str_replace([" ",":"], ";", $data));
					$datanova = $data[2]."-".date('m', strtotime($data[1]))."-".$data[0]." ".$data[3].":".$data[4].":".$data[5];

					if ($countLinhas < 5) {
						$backup = 2;
					}else{
						$backup = 1;
					}

					$id_empresa = SwitchGeral($titulo);
					
					if ($id_empresa <> 0 and $id_empresa <> '') {
						$sql = "INSERT INTO relatorio_tb (id_empresa, data, status, replic) VALUES ('".$id_empresa."', '".$datanova."', '".$backup."', 'Sim')";
						$conn=OpenCon();
						$result=$conn->query($sql);
						imap_setflag_full($mbox,$overview->uid, "\\Seen", ST_UID);
					}else{
						echo $titulo."\n";
					}
				}
		    }
		}
	}
}
/*
gravaWindows($host, $usuario, $senha);
gravaScript($host, $usuario, $senha);
gravaCobian($host, $usuario, $senha);
gravaNuvem($host, $usuario, $senha);
gravaReplic($host, $usuario, $senha);
gravaNuvemAnexo($host, $usuario, $senha);
*/

gravaPersonal($host, $usuario, $senha);
?>
