
<?php
//Esse arquivo "inserir.php" deverá fazer a "ponte/comunicação" do que foi inserido no formulário e guardar no meu Banco de Dados. -- SCRIPTS PRONTOS 05 
   
    require_once('../conexao.php'); //  'require_once': incluir um arquivo PHP em outro -- Conecta inserir.php com conexao.php
    $postjson = json_decode(file_get_contents('php://input'), true); // Essa função irá receber tudo que vier das caixas de textos, ou seja "inputs" (nome, cpf, email...). 
   
    $nome = $postjson['nome'];
    $email = $postjson['email'];
    $cpf = $postjson['cpf'];
    $senha = $postjson['senha'];
    $nivel = $postjson['nivel']; //P1. Esses são os elementos que estão chegando via input, cuja informação deve estar entre 'aspas' e com o mesmo nome que se encontra em
                                 //P2. "cadastrar ()" no arquivo 'add-usuario.page.ts' 
    if($nome == ""){                                                 
        echo json_encode(array('mensagem'=>'Preencha o Campo Nome!'));
        exit(); 
    }

    if($cpf == ""){
        echo json_encode(array('mensagem'=>'Preencha o Campo CPF!'));
        exit();
    }

    if($email == ""){
        echo json_encode(array('mensagem'=>'Preencha o Campo Email!'));
        exit();
    }


    if($senha == ""){
        echo json_encode(array('mensagem'=>'Preencha o Campo Senha!'));
        exit();
    }

    if($nivel == ""){
        echo json_encode(array('mensagem'=>'Preencha o Campo Nivel!'));
        exit();
    }



// EVITAR DUPLICIDADE NO EMAIL
	$query_con = $pdo->prepare("SELECT * from usuarios WHERE email = :email");
	$query_con->bindValue(":email", $email);
	$query_con->execute();
	$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res_con) > 0){
		echo json_encode(array('mensagem'=>'Email já cadastrado!'));
        exit();
	}



// EVITAR DUPLICIDADE NO CPF
	$query_con = $pdo->prepare("SELECT * from usuarios WHERE cpf = :cpf");
	$query_con->bindValue(":cpf", $cpf);
	$query_con->execute();
	$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res_con) > 0){
		echo json_encode(array('mensagem'=>'CPF já cadastrado!'));
		exit();
	}


//Inserindo dados na tabela Usuarios no Banco.
	$res = $pdo->prepare("INSERT INTO usuarios SET nome = :nome, email = :email, cpf = :cpf, senha = :senha, nivel = :nivel");
	$res->bindValue(":nome", $nome);
	$res->bindValue(":email", $email);
	$res->bindValue(":cpf", $cpf);
	$res->bindValue(":senha", $senha);
	$res->bindValue(":nivel", $nivel);
	$res->execute();


/*   P1. Devolvendo dados para o banco, anteriormente quando recebemos, utilizamos o "json_decode" para decodificar os dados que estamos recebendo. 
    Já agora, que vamos enviar, precisamos utilizar o "json_encode" - criamos o elemento "array" que permite passar varias informações e dentro dele passamos 2 elementos:
    Mensagem que será ='salvo com sucesso' se todos os campos estiverem preenchidos da forma correta e OK.*/

    $result = json_encode(array('mensagem'=>'Salvo com Sucesso', 'ok'=>true)); 
    echo $result;
?>