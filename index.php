<?php 

	header('Content-Type: text/html; charset=utf-8');
	session_start();
	ob_start();
	error_reporting(0);

	//Conecta no banco do sistema 
	include 'conecta.php'; 
	include 'lib/php/funcoes_html.php'; 

	$sql = "SELECT a.*, c.nome as nome_curso 
			FROM aluno a
			INNER JOIN curso c ON c.id_curso = a.id_curso
			WHERE a.id_aluno = '".$_SESSION['aluno']."'";
	$sql = mysql_query($sql) or die(mysql_error());
	$aluno = mysql_fetch_assoc($sql);
	$nome_aluno = $aluno['nome'];
	$nome_curso = $aluno['nome_curso'];
	
?> 

<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		

		<!-- Bootstrap - Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

		<!-- Google Fonts -->
		<link href='https://fonts.googleapis.com/css?family=Bitter:400,700|Open+Sans:400,300' rel='stylesheet' type='text/css'>

		<!-- Custom styles -->
		<link  rel="stylesheet" href="<?php echo $path;?>/lib/css/styles.css">
		
		<!-- Font Awesome-->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

		<!-- Sweet Alert -->
		<link rel="stylesheet" type="text/css" href="<?php echo $path;?>/lib/plugins/sweetalert-master/dist/sweetalert.css">
		<script src="<?php echo $path;?>/lib/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
	</head>

	<body>
		<?php 

			if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['matricula']) && !empty($_POST['senha'])) {
				
				$senha = stripslashes($_POST['senha']);
				$senha = mysql_real_escape_string($_POST['senha']);
				$matricula = stripslashes($_POST['matricula']);
				$matricula = mysql_real_escape_string($_POST['matricula']);

				$sql = "SELECT a.*, c.nome as nome_curso 
						FROM aluno a
						INNER JOIN curso c ON c.id_curso = a.id_curso
						WHERE a.matricula = '".$matricula."' AND a.senha = '".$senha."' ";
				$sql = mysql_query($sql) or die(mysql_error());

				$count_sql = mysql_num_rows($sql); //conta a quantidade de linhas do resultado da consulta

				$aluno = array();

				//Login Correto
				if ($count_sql == 1) {

					$aluno = mysql_fetch_assoc($sql);
					$_SESSION['aluno']      = $aluno['id_aluno'];
					$_SESSION['nome_aluno'] = $aluno['nome'];
					$_SESSION['curso']      = $aluno['id_curso'];
					$_SESSION['nome_curso'] = $aluno['nome_curso'];
					include 'main.php';
				}

				//Login Incorreto
				else{
					$msg = "<script>swal('Erro!','Matricula ou senha incorretos','error')</script>";
					include 'login.php';
				}
			}
			else{
				if (empty($_SESSION['aluno'])) {
					include 'login.php';
				}
				else{
					if (isset($_GET['secao'])) {
						switch ($_GET['secao']) {
							case 'perfil':
								include 'perfil.php';
								break;

							case 'monitores':
								include 'monitores.php';
								break;
							
							case 'solicitacoes':
								include 'solicitacoes.php';
								break;
							
							default:
								include 'main.php';
								break;
						}
					}
					include 'main.php';
				}
			}
		?>
		
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>


		<!-- Custom JS -->
		<link  rel="stylesheet" href="<?php echo $path;?>/lib/js/utils.js">

	</body>
</html>