
<?php
//Esse arquivo "inserir.php" deverá fazer a "ponte/comunicação" do que foi inserido no formulário e guardar no meu Banco de Dados. -- SCRIPTS PRONTOS 05 
// Essa api, fara o trabalho de inserir e de editar -- Aprtir da aula 41 o codigo vai mudar - se quiser ver o antigo, basta acessar o SCRIPTS PRONTOS 12   
    require_once('../conexao.php'); //  'require_once': incluir um arquivo PHP em outro -- Conecta inserir.php com conexao.php
    $postjson = json_decode(file_get_contents('php://input'), true); // Essa função irá receber tudo que vier das caixas de textos, ou seja "inputs" (nome, cpf, email...). 
   
    $nome = $postjson['nome'];
    $email = $postjson['email'];
    $cpf = $postjson['cpf'];
    $senha = $postjson['senha'];
    $nivel = $postjson['nivel']; //@ - Ignora a possibilidade de erro, pelo campo esta vazio
    $id = @$postjson['id'];        //Antigo= email & antigo2= cpf --- "id, antigo e antigo2"= Ref. aos campos que estão sendo editados
    $antigo = @$postjson['antigo'];
    $antigo2 = @$postjson['antigo2']; //P1. Esses são os elementos que estão chegando via input, cuja informação deve estar entre 'aspas' e com o mesmo nome que se encontra em
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
   if($antigo != $email){ //Se o email que estou passando, for diferente do aintigo email, quero que exista a ferificação de duplicidade. -- Ja no momente de "adicionar" isso não ocorre pois o campo antigo esta vazio, o email salvo em adicionar vai preencher o campo "antigo" e a verificação vai ocorrer na edição.

	$query_con = $pdo->prepare("SELECT * from usuarios WHERE email = :email");
	$query_con->bindValue(":email", $email);
	$query_con->execute();
	$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res_con) > 0){
		echo json_encode(array('mensagem'=>'Email já cadastrado!'));
        exit();
	}
}


// EVITAR DUPLICIDADE NO CPF
   if($antigo2 != $cpf){  //Mesma coisa do email
   
	$query_con = $pdo->prepare("SELECT * from usuarios WHERE cpf = :cpf");
	$query_con->bindValue(":cpf", $cpf);
	$query_con->execute();
	$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res_con) > 0){
		echo json_encode(array('mensagem'=>'CPF já cadastrado!'));
		exit();
	}
     
   }


//Se o id for vazio, deve nserir dados na tabela Usuarios no Banco.
    if($id == ""){ 
	$res = $pdo->prepare("INSERT INTO usuarios SET nome = :nome, email = :email, cpf = :cpf, senha = :senha, nivel = :nivel");

    } else {  //Caso contrario "UPDATE" edita os dados
    $res = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, cpf = :cpf, senha = :senha, nivel = :nivel WHERE id = :id");
	$res->bindValue(":id", $id);
    }

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