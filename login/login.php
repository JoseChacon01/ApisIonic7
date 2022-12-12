
<?php
    require_once('../conexao.php'); //  'require_once': incluir um arquivo PHP em outro -- Conecta inserir.php com conexao.php
    $postjson = json_decode(file_get_contents('php://input'), true); // Essa função irá receber tudo que vier das caixas de textos, ou seja "inputs" (nome, cpf, email...). 
   
    
    $usuario = $postjson['usuario'];
    $senha = $postjson['senha'];

    if($usuario == ""){                                                 
        echo json_encode(array('mensagem'=>'Preencha o Campo Usuario!'));
        exit(); 
    }

    if($senha == ""){
        echo json_encode(array('mensagem'=>'Preencha o Campo Senha!'));
        exit();
    }

	$query_con = $pdo->prepare("SELECT * from usuarios WHERE (email = :usuario or cpf = :usuario) and senha = :senha"); // O usuario pode logar tanto com email quanto com a senha.
	$query_con->bindValue(":usuario", $usuario);
    $query_con->bindValue(":senha", $senha);
	$query_con->execute();
	$res = $query_con->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res) > 0){

        $dados = array( //recuperando dados do usuario logado.
            'id' => $res[0]['id'],
            'nome' => $res[0]['nome'],
            'cpf' => $res[0]['cpf'],
            'email' => $res[0]['email'],
            'senha' => $res[0]['senha'],
            'nivel' => $res[0]['nivel'],

        );

        $result = json_encode(array('mensagem'=>'Bem-vindo(a)!', 'ok'=>true, 'usu'=>$dados)); //usu, vai receber a variavel 'dados.
        echo $result;
	}else{
        $result = json_encode(array('mensagem'=>'Dados incorretos!', 'ok'=>false)); 
        echo $result;  
    }

?>